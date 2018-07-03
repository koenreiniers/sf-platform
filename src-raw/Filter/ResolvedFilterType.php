<?php
namespace Raw\Filter;

use Raw\Filter\Exception\FilterValidationException;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ResolvedFilterType
{
    /**
     * @var ResolvedFilterType|null
     */
    private $parent;

    /**
     * @var FilterType
     */
    private $inner;

    /**
     * @var OptionsResolver
     */
    private $optionsResolver;

    /**
     * ResolvedFilterType constructor.
     * @param FilterType $inner
     * @param null|ResolvedFilterType $parent
     */
    public function __construct(FilterType $inner, ResolvedFilterType $parent = null)
    {
        $this->inner = $inner;
        $this->parent = $parent;
    }

    public function getOptionsResolver()
    {
        if($this->optionsResolver === null) {
            if($this->parent !== null) {
                $this->optionsResolver = clone $this->parent->getOptionsResolver();
            } else{
                $this->optionsResolver = new OptionsResolver();
            }
            $this->inner->configureOptions($this->optionsResolver);
        }
        return $this->optionsResolver;
    }

    /**
     * @param string $operator
     * @param mixed $data
     * @param array $options
     *
     * @throws FilterValidationException
     */
    public function validate($operator, $data, array $options)
    {
        if($this->parent !== null) {
            $this->parent->validate($operator, $data, $options);
        }

        $this->inner->validate($operator, $data, $options);
    }
}