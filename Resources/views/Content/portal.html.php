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
$this->extend('layout.html.php');

?>

    <?= $this->areablock(
        'content',
        [
            'allowed' => [
                'api',
                'wrapper',
                'video',
                'gallery-carousel',
                'gallery-grid',
                'gallery-single-images',
                'gallery-social-media',
                'form',
                'wysiwyg',
                'highlight',
                'contact-card',
                'relatable',
                'relatable-filtered',
                'hero',
                'image',
                'horizontal-line',
                'icon-teaser-row',
                'productteaser',
                'card',
                'section-title',
                'testimonial',
                'directions',
            ]
        ]
    ) ?>
