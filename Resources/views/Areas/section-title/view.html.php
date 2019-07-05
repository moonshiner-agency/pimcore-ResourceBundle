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
        <div class="cms-component-type">Section Title</div>
        <div class="row">
            <div class="col-md-6 col-md-offset-3 text-center">
                <h1><?= $this->input('title', ['placeholder' => 'Title']) ?></h1>
                <h4><?= $this->input('subtitle', ['placeholder' => 'Subtitle']) ?></h4>
                <p><?= $this->textarea('description', ['placeholder' => 'Description']) ?></p>
            </div>
        </div>
    </section>
    <?php
} else {
        $this->slots()->components[] = [
            'type' => 'CmsSectionHeader',
            'title' => $this->input('title')->getData(),
            'subtitle' => $this->input('subtitle')->getData(),
            'description' => $this->textarea('description')->getData(),
        ];
    }
?>

