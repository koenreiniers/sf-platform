<?php
namespace AppBundle\Admin\Channel;

use AppBundle\Form\Type\ChannelType;
use Raw\Component\Admin\Easy\Element\LayoutElement;
use Raw\Component\Admin\Page\AdminPage;
use Raw\Component\Admin\Page\FormAdminPage;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChannelFormPage extends AdminPage
{
    public function buildLayout(LayoutElement $layout, $entity, array $options)
    {
        $layout
            ->template('AppBundle:Channel:create/content.html.twig');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'form_type' => ChannelType::class,
        ]);
    }

    public function getParent()
    {
        return FormAdminPage::class;
    }
}