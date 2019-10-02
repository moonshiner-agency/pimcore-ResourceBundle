<?php

namespace Moonshiner\BrigthenBundle\Event\Form;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

class SubmissionEvent extends Event
{
    /**
     * @var Request
     */
    private $request;

    /**
     * @var array
     */
    private $formConfiguration;

    /**
     * @var FormInterface
     */
    private $form;

    /**
     * @var int
     */
    private $formID;

    /**
     * @var string
     */
    private $relatedToKey;

    /**
     * @var null|string
     */
    private $redirectUri = null;

    /**
     * @param Request       $request
     * @param array         $formConfiguration
     * @param FormInterface $form
     * @param int           $formID
     * @param string        $relatedToKey
     */
    public function __construct(Request $request, $formConfiguration, FormInterface $form, int $formID, string $relatedToKey)
    {
        $this->request = $request;
        $this->formConfiguration = $formConfiguration;
        $this->form = $form;
        $this->formID = $formID;
        $this->relatedToKey = $relatedToKey;
    }

    /**
     * @param string $uri
     */
    public function setRedirectUri($uri)
    {
        $this->redirectUri = $uri;
    }

    /**
     * @return mixed
     */
    public function getRedirectUri()
    {
        return $this->redirectUri;
    }

    /**
     * @return bool
     */
    public function hasRedirectUri()
    {
        return !is_null($this->redirectUri);
    }

    /**
     * @return Request
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @return array
     */
    public function getFormConfiguration()
    {
        return $this->formConfiguration;
    }

    /**
     * @return FormInterface
     */
    public function getForm()
    {
        return $this->form;
    }

    /**
     * @return int
     */
    public function getFormID()
    {
        return $this->formID;
    }

    /**
     * @return string
     */
    public function getRelatedToKey()
    {
        return $this->relatedToKey;
    }
}
