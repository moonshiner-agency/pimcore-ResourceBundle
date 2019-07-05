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

use function GuzzleHttp\json_decode;
use Pimcore\Bundle\AdminBundle\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ContentController extends AbstractController
{
    public function portalAction(Request $request)
    {
        $this->view->isPortal = true;


        $rendered = $this->render(':Content:portal.html.php', ['document' => $this->document]);
        if (!$this->editmode) {
            return new JsonResponse(json_decode($rendered->getContent(), true));
        }
    }

    public function defaultAction()
    {
        return [];
    }
}
