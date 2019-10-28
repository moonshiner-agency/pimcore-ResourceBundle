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
use Moonshiner\BrigthenBundle\JsonResources\ImageResource;
use Moonshiner\BrigthenBundle\Services\Service\SystemSettings;

/**
 * @var \Pimcore\Templating\PhpEngine $this
 * @var \Pimcore\Templating\PhpEngine $view
 * @var \Pimcore\Templating\GlobalVariables $app
 */
?>

<?php if ($this->editmode) {
    ?>
    <section>
        <div class="cms-component-type">Video</div>
        <div class="row">
            <div class="col-md-6">
                <div class="mb-20">
                    <label class="text-info">Video:</label><br />
                    <?= $this->video('video', [
                        'width' => 700,
                        'height' => 400
                    ]); ?>
                </div>
            </div>
        </div>
    </section>
    <?php
} else {
                        $video = $this->video('video');
                        $this->slots()->components[] = [
            'type' => 'CmsVideo',
            'video' => [
                'type' => $video->getVideoType(),
                'title' => $video->getTitle(),
                'description' => $video->getDescription(),
                'asset' => $video->getVideoAsset() ? SystemSettings::getHostUrl(). $video->getVideoAsset() : null,
                'posterAsset' => $video->getPosterAsset() ? (new ImageResource($video->getPosterAsset()))->toArray() : null,
                'id' => $video->getData()['id'],
            ]
        ];
                    }
?>
