<?php

namespace Moonshiner\BrigthenBundle\Form\Transformer;

use Limenius\Liform\Exception\TransformerException;
use Limenius\Liform\ResolverInterface;
use Limenius\Liform\Transformer\AbstractTransformer;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormTypeGuesserInterface;
use Symfony\Component\Translation\TranslatorInterface;

class CollectionTransformer extends AbstractTransformer
{
    /**
     * @var ResolverInterface
     */
    protected $resolver;

    /**
     * @param TranslatorInterface           $translator
     * @param FormTypeGuesserInterface|null $validatorGuesser
     * @param ResolverInterface             $resolver
     */
    public function __construct(
        TranslatorInterface $translator,
        FormTypeGuesserInterface $validatorGuesser = null,
        ResolverInterface $resolver
    ) {
        parent::__construct($translator, $validatorGuesser);
        $this->resolver = $resolver;
    }

    /**
     * {@inheritdoc}
     */
    public function transform(FormInterface $form, array $extensions = [], $widget = null)
    {
        $children = [];

        foreach ($form->all() as $name => $field) {
            $transformerData = $this->resolver->resolve($field);
            $transformedChild = $transformerData['transformer']->transform($field, $extensions, $transformerData['widget']);
            $children[] = $transformedChild;

            if ($transformerData['transformer']->isRequired($field)) {
                $required[] = $field->getName();
            }
        }

        if (empty($children)) {
            $entryType = $form->getConfig()->getAttribute('prototype');

            if (!$entryType) {
                throw new TransformerException('Liform cannot infer the json-schema representation of a an empty Collection or array-like type without the option "allow_add" (to check the proptotype). Evaluating "'.$form->getName().'"');
            }

            $transformerData = $this->resolver->resolve($entryType);
            $children[] = $transformerData['transformer']->transform($entryType, $extensions, $transformerData['widget']);
            $children[0]['title'] = 'prototype';
        }

        $schema = [
            'widget' => 'collection',
            'type' => 'array',
            'title' => $form->getConfig()->getOption('label'),
            'items' => $children[0],
        ];

        $schema = $this->addCommonSpecs($form, $schema, $extensions, $widget);

        if (isset($schema['attr']) && isset($schema['attr']['class'])) {
            if (strpos($schema['attr']['class'], 'formbuilder-container-fieldset') !== false) {
                $schema['widget'] = 'fieldset';
            } elseif (strpos($schema['attr']['class'], 'formbuilder-container-repeater') !== false) {
                $schema['widget'] = 'repeater';
            }
        }

        return $schema;
    }
}
