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

<?php $block = $this->block('cardBlock', ['default' => 1]); ?>

<?php if ($this->editmode) {
    ?>
    <section>
        <div class="cms-component-type">Card</div>
        <div class="row">
            <?php if ($block->getCount() > 1) { ?>
                <div class="col-sm-4">
                    <div class="mb-20">
                        <label>List options:</label><br />
                        <p>Scrolling behaviour:</p>
                        <?= $this->select('variant', [
                            'store' => ['scroll', 'col2', 'col3'],
                            'width' => 300,
                            'default' => 'col2',
                        ])->setDataFromResource('col2'); ?>
                    </div>
                </div>
            <?php } ?>

            <?php while ($block->loop()) { ?>
                <div class="col-md-4 mb-20">
                    <div class="mb-20">
                        <label>Image:</label><br />
                        <?= $this->image('image') ?>
                    </div>
                    <div class="mb-20">
                        <label>Headline:</label><br />
                        <h3 class="noMarginTop"><?= $this->input('title', ['placeholder' => 'Headline']) ?></h3>
                    </div>
                    <div class="mb-20">
                        <label>Subline:</label><br />
                       <?= $this->input('subtitle', ['placeholder' => 'Subline']) ?>
                    </div>
                    <div class="mb-20">
                        <label>Card text:</label><br />
                        <?= $this->textarea('text', ['placeholder' => 'Card text', 'height' => 150]) ?>
                    </div>
                    <div class="mb-20">
                        <label>Button:</label><br />
                        <?= $this->link('link', ['class' => 'btn btn-primary btn-lg']) ?>
                    </div>
                    <div class="mb-20">
                        <label>Card has border:</label><br />
                        <?= $this->checkbox('hasBorder') ?> has border
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
} else {
    $data = [];
    while ($block->loop()) {
        $tagBlock = $this->block('cardTagBlock'.$block->getCurrent(), ['default' => 1]);

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
            'content' => $this->textarea('text')->getData(),
            'image' => $image->getThumbnail('galleryLightbox') !== '' ? \Pimcore\Tool::getHostUrl() . $image->getThumbnail('galleryLightbox')->getPath() : null,
            'link' => $this->link('link', ['class' => 'btn btn-default'])->getData(),
            'hasBorder' => $this->checkbox('hasBorder')->getData(),
            'subline' => $this->input('subtitle')->getData(),
            'tags' => $tags,
        ];
    }

    if ($block->getCount() <= 1) {
        $this->slots()->components[] = [
            'type' => 'CmsCard',
            'data' => ($data[0] ? $data[0] : $data)
        ];
    } else {
        $this->slots()->components[] = [
            'type' => 'CmsCardList',
            'variant' => $this->select('variant')->getData(),
            'items' => $data
        ];
    }
} ?>
