<?php
/**
 * Pimcore
 *
 * This source file is available under two different licenses:
 * - GNU General Public License version 3 (GPLv3)
 * - Pimcore Enterprise License (PEL)
 * Full copyright and license information is available in
 * LICENSE.md which is distributed with this source code.
 *
 * @copyright  Copyright (c) Pimcore GmbH (http://www.pimcore.org)
 * @license    http://www.pimcore.org/license     GPLv3 and PEL
 */
namespace Moonshiner\BrigthenBundle\Controller;

use Pimcore\Controller\FrontendController;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;

abstract class AbstractController extends FrontendController
{
    /**
     * @inheritDoc
     */
    public function onKernelController(FilterControllerEvent $event)
    {
        $request = $event->getRequest();
        $locale = $request->get('locale') ? $request->get('locale') : $request->attributes->get('_locale');
        if ($locale) {
            $request->getSession()->set('_locale', $locale);
            $request->setLocale($locale);
        } else {
            // if no explicit locale has been set on this request, use one from the session
            $request->setLocale($request->getSession()->get('_locale', 'en'));
        }
        // enable view auto-rendering
        $this->setViewAutoRender($request, true, 'php');
        //get first two characters of locale for language
        $this->view->language = substr($request->getLocale(), 0, 2);
    }
}
