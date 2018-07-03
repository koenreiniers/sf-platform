<?php
namespace Raw\Pager\Extension\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;

class PagerFormMetaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('namespace', HiddenType::class);
        $builder->add('totalCount', HiddenType::class);
    }
}