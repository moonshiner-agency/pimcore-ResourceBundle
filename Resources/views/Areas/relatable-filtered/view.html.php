<?php

use Pimcore\Model\DataObject\AbstractObject;

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
    $relation = $this->relation('object');
    $maxEntries = $this->numeric('maxEntries')->getData();
    $order = $this->select('order')->getData();

    $list = new \Pimcore\Model\DataObject\Listing();
    $list->setUnpublished(false);
    $list->setCondition('o_parentId = ?', [$relation->getElement()->getId()]);

    if ($order === 'most-recently-created') {
        $list->setOrderKey('o_creationDate');
    } elseif ($order === 'most-recently-updated') {
        $list->setOrderKey('o_modificationDate');
    } else {
        $list->setOrderKey("RAND()", false);
    }
    $list->setOrder('desc');

    $list->setObjectTypes([AbstractObject::OBJECT_TYPE_OBJECT]);
    $list->setLimit((int) $maxEntries);
    $children = $list->load();

    if (empty($children)) {
        return;
    }

    $shortClassName = '';
    $this->slots()->components[] = [
        'data' => array_map(function ($item) use (&$shortClassName) {
            $shortClassName = (new \ReflectionClass($item))->getShortName();
            $resourceClassName = '\AppBundle\JsonResources\\' . $shortClassName;
            if (class_exists($resourceClassName)) {
                $data = (new $resourceClassName($item))->toArray();
            } else {
                $data = [];
                foreach ($item->getClass()->getFieldDefinitions() as $fieldDefinition) {
                    $data[$fieldDefinition->getName()] = $item->getValueForFieldName($fieldDefinition->getName());
                }
            }

            return $data;
        }, $children),
        'type' => 'Cms' . $shortClassName . 'List'
    ];
} else {
    ?>

    <section>
        <div class="cms-component-type">Filtered data folder</div>
        <div class="row">
            <div class="col-md-20">
                <div class="mb-20">
                    <label>Folder:</label><br />
                    <?= $this->relation('object', [
                        'types' => ['object'],
                        'subtypes' => [
                            'object' => ['folder']
                        ]
                    ]); ?>
                </div>
                <div class="mb-20">
                    <label>Order:</label><br />
                    <?= $this->select('order', [
                        'store' => [
                            ['most-recently-created', 'Most recently created'],
                            ['most-recently-updated', 'Most recently updated'],
                            ['random', 'Random']
                        ],
                        'width' => '100%'
                    ]); ?>
                </div>
                <div class="mb-20">
                    <label>Maximum number of entries:</label><br />
                    <?= $this->numeric('maxEntries', [
                        'minValue' => 0,
                        'decimalPrecision' => 0,
                        'width' => '100%'
                    ]); ?>
                </div>
            </div>
        </div>
    </section>

<?php
} ?>