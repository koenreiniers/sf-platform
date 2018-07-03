<?php
namespace Raw\Filter\Type;

use Raw\Filter\Exception\FilterValidationException;
use Raw\Filter\FilterType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Raw\Filter\Expr\Comparison as Op;

class EnumFilterType extends FilterType
{
    public function validate($operator, array $data, array $options)
    {
        $choices = $options['choices'];
        if(!in_array($data[0], $choices)) {
            throw new FilterValidationException(sprintf('Invalid value "%s" for enum. Valid values are: %s', implode(',', $data), implode(', ', $choices)));
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'input_type' => 'select',
            'operators' => [Op::EQ, Op::NEQ],
            'choices' => [],
        ]);
    }
}