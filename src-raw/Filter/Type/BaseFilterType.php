<?php
namespace Raw\Filter\Type;

use Raw\Filter\Exception\FilterValidationException;
use Raw\Filter\FilterType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BaseFilterType extends FilterType
{
    /**
     * @param string $operator
     * @param mixed $data
     * @param array $options
     *
     * @throws FilterValidationException
     */
    public function validate($operator, array $data, array $options)
    {
        if(!in_array($operator, $options['operators'])) {
            throw new FilterValidationException(sprintf('Invalid operator "%s", available: %s', $operator, implode(', ', $options['operators'])));
        }
    }

    public function getParent()
    {

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'field' => null,
            'input_type' => 'text',
            'label' => null,
            'type' => null,
            'operators' => [],
            'choices' => [],
            'class' => null,
            'choice_label' => null,
        ]);
    }
}