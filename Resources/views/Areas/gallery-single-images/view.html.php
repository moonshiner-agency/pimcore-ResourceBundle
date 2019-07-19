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

/**
 * @var \Pimcore\Templating\PhpEngine $this
 * @var \Pimcore\Templating\PhpEngine $view
 * @var \Pimcore\Templating\GlobalVariables $app
 */
?>

<?php
$block = $this->block('gallery');

if (!$this->editmode) {
    $items = [];
    while ($block->loop()) {
        $items[] = $this->image('image')->getData();
    }
    $this->slots()->components[] = [
        'type' => 'CmsGallery',
        'items' => $items
    ];
} else {
    ?>
    <section class="area-gallery-single-images">
        <div class="cms-component-type">Gallery (Single)</div>
        <div class="row">
            <?php
            while ($block->loop()) { ?>
                <div class="col-md-3 col-xs-6">
                    <?= $this->image('image', [
                        'thumbnail' => 'galleryThumbnail'
                    ]); ?>
                </div>
            <?php } ?>
        </div>

    </section>

<?php } ?>