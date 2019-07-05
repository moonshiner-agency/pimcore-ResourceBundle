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
    <section class="col-md-8 mb-20">
        <div class="cms-component-type">Hero</div>
        <div><?= $this->image('image') ?></div>
        <h1><?= $this->input('title', ['placeholder' => 'Title']) ?></h1>
        <p><?= $this->input('subline', ['placeholder' => 'Subline']) ?></p>
        <p><?= $this->wysiwyg('text', ['placeholder' => 'Text']) ?></p>
        Link: <?= $this->link('link', ['class' => 'btn btn-primary']) ?>
    </section>
    <?php
} else {
        $image = $this->image('image');
        $data = [];

        $this->slots()->components[] = [
            'type' => 'CmsHero',
            'title' => $this->input('title')->getData(),
            'subline' => $this->input('subline')->getData(),
            'image' => $image->getThumbnail('galleryLightbox') !== '' ? \Pimcore\Tool::getHostUrl() . $image->getThumbnail('galleryLightbox')->getPath() : null,
            'text' => $this->wysiwyg('text')->getData(),
            'link' => $this->link('link', ['class' => 'btn btn-default'])->getData()
        ];
    }?>





