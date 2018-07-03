<?php
namespace Raw\Filter;

use Raw\Filter\Exception\FilterValidationException;
use Raw\Filter\Type\BaseFilterType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FilterType
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

    }

    public function getParent()
    {
        return BaseFilterType::class;
    }

    public function configureOptions(OptionsResolver $resolver)
    {

    }
}