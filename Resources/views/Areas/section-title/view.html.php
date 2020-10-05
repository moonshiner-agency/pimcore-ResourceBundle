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
            <div class="col-md-20">
                <div><label class="text-info">Title:</label><h1><?= $this->input('title', ['placeholder' => 'Title']) ?></h1></div><hr>
                <div><label class="text-info">Subtitle:</label><h4><?= $this->input('subtitle', ['placeholder' => 'Subtitle']) ?></h4></div><hr>
                <div><label class="text-info">Content:</label><p><?= $this->textarea('content', ['placeholder' => 'Content']) ?></p></div>
            </div>
        </div>
    </section>
    <?php
} else {
        $this->slots()->components[] = [
            'type' => 'CmsSectionHeader',
            'title' => $this->input('title')->getData(),
            'subtitle' => $this->input('subtitle')->getData(),
            'content' => $this->textarea('content')->getData(),
        ];
    }
?>
