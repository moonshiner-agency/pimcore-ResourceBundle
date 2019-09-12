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
use Moonshiner\BrigthenBundle\JsonResources\LinkResource;
use Moonshiner\BrigthenBundle\JsonResources\TagResource;
use Moonshiner\BrigthenBundle\Services\Resource;

?>

<?php $block = $this->block('cardBlock', ['default' => 1]); ?>

<?php if ($this->editmode) {
    ?>
    <section class="col-md-8 mb-20">
        <div class="cms-component-type">Card</div>

            <?php if ($block->getCount() > 1) {
        ?>
                <div >
                    <div class="mb-20 alert alert-info">

                        <label>List options:</label><br />
                        <p>Scrolling behaviour:
                            <?= $this->select('variant', [
                                'store' => ['scroll', 'col2', 'col3'],
                                'width' => 100,
                                'default' => 'col2',
                            ]) ?>
                        </p>
                    </div>
                </div>
            <?php
    } ?>

            <?php while ($block->loop()) {
        ?>
                <div class="mb-20">
                    <div class="mb-20">
                        <label class="text-info">Image:</label><br />
                        <div class="col-md-8">
                            <?= $this->image('image'); ?>
                        </div>
                    </div>
                    <hr>
                    <div class="mb-20">
                        <label class="text-info">Headline:</label><br />
                        <h3 class="noMarginTop"><?= $this->input('title', ['placeholder' => 'Headline']) ?></h3>
                    </div>
                    <hr>
                    <div class="mb-20">
                        <label class="text-info">Subline:</label><br />
                        <?= $this->input('subtitle', ['placeholder' => 'Subline']) ?>
                    </div>
                    <hr>
                    <div class="mb-20">
                        <label class="text-info">Card text:</label><br />
                        <?= $this->textarea('text', ['placeholder' => 'Card text', 'height' => 150]) ?>
                    </div>
                    <hr>
                    <div class="mb-20">
                        <label class="text-info">Button:</label><br />
                        <?= $this->link('link', ['class' => 'btn-info btn-link']) ?>
                    </div>
                    <hr>
                    <div class="mb-20">
                        <label class="text-info">Card has border:</label>
                        <?= $this->checkbox('hasBorder') ?>
                        <br />
                    </div>
                    <hr>
                    <div class="mb-20">
                        <?= $this->relations('tag', [
                            'title' => 'Tags',
                            'types' => ['object'],
                            'subtypes' => [
                                'object' => ['object']
                            ],
                            'classes' => ['Tag'],
                        ]); ?>

                    </div>
                </div>
            <?php
    } ?>

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
            $data[] = [
                'title' => $this->input('title')->getData(),
                'content' => $this->textarea('text')->getData(),
                'link' => (new LinkResource($this->link('link')))->toArray(),
                'hasBorder' => $this->checkbox('hasBorder')->getData(),
                'subline' => $this->input('subtitle')->getData(),
                'tags' => TagResource::collection($this->relations('tag')->getElements(), Resource::NESTED),
                'image' => $this->image('image')->getImage() ? (new ImageResource($this->image('image')->getImage()))->toArray() : null
            ];
        }

        if ($block->getCount() <= 1) {
            $this->slots()->components[] =  array_merge([
                    'type' => 'CmsCard',
            ], isset($data[0]) ? $data[0] : []);
        } else {
            $this->slots()->components[] = [
                'type' => 'CmsCardList',
                'variant' => $this->select('variant')->getData() ? $this->select('variant')->getData() : 'col2',
                'items' => $data
            ];
        }
    } ?>