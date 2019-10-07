<?php

namespace Moonshiner\BrigthenBundle\Form\Transformer;

use Limenius\Liform\Transformer\AbstractTransformer;
use Symfony\Component\Form\FormInterface;

class FileTransformer extends AbstractTransformer
{
    /**
     * {@inheritdoc}
     */
    public function transform(FormInterface $form, array $extensions = [], $widget = null)
    {
        $schema = [];
        $schema = $this->addCommonSpecs($form, $schema, $extensions, $widget);
        $schema['widget'] = 'file';
        $schema['type'] = 'string';

        return $schema;
    }
}
