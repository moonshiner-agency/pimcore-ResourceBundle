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

if ($this->editmode) { ?>
    <section class="col-md-20 mb-20">
        <div class="cms-component-type">Gallery grid</div>

        <div class="mb-20">
            <label class="text-info">Title:</label><br />
            <?= $this->input('title', ['placeholder' => 'Title']) ?>
        </div>

        <div class="mb-20">
            <label class="text-info">Text:</label><br />
            <?= $this->textarea('text', ['placeholder' => 'Text', 'height' => 150]) ?>
        </div>

        <div class="mb-20">
            <div class="mb-20">
                <label class="text-info">Image:</label><br />
                <div class="col-md-8"></div>
                <?= $this->image('image1'); ?>
                <label class="text-info">Button:</label><br />
                <?= $this->link('link1', ['class' => 'btn-info btn-link']) ?>
            </div>
        </div>

        <div class="mb-20">
            <div class="mb-20">
                <label class="text-info">Image:</label><br />
                <div class="col-md-8"></div>
                <?= $this->image('image2'); ?>

                <label class="text-info">Button:</label><br />
                <?= $this->link('link2', ['class' => 'btn-info btn-link']) ?>
            </div>
        </div>

        <div class="mb-20">
            <div class="mb-20">
                <label class="text-info">Image:</label><br />
                <div class="col-md-8"></div>
                <?= $this->image('image3'); ?>

                <label class="text-info">Button:</label><br />
                <?= $this->link('link3', ['class' => 'btn-info btn-link']) ?>
            </div>
        </div>

        <div class="mb-20">
            <div class="mb-20">
                <label class="text-info">Image:</label><br />
                <div class="col-md-8"></div>
                <?= $this->image('image4'); ?>

                <label class="text-info">Button:</label><br />
                <?= $this->link('link4', ['class' => 'btn-info btn-link']) ?>
            </div>
        </div>

        <style>
            h3.noMarginTop {
                margin-top: 0;
            }
        </style>
    </section>

<?php
} else {
    $data =
            [
                [
                    'link' => (new LinkResource($this->link('link1')))->toArray(),
                    'image' => $this->image('image1')->getImage() ? (new ImageResource($this->image('image1')->getImage()))->toArray() : null
                ],
                [
                    'link' => (new LinkResource($this->link('link2')))->toArray(),
                    'image' => $this->image('image2')->getImage() ? (new ImageResource($this->image('image2')->getImage()))->toArray() : null
                ],
                [
                    'link' => (new LinkResource($this->link('link3')))->toArray(),
                    'image' => $this->image('image3')->getImage() ? (new ImageResource($this->image('image3')->getImage()))->toArray() : null
                ],
                [
                    'link' => (new LinkResource($this->link('link4')))->toArray(),
                    'image' => $this->image('image4')->getImage() ? (new ImageResource($this->image('image4')->getImage()))->toArray() : null
                ],
        ];

    $this->slots()->components[] = [
            'title' =>  $this->input('title')->getData(),
            'text' =>  $this->textarea('text')->getData(),
            'type' => 'CmsGalleryGrid',
            'items' => $data
        ];
} ?>