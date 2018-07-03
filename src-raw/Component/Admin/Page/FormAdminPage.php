<?php
namespace Raw\Component\Admin\Page;

use Raw\Component\Admin\Easy\Element\LayoutElement;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FormAdminPage extends AdminPage
{
    public function buildLayout(LayoutElement $layout, $entity, array $options)
    {

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(['form_type']);
        $resolver->setDefaults([
            'form_options' => [],
        ]);
    }

}