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

$columnCount = (int) $this->input('columnCount')->getData();
if ($columnCount == 0) {
    $columnCount = 3;
}

 if (! $this->editmode) {
     $data = [];
     for ($i = 0; $i < $columnCount; $i++) {
         $data[] = [
             'headline' => $this->input('headline_' . $i)->getData(),
             'text' => $this->textarea('text_' . $i)->getData(),
             'name' => $this->input('name_' . $i)->getData(),
             'origin' => $this->input('origin_' . $i)->getData(),
         ];
     }
     $this->slots()->components[] = ['type' => 'testimonial', 'data' => $data];
 } else { ?>
    <section>
        <div class="cms-component-type">testimonial</div>
        <div class="row">
            <div class="col-sm-3">
                Number of items:
                <?= $this->input('columnCount', ['placeholder' => '3']) ?>
            </div>
        </div>
        <div class="row">
            <?php for ($i = 0; $i < $columnCount; $i++) { ?>
                <div class="col-sm-4 mb-20">
                    <h4>
                        <?= $this->input('headline_'.$i, ['placeholder' => 'Headline']) ?>
                    </h4>
                    <p>
                        <?= $this->textarea('text_'.$i, ['placeholder' => 'Testimonial text']) ?>
                    </p>
                    <p><?= $this->input('name_'.$i, ['placeholder' => 'Name']) ?></p>
                    <strong><?= $this->input('origin_'.$i, ['placeholder' => 'Origin']) ?></strong>
                </div>
            <?php } ?>
        </div>
    </section>
    <?php
 }
?>

