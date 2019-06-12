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
    if (!$this->editmode) {
        $image = $this->image('image');
        $this->slots()->components[] = ['type' => 'highlight', 'image' => $image->getThumbnail('galleryLightbox') !== '' ? \Pimcore\Tool::getHostUrl() . $image->getThumbnail('galleryLightbox')->getPath() : null, 'text' => $this->wysiwyg('content')->getData(), 'mode' => $this->select('mode', [
            'reload' => true,
            'store' => [
                ['left', 'Left wide'],
                ['right', 'Right wide']
            ]
        ])->getData(),  'theme' => $this->select('theme', [
            'reload' => true,
            'store' => [
                ['primary', 'Primary'],
                ['secondary', 'Secondary']
            ]
        ])->getData()];
    } else {
        ?>
<section class="area-image">



        <?= $this->image('image', [
            'thumbnail' => 'galleryLightbox'
        ]); ?>
        <?= $this->wysiwyg('content'); ?>

<?= $this->select('mode', [
    'reload' => true,
    'store' => [
        ['left', 'Left wide'],
        ['right', 'Right wide']
    ]
]); ?>
<?= $this->select('theme', [
    'reload' => true,
    'store' => [
        ['primary', 'Primary'],
        ['secondary', 'Secondary']
    ]
]); ?>
</section>
        <?php
    }?>



