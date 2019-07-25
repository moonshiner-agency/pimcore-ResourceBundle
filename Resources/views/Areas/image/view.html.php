<?php

use Moonshiner\BrigthenBundle\Services\Service\SystemSettings;

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

/**
 * @var \Pimcore\Templating\PhpEngine $this
 * @var \Pimcore\Templating\PhpEngine $view
 * @var \Pimcore\Templating\GlobalVariables $app
 */
?>

<?php
if (!$this->editmode) {
    $image = $this->image('image');
    $this->slots()->components[] = [
        'type' => 'image',
        'content' => SystemSettings::getHostUrl() . $image->getThumbnail('galleryLightbox')->getPath(),
        'hotspots' => $image->getHotSpots(),
        'name' => $image->getName()
    ];
} else {
    ?>
    <section class="area-image">
        <div class="cms-component-type">Image</div>
        <div class="row">
            <div class="col-sm-4 mb-20">
                <?= $this->image('image', [
                    'thumbnail' => 'content'
                ]); ?>
            </div>
        </div>
    </section>
<?php
} ?>
