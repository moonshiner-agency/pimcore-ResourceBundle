<?php

namespace Moonshiner\BrigthenBundle\Form;

use FormBuilderBundle\Configuration\Configuration;
use Pimcore\HttpKernel\BundleLocator\NotFoundException;
use Symfony\Component\Yaml\Yaml;

class FormConfigurationManager
{
    private $config = null;

    /**
     * Load configuration of the form.
     *
     * @param int $formID
     *
     * @throws Pimcore\HttpKernel\BundleLocator\NotFoundException
     *
     */
    public function load(int $formID)
    {
        $configFile = Configuration::STORE_PATH . '/main_' . $formID . '.yml';
        if (!file_exists($configFile)) {
            throw new NotFoundException('no form configuration with id ' . $formID . ' found.');
        }
        $this->config = Yaml::parseFile($configFile);
    }

    /**
     * Get configuration of form field as array
     *
     * @param string $fieldName
     *
     * @return array|null
     */
    public function getFieldConfiguration(string $fieldName): ?array
    {
        if ($this->config === null) {
            return null;
        }

        $fields = $this->config['fields'];

        foreach ($fields as $outerField) {
            if ($outerField['name'] === $fieldName) {
                return $outerField;
            }
            if (isset($outerField['fields'])) {
                foreach ($outerField['fields'] as $innerField) {
                    if ($innerField['name'] === $fieldName) {
                        return $innerField;
                    }
                }
            }
        }

        return null;
    }

    /**
     * Get display name of form field
     *
     * @param string $fieldName
     *
     * @return string|null
     */
    public function getDisplayNameOfField(string $fieldName): ?string
    {
        $fieldConfig = $this->getFieldConfiguration($fieldName);

        if (!isset($fieldConfig['display_name'])) {
            return null;
        }

        return $fieldConfig['display_name'];
    }

    /**
     * Get field type of form field
     *
     * @param string $fieldName
     *
     * @return string|null
     */
    public function getTypeOfField(string $fieldName): ?string
    {
        $fieldConfig = $this->getFieldConfiguration($fieldName);

        if (!isset($fieldConfig['display_name'])) {
            return null;
        }

        return $fieldConfig['display_name'];
    }
}
