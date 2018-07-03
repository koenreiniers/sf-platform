<?php
namespace Raw\Filter\Type;

use Raw\Filter\FilterType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Raw\Filter\Expr\Comparison as Op;

class StringFilterType extends FilterType
{
    const CONTAINS = 'CONTAINS';
    const STARTSWITH = 'STARTSWITH';
    const ENDSWITH = 'ENDSWITH';

    public function validate($operator, array $data, array $options)
    {

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'operators' => [Op::EQ, Op::NEQ, self::CONTAINS, self::STARTSWITH, self::ENDSWITH],
            'choices' => [],
        ]);
    }
}