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

$id = 'areaBlock';
?>


<?php if ($this->editmode) {
    ?>
    <section>
        <div class="cms-component-type">Wrapper</div>
        <?= $this->select('theme', [
            'reload' => true,
            'store' => [
                ['primary', 'Primary'],
                ['secondary', 'Secondary']
            ]
        ]); ?>
        <?= $this->checkbox("fullWidth", ["label" => "Full Width?"]); ?>

        <?= $this->areablock($id, [
            'allowed' => [
                'gallery-carousel',
                'wysiwyg',
                'highlight',
                'relatable',
                'hero',
                'image',
                'horizontal-line',
                'icon-teaser-row',
                'productteaser',
                'card',
                'section-title',
                'testimonial',
                'wrapper',
                'directions',
            ]
        ]) ?>
    </section>
<?php
} else {
    $areaBlock = $this->areablock($id, [
        "manual" => true,
        'allowed' => [
            'wrapper',
            'gallery-carousel',
            'wysiwyg',
            'highlight',
            'relatable',
            'hero',
            'image',
            'horizontal-line',
            'icon-teaser-row',
            'contact-card',
            'productteaser',
            'card',
            'section-title',
            'testimonial',
            'wrapper',
            'directions',
        ]
    ])->start();

    $componentsCopy = $this->slots()->components;

    $this->slots()->components = [];
    while ($areaBlock->loop()) {
        $areaBlock->blockConstruct();
        $areaBlock->blockStart();
        $areaBlock->content();
        $areaBlock->blockEnd();
        $areaBlock->blockDestruct();
    }

    $areaBlock->end();

    $elements = $this->slots()->components;


    $this->slots()->components = $componentsCopy;

    $this->slots()->components[] = [
        'type' => 'wrapper',
        'isFullWidth' => $this->checkbox("fullWidth")->isChecked(),
        'theme' => $this->select('theme')->getData(),
        'data' => $elements
    ];
} ?>
