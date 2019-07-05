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

<?php $block = $this->block('testimonialblock', ['default' => '1']); ?>

<?php
 if (! $this->editmode) {
     $data = [];
     while ($block->loop()) {
         $data[] = [
             'title' => $this->input('headline')->getData(),
             'text' => $this->textarea('text')->getData(),
             'name' => $this->input('name')->getData(),
             'origin' => $this->input('origin')->getData(),
         ];
     }

     if ($block->getCount() <= 1) {
         $this->slots()->components[] = [
             'type' => 'CmsQuote',
             'data' => ($data[0] ? $data[0] : $data)
         ];
     } else {
         $this->slots()->components[] = [
             'type' => 'CmsQuoteList',
             'items' => $data
         ];
     }
 } else { ?>
    <section>
        <div class="cms-component-type">Testimonial</div>
        <div class="row">
        <div class="mb-20">
            <?php while ($block->loop()) { ?>
                <div class="col-sm-4 mb-20">
                    <div class="mb-20">
                        <label>Headline:</label><br />
                        <h3 class="noMarginTop">
                            <?= $this->input('headline', ['placeholder' => 'Headline']) ?>
                        </h3>
                    </div>
                    <div class="mb-20">
                        <label>Text:</label><br />
                        <?= $this->textarea('text', ['placeholder' => 'Testimonial text', 'height' => 100]) ?>
                    </div>
                    <div class="mb-20">
                        <label>Name:</label><br />
                        <?= $this->input('name', ['placeholder' => 'Name']) ?>
                    </div>
                    <div class="mb-20">
                        <label>Origin:</label><br />
                        <?= $this->input('origin', ['placeholder' => 'Origin']) ?>
                    </div>
                </div>
            <?php } ?>
        </div>
    </section>

    <style>
        h3.noMarginTop {
            margin-top: 0;
        }
    </style>
    <?php
 }
?>

