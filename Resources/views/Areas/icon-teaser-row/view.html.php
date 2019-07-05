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

<?php $block = $this->block('iconTeaserBlock', ['default' => 1]); ?>

<?php
    if (!$this->editmode) {
        $data = [];
        while ($block->loop()) {
            $tagBlock = $this->block('iconTeaserTagBlock'.$block->getCurrent(), ['default' => 1]);

            $tags = [];
            while ($tagBlock->loop()) {
                $tags[] = [
                    'text' => $this->input('tag_text')->getData(),
                    'icon' => $this->select('tag_icon')->getData()
                ];
            }

            $image = $this->image('image');
            $data[] = [
                'title' => $this->input('title')->getData(),
                'image' => $image->getThumbnail('galleryLightbox') !== '' ? (\Pimcore\Tool::getHostUrl() . $image->getThumbnail('galleryLightbox')->getPath()) : null,
                'content' => $this->textarea('text')->getData(),
                'number' => $this->input('number')->getData(),
                'isSmall' => $this->checkbox('isSmall')->isChecked(),
                'tags' => $tags,
            ];
            $tagID++;
        }

        if ($block->getCount() <= 1) {
            $this->slots()->components[] = [
                'type' => 'CmsListItem',
                'data' => ($data[0] ? $data[0] : $data)
            ];
        } else {
            $this->slots()->components[] = [
                'type' => 'CmsList',
                'items' => $data
            ];
        }
    } else {
        ?>
    <section class="area-icon-teaser-row">
        <div class="cms-component-type">Icon Teaser</div>
        <div class="row">
            <?php while ($block->loop()) { ?>
                <div class="col-sm-4">
                    <div class="mb-20">
                        <label>Image:</label><br />
                        <?= $this->image('image', [
                            'thumbnail' => 'galleryLightbox'
                        ]); ?>
                    </div>

                    <div class="mb-20">
                        <label>Title:</label><br />
                        <h3 class="title noMarginTop"><?= $this->input('title', ['placeholder' => 'Title']) ?></h3>
                    </div>

                    <div class="mb-20">
                        <label>Text:</label><br />
                        <?= $this->textarea('text', ['placeholder' => 'Text', 'height' => '70']) ?>
                    </div>

                    <div class="mb-20">
                        <label>Number:</label><br />
                        <?= $this->input('number', ['placeholder' => 'Number']) ?>
                    </div>

                    <div class="mb-20">
                        <label>Size:</label><br />
                        <?= $this->checkbox('isSmall') ?> small
                    </div>

                    <div class="mb-20">
                        <label>Tags:</label><br />

                        <?php $tagBlock = $this->block('iconTeaserTagBlock'.$block->getCurrent()); ?>
                        <?php while ($tagBlock->loop()) { ?>
                            <p>Tag <?= $tagBlock->getCurrent()+1 ?>:</p>
                            <div class="mb-20">
                                <p>Tag title:</p>
                                <?= $this->input('tag_text', ['placeholder' => 'Tag title']) ?>
                            </div>
                            <div class="mb-20">
                                <p>Tag icon:</p>
                                <?= $this->select('tag_icon', [
                                    'store' => ['car', 'pinLocation', 'people', 'area'],
                                    'width' => 150
                                ]); ?>
                            </div>
                        <?php } ?>
                    </div>

                </div>
            <?php } ?>
        </div>
    </section>

    <style>
        h3.noMarginTop {
            margin-top: 0;
        }
    </style>

<?php
    } ?>
