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
        <div class="cms-component-type">directions</div>
        <div class="row">
            <div class="col-md-6">
                <p><?= $this->wysiwyg('text', ['placeholder' => 'Text']) ?></p>
            </div>
    </div>
    </section>
    <?php

} else {
    $image = $this->image('image');

    $this->slots()->components[] = [
        'type' => 'directions',
        'text' => $this->wysiwyg('text')->getData(),
    ];
} ?>

