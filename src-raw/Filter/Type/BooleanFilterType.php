<?php
namespace Raw\Filter\Type;

use Raw\Filter\Exception\FilterValidationException;
use Raw\Filter\FilterType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Raw\Filter\Expr\Comparison as Op;

class BooleanFilterType extends FilterType
{
    public function validate($operator, array $data, array $options)
    {

    }

    public function getParent()
    {
        return EnumFilterType::class;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'operators' => [Op::EQ],
            'choices' => [
                'Yes' => 1,
                'No' => 0,
            ],
        ]);
    }
}