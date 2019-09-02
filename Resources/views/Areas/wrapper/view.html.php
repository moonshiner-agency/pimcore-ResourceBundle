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
                ['secondary', 'Secondary'],
                ['highlighted', 'Highlighted'],
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
        <div class="wrapper-subsection">
        <?= $this->areablock($id) ?>
    </div>
</section>
<style>
    .wrapper-subsection .pimcore_tag_areablock {
        display: grid;
        grid-gap: 5px;
        grid-template-columns: 50% 50%;
    }
    .wrapper-subsection .pimcore_tag_areablock .pimcore_area_entry section {
        height: 100%;
        margin: 0;
    }
</style>
<?php
} else {
            $areaBlock = $this->areablock($id, [
                'manual' => true,
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
