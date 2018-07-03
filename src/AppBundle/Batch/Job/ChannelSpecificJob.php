<?php
namespace AppBundle\Batch\Job;

use AppBundle\Entity\Channel;
use Raw\Component\Batch\Job\Job;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;

class ChannelSpecificJob extends Job
{
    public function buildForm(FormBuilderInterface $builder)
    {
        $builder->add('channel', EntityType::class, [
            'class' => Channel::class,
            'choice_label' => 'name',
            'return_identifier' => true,
        ]);
    }
}