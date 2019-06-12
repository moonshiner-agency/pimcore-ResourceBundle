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
        $test = $this->relations('objectPaths');
        $this->slots()->components[] = ['type' => 'relatable', 'data' => array_map(function ($item) {
            return [
                'id' => $item->getId(),
                'name' => $item->getName(),
                'summary' => $item->getSummary(),
                'text' => $item->getText(),
                'salary' => $item->getSalary()
            ];
        }, $test->getElements())];
    } else {
        ?>

    <?= $this->relations('objectPaths'); ?>

        <?php
    }?>



