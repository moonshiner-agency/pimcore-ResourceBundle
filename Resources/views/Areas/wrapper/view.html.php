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
        <br>
        <strong>Theme</strong>
        <br>
        <?= $this->select('theme', [
            'store' => [
                ['default', 'Default'],
                ['primary', 'Primary'],
                ['secondary', 'Secondary']
            ]
        ]); ?>
        <br>
        <strong>Layout</strong>
        <?= $this->select('layout', [
            'reload' => true,
            'store' => [
                ['one', 'Default'],
                ['2x2', '2x2'],
                ['1-2x2', '1 - 2 - 2'],
                ['2x2-1', '2 - 2 - 1']
            ]
        ]); ?>
        <br>
        <br>
         <strong>Width</strong><br>
        <?= $this->checkbox('fullWidth', ['label' => 'Full Width?']); ?>
        <div class="wrapper-subsection-<?= $this->select('layout')->getData() ?>">
        <?= $this->areablock($id, [
            'allowed' => [
                'gallery-carousel',
                'wysiwyg',
                'highlight',
                'video',
                'form',
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
        <style>
        .wrapper-subsection-2x2 .pimcore_tag_areablock {
                display: grid;
                grid-gap: 20px;
                grid-template-columns: 50% 50%;
            }
            .wrapper-subsection-2x2 .pimcore_tag_areablock .pimcore_area_entry section {
    height: 100%;
    margin: 0;
}

.wrapper-subsection-2x2-1 .pimcore_tag_areablock {
                grid-gap: 20px;
  display: grid;
  align-items: center;
  grid-template-rows: auto 1fr auto;  /* key rule */
  grid-template-columns: 50% 50%;
  grid-template-areas:
    'a b'
    'c c';

}
.wrapper-subsection-2x2-1 .pimcore_area_entry:first-of-type section{
    margin: 0; 
    height: 100%;

}
.wrapper-subsection-2x2-1 .pimcore_area_entry:first-of-type {
    grid-area: a;
    height: 100%; 
  }
  .wrapper-subsection-2x2-1 .pimcore_area_entry:nth-of-type(2) section{
    margin: 0; 
    height: 100%;
      }
  .wrapper-subsection-2x2-1 .pimcore_area_entry:nth-of-type(2) {
    grid-area: b;
    height: 100%; 
  }
  .wrapper-subsection-2x2-1 .pimcore_area_entry:nth-of-type(3) section{
    margin: 0; 
    height: 100%;
      }
  .wrapper-subsection-2x2-1 .pimcore_area_entry:nth-of-type(3) {
    margin: 0; 
    grid-area: c;
    height: 100%; 
  }
        </style>
        </div>
    </section>
<?php
} else {
            $areaBlock = $this->areablock($id, [
                'manual' => true,
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
                'isFullWidth' => $this->checkbox('fullWidth')->isChecked(),
                'theme' => $this->select('theme')->getData(),
                'layout' => $this->select('layout')->getData(),
                'data' => $elements
            ];
        } ?>
