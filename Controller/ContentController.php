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
use Symfony\Component\HttpFoundation\Response;

class ContentController extends AbstractController
{
    public function portalAction()
    {
        $this->view->isPortal = true;
        $test = $this->render(':Content:portal.html.php', ['document' => $this->document]);
        try {
            if (!$this->editmode) {
                $response = new JsonResponse(json_decode($test->getContent(), true));
            }
        } catch (\Exception $e) {
            $response = new Response($test->getContent());
        }

        return $response;
    }

    public function defaultAction()
    {
        return [];
    }
}
