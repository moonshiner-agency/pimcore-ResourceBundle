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


<?php if ($this->editmode) {
    ?>
    <section>
        <div class="cms-component-type">card</div>
        <div class="row">
            <div class="col-md-3 mb-20">
                <div><?= $this->image('image') ?></div>
                <h2><?= $this->input('title', ['placeholder' => 'Headline']) ?></h2>
                <p><?= $this->textarea('text', ['placeholder' => 'Card text']) ?></p>
                <div>
                    <label>Button:</label>
                    <?= $this->link('link', ['class' => 'btn btn-primary btn-lg']) ?>
                </div>
            </div>
    </div>
    </section>
    <?php
} else {
        $image = $this->image('image');
        $data = [];

        $this->slots()->components[] = [
            'type' => 'card',
            'title' => $this->input('title')->getData(),
            'text' => $this->textarea('text')->getData(),
            'image' => $image->getThumbnail('galleryLightbox') !== '' ? \Pimcore\Tool::getHostUrl() . $image->getThumbnail('galleryLightbox')->getPath() : null,
            'link' => $this->link('link', ['class' => 'btn btn-default'])->getData()
        ];
    } ?>

