<?php
namespace Raw\Filter\Type;

use Raw\Filter\FilterType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Raw\Filter\Expr\Comparison as Op;

class IntegerFilterType extends FilterType
{
    public function validate($operator, array $data, array $options)
    {

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'input_type' => 'number',
            'operators' => [Op::EQ, Op::GT, Op::GTE, Op::LT, Op::LTE, 'BETWEEN'],
            'choices' => [],
        ]);
    }
}