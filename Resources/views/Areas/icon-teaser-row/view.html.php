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
use Moonshiner\BrigthenBundle\JsonResources\TagResource;

?>

<?php $block = $this->block('iconTeaserBlock', ['default' => 1]); ?>

<?php
    if (!$this->editmode) {
        $data = [];
        while ($block->loop()) {
            $tagBlock = $this->block('iconTeaserTagBlock'.$block->getCurrent(), ['default' => 1]);

            if ($tagBlock->getCount() > 0) {
                $tags = [];
                while ($tagBlock->loop()) {
                    $tag = $this->relation('tag');
                    if (!$tag->isEmpty()) {
                        $tags[] = (new TagResource($tag->getElement()))->toArray();
                    }
                }
            } else {
                $tags = null;
            }

            $data[] = [
                'title' => $this->input('title')->getData(),
                'content' => $this->textarea('text')->getData(),
                'number' => $this->input('number')->getData(),
                'isSmall' => $this->checkbox('isSmall')->isChecked(),
                'tags' => $tags,
                'image' => (new ImageResource($this->image('image')))->toArray(),
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

                        <?php $tagBlock = $this->block('iconTeaserTagBlock' . $block->getCurrent()); ?>
                        <?php while ($tagBlock->loop()) { ?>
                            <div class="mb-20">
                                <p>Tag <?= $tagBlock->getCurrent() + 1 ?>:</p>
                                <?= $this->relation('tag', [
                                    'types' => ['object'],
                                    'subtypes' => [
                                        'object' => ['object']
                                    ],
                                    'classes' => ['Tag'],
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
