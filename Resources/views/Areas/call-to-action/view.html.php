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
use Moonshiner\BrigthenBundle\JsonResources\ImageResource;
use Moonshiner\BrigthenBundle\JsonResources\LinkResource;

/**
 * @var \Pimcore\Templating\PhpEngine $this
 * @var \Pimcore\Templating\PhpEngine $view
 * @var \Pimcore\Templating\GlobalVariables $app
 */
?>

<?php if ($this->editmode) {
    ?>
    <section>
        <div class="cms-component-type">Call to action</div>
        <div class="row">
            <div class="col-md-20">
                <div class="mb-20">
                    <label class="text-info">Image:</label><br />
                    <?= $this->image('image'); ?>
                </div>
                <div class="mb-20">
                    <label class="text-info">Headline:</label><br />
                    <?= $this->input('headline', ['placeholder' => 'Headline']) ?>
                </div>
                <div class="mb-20">
                    <label class="text-info">Description:</label><br />
                    <?= $this->textarea('description', ['placeholder' => 'Description', 'height' => 100]) ?>
                </div>
                <div class="mb-20">
                    <label class="text-info">Link:</label><br />
                    <?= $this->link('link', ['class' => 'btn-info btn-link']) ?>
                </div>
            </div>
        </div>
    </section>
    <?php
} else {
        $this->slots()->components[] = [
            'type' => 'CmsCallToAction',
            'title' => $this->input('headline')->getData(),
            'description' => $this->textarea('description')->getData(),
            'image' => $this->image('image')->getImage() ? (new ImageResource($this->image('image')->getImage()))->toArray() : null,
            'link' => (new LinkResource($this->link('link')))->toArray(),
        ];
    }
?>
