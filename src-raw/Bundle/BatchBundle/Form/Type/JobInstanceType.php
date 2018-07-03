<?php
namespace Raw\Bundle\BatchBundle\Form\Type;

use Raw\Component\Batch\Job\JobRegistry;
use Raw\Bundle\BatchBundle\Entity\JobInstance;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class JobInstanceType extends AbstractType
{
    /**
     * @var JobRegistry
     */
    private $jobRegistry;

    /**
     * JobInstanceType constructor.
     * @param JobRegistry $jobRegistry
     */
    public function __construct(JobRegistry $jobRegistry)
    {
        $this->jobRegistry = $jobRegistry;
    }


    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('code');

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function(FormEvent $event){
            if($event->getData() === null || $event->getData()->getId() === null) {
                return;
            }

            $event->getForm()->add('code', TextType::class, [
                'disabled' => true,
            ]);
            $event->getForm()->add('jobName', TextType::class, [
                'disabled' => true,
            ]);
        });

        $jobChoices = [];
        foreach($this->jobRegistry->getJobNames() as $jobName) {
            $jobChoices[$jobName] = $jobName;
        }
        $builder->add('jobName', ChoiceType::class, [
            'choices' => $jobChoices,
        ]);

        $builder->add('cronEnabled', CheckboxType::class, [
            'required' => false,
        ]);
        $builder->add('cronExpression', TextType::class, [
            'required' => false,
        ]);

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function(FormEvent $event){

            /** @var JobInstance $jobInstance */
            $jobInstance = $event->getData();

            if($jobInstance === null || empty($jobInstance->getJobName())) {
                return;
            }

            $form = $event->getForm();

            $job = $this->jobRegistry->getJob($jobInstance->getJobName());

            $parametersBuilder = $form->getConfig()->getFormFactory()->createNamedBuilder('parameters')->setAutoInitialize(false);

            $job->buildForm($parametersBuilder);

            $form->add($parametersBuilder->getForm());
        });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => JobInstance::class,
        ]);
    }
}