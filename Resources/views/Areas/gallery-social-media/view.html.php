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

use Moonshiner\BrigthenBundle\JsonResources\ImageResource;

?>

<?php $block = $this->block('socialMediaGalleryBlock', ['default' => 1]); ?>

<?php if ($this->editmode) {
    ?>
    <section>
        <div class="cms-component-type">Social Media Gallery</div>
        <div class="row">
            <?php while ($block->loop()) { ?>
                <div class="col-md-8 mb-20">
                    <div>
                        <label class="text-info">Image:</label><br />
                        <div>
                            <?= $this->image('image'); ?>
                        </div>
                    </div>
                    <hr>
                    <div class="mb-20">
                        <label class="text-info">Facebook link:</label><br />
                        <?= $this->link('link_facebook'); ?>
                    </div>
                    <hr>
                    <div class="mb-20">
                        <label class="text-info">Instagram link:</label><br />
                        <?= $this->link('link_instagram'); ?>
                    </div>
                    <hr>
                    <div class="mb-20">
                        <label class="text-info">Twitter link:</label><br />
                        <?= $this->link('link_twitter'); ?>
                    </div>
                </div>
            <?php } ?>
        </div>
    </section>
<?php
} else {
    $data = [];
    while ($block->loop()) {
        $image = $this->image('image');
        $twitter = $this->link('link_twitter');
        $facebook = $this->link('link_facebook');
        $instagram = $this->link('link_instagram');

        if($this->image('image')->getImage()) {
            $data[] = [
                'urls' => [
                    'twitter' => $twitter->isEmpty() ? null : $twitter->getHref(),
                    'facebook' => $facebook->isEmpty() ? null : $facebook->getHref(),
                    'instagram' => $instagram->isEmpty() ? null : $instagram->getHref(),
                ],
                'image' => $this->image('image')->getImage() ? (new ImageResource($this->image('image')->getImage()))->toArray() : null
            ];
        }
    }

    $this->slots()->components[] = [
        'type' => 'CmsGallerySocialMedia',
        'items' => $data
    ];
} ?>