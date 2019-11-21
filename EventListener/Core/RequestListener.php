<?php

namespace Moonshiner\BrigthenBundle\EventListener\Core;

use FormBuilderBundle\Form\Builder;
use FormBuilderBundle\Form\FormErrorsSerializerInterface;
use FormBuilderBundle\FormBuilderEvents;
use FormBuilderBundle\Session\FlashBagManagerInterface;
use Moonshiner\BrigthenBundle\Event\Form\SubmissionEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

class RequestListener implements EventSubscriberInterface
{
    /**
     * @var Builder
     */
    protected $formBuilder;

    /**
     * @var EventDispatcherInterface
     */
    protected $eventDispatcher;

    /**
     * @var SessionInterface
     */
    protected $session;

    /**
     * @var FlashBagManagerInterface
     */
    protected $flashBagManager;

    /**
     * @var FormErrorsSerializerInterface
     */
    protected $formErrorsSerializer;

    /**
     * RequestListener constructor.
     *
     * @param Builder                       $formBuilder
     * @param EventDispatcherInterface      $eventDispatcher
     * @param SessionInterface              $session
     * @param FlashBagManagerInterface      $flashBagManager
     * @param FormErrorsSerializerInterface $formErrorsSerializer
     */
    public function __construct(
        Builder $formBuilder,
        EventDispatcherInterface $eventDispatcher,
        SessionInterface $session,
        FlashBagManagerInterface $flashBagManager,
        FormErrorsSerializerInterface $formErrorsSerializer,
        CsrfTokenManagerInterface $csrfTokenManager
    ) {
        $this->formBuilder = $formBuilder;
        $this->eventDispatcher = $eventDispatcher;
        $this->session = $session;
        $this->flashBagManager = $flashBagManager;
        $this->formErrorsSerializer = $formErrorsSerializer;
        $this->csrfTokenManager = $csrfTokenManager;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::REQUEST => ['onKernelRequest'],
        ];
    }

    /**
     * @param GetResponseEvent $event
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        /** @var \Symfony\Component\HttpFoundation\Session\Attribute\NamespacedAttributeBag $sessionBag */
        $sessionBag = $this->session->getBag('form_builder_session');
        $formConfiguration = [];

        if (!$event->isMasterRequest()) {
            return;
        }

        $request = $event->getRequest();
        if (!$request->isMethod('POST')) {
            return;
        }

        if ($request->getContentType() != 'json' || !$request->getContent()) {
            return;
        }

        $data = json_decode($request->getContent(), true);
        if (!isset($data['data'])) {
            $data['data'] = [];
        }
        if (!isset($data['relatedTo'])) {
            return;
        }
        $relatedTo = $data['relatedTo'];
        $fileNames = null;
        if (isset($data['files'])) {
            $fileNames = $data['files'];
        }
        $formId = $this->getFormID($data);
        if ($formId === null) {
            return;
        }

        try {
            $userOptions = isset($formConfiguration['user_options']) ? $formConfiguration['user_options'] : [];
            $form = $this->formBuilder->buildForm($formId, ['csrf_protection' => false]);

            $csrfToken = $this->csrfTokenManager->refreshToken('formbuilder_'.$formId)->getValue();
            $form->submit(array_merge($data['data'], ['_token' => $csrfToken]));
        } catch (\Exception $e) {
            if ($request->isXmlHttpRequest()) {
                $response = new JsonResponse([
                    'success' => false,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTrace()
                ]);
                $response->setStatusCode(420);
                $event->setResponse($response);
            }

            return;
        }

        if ($form->isValid()) {
            if ($sessionBag->has('form_configuration_' . $formId)) {
                $sessionBag->remove('form_configuration_' . $formId);
            }

            $submissionEvent = new SubmissionEvent($request, $formConfiguration, $form, $formId, $relatedTo, $fileNames);
            // not using the standard FormBuilderEvents::FORM_SUBMIT_SUCCESS - to prevent maillistener from reacting to the event
            $this->eventDispatcher->dispatch('form_builder.submitted.success', $submissionEvent);

            $eventErrors = $submissionEvent->getErrors();
            if (!empty($eventErrors)) {
                $this->handleAjaxErrorResponse($event, $form, $eventErrors);

                return;
            }

            if ($request->isXmlHttpRequest()) {
                $this->handleAjaxSuccessResponse($event, $submissionEvent, $formId);
            } else {
                $this->handleDefaultSuccessResponse($event, $submissionEvent);
            }
        } else {
            //only ajax forms want some feedback.
            if ($request->isXmlHttpRequest()) {
                $this->handleAjaxErrorResponse($event, $form, $this->getErrors($form));
            }
        }
    }

    protected function getFormID(array $data): ?int
    {
        $formID = null;

        foreach ($data as $key => $parameters) {
            if (strpos($key, 'formbuilder_') === false) {
                continue;
            }

            if (isset($parameters['formId'])) {
                $formID = $parameters['formId'];
                break;
            }
        }

        return $formID;
    }

    /**
     * @param GetResponseEvent $event
     * @param SubmissionEvent  $submissionEvent
     */
    protected function handleDefaultSuccessResponse(GetResponseEvent $event, SubmissionEvent $submissionEvent)
    {
        $uri = '?send=true';
        if ($submissionEvent->hasRedirectUri()) {
            $uri = $submissionEvent->getRedirectUri();
        }

        $response = new RedirectResponse($uri);
        $event->setResponse($response);
    }

    /**
     * @param GetResponseEvent $event
     * @param SubmissionEvent  $submissionEvent
     * @param string           $formId
     */
    protected function handleAjaxSuccessResponse(GetResponseEvent $event, SubmissionEvent $submissionEvent, $formId)
    {
        $redirectUri = null;
        if ($submissionEvent->hasRedirectUri()) {
            $redirectUri = $submissionEvent->getRedirectUri();
        }

        $messages = [];
        $error = false;

        foreach (['success', 'error'] as $type) {
            $messageKey = 'formbuilder_' . $formId . '_' . $type;

            if (!$this->flashBagManager->has($messageKey)) {
                continue;
            }

            foreach ($this->flashBagManager->get($messageKey) as $message) {
                if ($type === 'error') {
                    $error = true;
                }
                $messages[] = ['type' => $type, 'message' => $message];
            }
        }

        $response = new JsonResponse([
            'success' => !$error,
            'redirect' => $redirectUri,
            'messages' => $messages
        ]);

        $event->setResponse($response);
    }

    /**
     * @param GetResponseEvent $event
     * @param FormInterface    $form
     */
    protected function handleAjaxErrorResponse(GetResponseEvent $event, FormInterface $form, $errors)
    {
        $response = new JsonResponse([
            'success' => false,
            'validation_errors' => $errors,
        ]);

        $event->setResponse($response);
    }

    /**
     * @param FormInterface $form
     *
     * @return array
     */
    protected function getErrors(FormInterface $form)
    {
        return $this->formErrorsSerializer->getErrors($form);
    }
}
