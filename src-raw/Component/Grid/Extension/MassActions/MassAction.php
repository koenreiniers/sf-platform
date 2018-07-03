<?php
namespace Raw\Component\Grid\Extension\MassActions;

use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class MassAction
{
    /**
     * @param array $ids
     * @param array $records
     * @param array $options
     */
    abstract public function execute(array $ids, array $records, array $options);

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {

    }
}