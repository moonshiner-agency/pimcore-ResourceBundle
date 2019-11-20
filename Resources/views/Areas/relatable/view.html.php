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
    $relations = $this->relations('objects');

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
        }, $relations->getElements()),
        'type' => 'Cms' . $shortClassName . 'List'
    ];
} else {
    ?>

    <section>
        <div class="cms-component-type">Data Objects</div>
        <div class="row">
            <div class="col-md-6">
                <?= $this->relations('objects', [
                    'types' => ['object'],
                    'subtypes' => [
                        'object' => ['object']
                    ]
                ]); ?>
            </div>
        </div>
    </section>

<?php
} ?>