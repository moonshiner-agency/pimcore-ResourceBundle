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
use Pimcore\Model\Document;
use Pimcore\Model\Document\Page;

/** @var Document|Page $document */
$document = $this->document;

// output the collected meta-data
if (!$document) {
    // use "home" document as default if no document is present
    $document = Document::getById(1);
}
$this->slots()->components = [];
if ($document->getTitle()) {
    // use the manually set title if available
    $this->headTitle()->set($document->getTitle());
}

if ($document->getDescription()) {
    // use the manually set description if available
    $this->headMeta()->appendName('description', $document->getDescription());
}

$this->headTitle()->append('pimcore Demo');
$this->headTitle()->setSeparator(' : ');

if ($this->editmode) {
    ?>

    <!DOCTYPE html>
    <html lang="<?= $this->language; ?>">

    <head>
        <meta charset="utf-8">
        <link rel="icon" type="image/png" href="/pimcore/static/img/favicon/favicon-32x32.png" />

        <?php
        /** @var Document|Page $document */
        $document = $this->document;

        // output the collected meta-data
        if (!$document) {
            // use "home" document as default if no document is present
            $document = Document::getById(1);
        }

        if ($document->getTitle()) {
            // use the manually set title if available
            $this->headTitle()->set($document->getTitle());
        }

        if ($document->getDescription()) {
            // use the manually set description if available
            $this->headMeta()->appendName('description', $document->getDescription());
        }

        $this->headTitle()->append('pimcore Demo');
        $this->headTitle()->setSeparator(' : ');

        echo $this->headTitle();
        echo $this->headMeta();
        echo $this->placeholder('canonical'); ?>

        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <!-- Le styles -->
        <?php
        // we use the view helper here to have the cache buster functionality
        $this->headLink()->appendStylesheet('/static/bootstrap/css/bootstrap.css'); ?>

        <?= $this->headLink(); ?>

    </head>

    <body>
        <style>
            section {
                border: 1px solid black;
                padding: 40px 20px;
                margin: 50px 20px;
                position: relative;
            }

            .cms-component-type {
                position: absolute;
                background-color: #ececec;
                top: 0;
                left: 0;
                padding: 5px 5px;
                color: #9e9e9e;
            }
        </style>

        <?php $this->slots()->output('_content') ?>

        <?php
        // include a document-snippet - in this case the footer document
        echo $this->inc('/' . $this->language . '/shared/includes/footer');

        // global scripts, we use the view helper here to have the cache buster functionality
        echo $this->headScript(); ?>


    </body>

    </html>
<?php
} else {
    if ($document->getTitle()) {
        // use the manually set title if available
        $this->headTitle()->set($document->getTitle());
    }
    $this->slots()->output('_content');
    echo json_encode(['meta' => [
        'title' => $this->headTitle()->getRawContent(),
        'description' => $this->headMeta()->getItem('name', 'description'),
    ], 'data' => $this->slots()->components]);
}
