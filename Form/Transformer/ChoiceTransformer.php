<?php

namespace Moonshiner\BrigthenBundle\Form\Transformer;

use Limenius\Liform\Transformer\AbstractTransformer;
use Symfony\Component\Form\ChoiceList\View\ChoiceGroupView;
use Symfony\Component\Form\FormInterface;

/**
 * @author Nacho Martín <nacho@limenius.com>
 */
class ChoiceTransformer extends AbstractTransformer
{
    /**
     * {@inheritdoc}
     */
    public function transform(FormInterface $form, array $extensions = [], $widget = null)
    {
        $formView = $form->createView();

        $choices = [];
        $titles = [];
        foreach ($formView->vars['choices'] as $choiceView) {
            if ($choiceView instanceof ChoiceGroupView) {
                foreach ($choiceView->choices as $choiceItem) {
                    $choices[] = $choiceItem->value;
                    $titles[] = $this->translator->trans($choiceItem->label);
                }
            } else {
                $choices[] = $choiceView->value;
                $titles[] = $this->translator->trans($choiceView->label);
            }
        }

        if ($formView->vars['multiple']) {
            $schema = $this->transformMultiple($form, $choices, $titles);
        } else {
            $schema = $this->transformSingle($form, $choices, $titles);
        }

        $schema = $this->addCommonSpecs($form, $schema, $extensions, $widget);

        return $schema;
    }

    private function transformSingle(FormInterface $form, $choices, $titles)
    {
        $formView = $form->createView();

        $schema = [
            'widget' => 'select',
            'type' => 'string',
            'enum' => $choices,
            'enum_titles' => $titles,
        ];

        return $schema;
    }

    private function transformMultiple(FormInterface $form, $choices, $titles)
    {
        $formView = $form->createView();

        $schema = [
            'widget' => 'checkboxGroup',
            'type' => 'array',
            'items' => [
                'type' => 'string',
                'enum' => $choices,
                'enum_titles' => $titles,
                'minItems' => $this->isRequired($form) ? 1 : 0,
            ],
            'uniqueItems' => true,
        ];

        return $schema;
    }
}
