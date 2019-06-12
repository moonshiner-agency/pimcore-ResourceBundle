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

    if (!$this->editmode) {
        $data = [];
        for ($t = 0; $t < $columnCount; $t++) {
            $image = $this->image('image'.$t);

            $data[] = [
                'title' => $this->input('title_' . $t)->getData(),
                'image' => $image->getThumbnail('galleryLightbox') !== '' ? (\Pimcore\Tool::getHostUrl() . $image->getThumbnail('galleryLightbox')->getPath()) : null,
                'text' => $this->textarea('text_' . $t)->getData(),
                'isSmall' => $this->checkbox('isSmall_' . $t)->isChecked(),
                'tag' => [
                    'text' => $this->input('tag_text_' . $t)->getData(),
                    'icon' => $this->select('tag_icon_' . $t)->getData(),
                ]
            ];
        }
        $this->slots()->components[] = ['type' => 'icon-teaser-row', 'data' => $data];
    } else {
        ?>
    <section class="area-icon-teaser-row">
        <div class="cms-component-type">icon-teaser</div>
        <div class="row">
            <div class="col-sm-3">
                Number of items:
                <?= $this->input('columnCount', ['placeholder' => '3']) ?>
            </div>
        </div>
        <div class="row">
        <?php for ($t = 0; $t < $columnCount; $t++) {
            ?>
            <div class="col-sm-3">
                <?= $this->image('image'.$t, [
                    'thumbnail' => 'galleryLightbox'
                ]); ?>

                <h3 class="title"><?= $this->input('title_' . $t, ['placeholder' => 'Title']) ?></h3>
                <p>
                    <?= $this->textarea('text_' . $t, ['placeholder' => 'Text']) ?>
                </p>

                <p>
                    <strong>Tag:</strong><br />
                    <?= $this->input('tag_text_' . $t, ['placeholder' => 'Tag title']) ?>
                    <?= $this->select('tag_icon_' . $t, [
                        'store' => ['car', 'pinLocation', 'people', 'area'],
                        'width' => 150
                    ]); ?>
                </p>

                <p>
                    <strong>Größe:</strong><br />
                    <?= $this->checkbox('isSmall_' . $t) ?> klein
                </p>

                <?php /*
                <div>
                    Link: <?= $this->link('link_' . $t, ['class' => 'btn btn-default']) ?>
                </div>
                */ ?>

                <?php
                    // link?
                    // linktext?
                    // size: default, small
                    // tag title
                    // tag icon: selectbox
                ?>

            </div>
            <?php
        } ?>
        </div>
    </section>
<?php
    } ?>
