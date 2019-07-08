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
        $this->headLink()->appendStylesheet('/static/bootstrap/css/bootstrap.css');
    $this->headLink()->appendStylesheet('/static/bootstrap/css/bootstrap.css');
    $this->headLink()->appendStylesheet('/static/css/global.css');
    $this->headLink()->appendStylesheet('/static/lib/video-js/video-js.min.css', 'screen');
    $this->headLink()->appendStylesheet('/static/lib/magnific/magnific.css', 'screen');
    $this->headLink()->appendStylesheet('/static/css/print.css', 'print');
    if ($this->editmode) {
        $this->headLink()->appendStylesheet('/static/css/editmode.css', 'screen');
    } ?>

        <?= $this->headLink(); ?>

    </head>

    <body>
        <style>
            section {
                border: 1px solid black;
                padding: 60px 20px 40px 20px;
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

            .mb-10 {
                margin-bottom: 10px;
            }
            .mb-20 {
                margin-bottom: 20px;
            }
            .pimcore_tag_textarea {
                padding: 5px;
            }
            .pimcore_tag_input {
                padding: 5px;
            }
        </style>

<?php
        $this->slots()->output('_content'); ?>
        <?php
        // include a document-snippet - in this case the footer document
        // echo $this->inc('/' . $this->language . '/shared/includes/footer');
        $this->headScript()->prependFile('/static/bootstrap/js/bootstrap.js');
    $this->headScript()->prependFile('/static/js/jquery-1.11.0.min.js');
    $this->headScript()->appendFile('/static/lib/magnific/magnific.js');
    $this->headScript()->appendFile('/static/lib/video-js/video.js');
    $this->headScript()->appendFile('/static/js/srcset-polyfill.min.js');
    // global scripts, we use the view helper here to have the cache buster functionality
    echo $this->headScript(); ?>

<script>
    // main menu
    $(".navbar-wrapper ul.nav>li>ul").each(function () {
        var li = $(this).parent();
        var a = $("a.main", li);

        $(this).addClass("dropdown-menu");
        li.addClass("dropdown");
        a.addClass("dropdown-toggle");
        li.on("mouseenter", function () {
            $("ul", $(this)).show();
        });
        li.on("mouseleave", function () {
            $("ul", $(this)).hide();
        });
    });

    // side menu
    $(".bs-sidenav ul").each(function () {
        $(this).addClass("nav");
    });

    // gallery carousel: do not auto-start
    $('.gallery').carousel('pause');

    // tabbed slider text
    var clickEvent = false;
    $('.tabbed-slider').on('click', '.nav a', function () {
        clickEvent = true;
        $('.nav li').removeClass('active');
        $(this).parent().addClass('active');
    }).on('slid.bs.carousel', function (e) {
        if (!clickEvent) {
            var count = $('.nav').children().length - 1;
            var current = $('.nav li.active');
            current.removeClass('active').next().addClass('active');
            var id = parseInt(current.data('slide-to'));
            if (count == id) {
                $('.nav li').first().addClass('active');
            }
        }
        clickEvent = false;
    });

    $("#portalHeader img, #portalHeader .item, #portalHeader").height($(window).height());
</script>
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
