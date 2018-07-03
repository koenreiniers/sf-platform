<?php
namespace Raw\Filter\Type;

use Raw\Filter\FilterType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Raw\Filter\Expr\Comparison as Op;
use Symfony\Component\VarDumper\VarDumper;

class DateTimeFilterType extends FilterType
{
    public function validate($operator, array $data, array $options)
    {

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'input_type' => 'datetime-local',
            'operators' => [Op::EQ, Op::GT, Op::GTE, Op::LT, Op::LTE, 'BETWEEN'],
            'choices' => [],
        ]);
    }
}