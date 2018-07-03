<?php
namespace Raw\Bundle\BatchBundle\Admin;

use Doctrine\ORM\EntityManager;
use Raw\Bundle\BatchBundle\Entity\JobExecution;
use Raw\Bundle\BatchBundle\Entity\JobInstance;
use Raw\Bundle\BatchBundle\Form\Type\JobInstanceType;
use Raw\Component\Admin\Admin;
use Raw\Component\Admin\BaseAdmin;
use Raw\Component\Admin\Easy\Actions;
use Raw\Component\Admin\Easy\Element\FormElement;
use Raw\Component\Admin\Easy\Element\GridElement;
use Raw\Component\Admin\Easy\Element\LayoutElement;
use Raw\Component\Admin\EasyAdmin;
use Raw\Component\Admin\Layout\Definition\Builder\ArrayNodeDefinition;
use Raw\Component\Admin\Layout\Definition\Builder\ContentNodeDefinition;
use Raw\Component\Admin\Layout\Definition\Builder\FormNodeDefinition;
use Raw\Component\Admin\SimpleAdmin;
use Raw\Component\Layout\Action\Action;
use Raw\Component\Layout\Action\ActionCollection;
use Raw\Component\Layout\Action\ActionCollectionBuilder;
use Symfony\Component\OptionsResolver\OptionsResolver;

class JobInstanceAdmin extends Admin
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * JobInstanceAdmin constructor.
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    protected function configure()
    {
        $this->setClassName(JobInstance::class);
    }

    public function buildListLayout(LayoutElement $layout, array $options)
    {
        $layout
            ->addGrid('job_instances_grid');
    }

    public function buildEditLayout(FormElement $layout, $entity, array $options)
    {
        $layout
            ->setFormType(JobInstanceType::class)
            ->addTabset()
                ->addTab('General')
                    ->addField('code')
                    ->addField('jobName')
                ->endTab()
                ->addTab('Cron')
                    ->addField('cronEnabled')
                    ->addField('cronExpression')
                ->endTab()
                ->addTab('Parameters')
                    ->addField('parameters')
                ->endTab()
            ->endTabset();
    }

    public function buildViewLayout(LayoutElement $layout, $entity, array $options)
    {
        $layout
            ->template('RawBatchBundle:JobInstance:view/content.html.twig', [
                'jobInstance' => $entity,
            ]);
    }

    public function configureActions(ActionCollectionBuilder $actions)
    {
        $actions->callbackAdd('launch', function (Action $action) {
            $action
                ->setType('post')
                ->setLabel('Launch')
                ->setUrl('#')
                ->setRoute('raw_batch.job_instance.launch')
                ->setRouteParametersResolver(function ($entity) {
                    return [
                        'id' => $entity->getId(),
                    ];
                });
        });
    }

    public function buildCreateLayout(FormElement $form, $entity, array $options)
    {
        $form
            ->setFormType(JobInstanceType::class)
            ->addTabset()
                ->addTab('General')
                    ->addField('code')
                    ->addField('jobName')
                ->endTab()
                ->addTab('Cron')
                    ->addField('cronEnabled')
                    ->addField('cronExpression')
                ->endTab()
            ->endTabset();
    }



    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'className' => JobInstance::class,
            'gridName' => 'nil',
        ]);
    }
}