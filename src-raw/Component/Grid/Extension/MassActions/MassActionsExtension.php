<?php
namespace Raw\Component\Grid\Extension\MassActions;

use Doctrine\ORM\EntityManager;
use Raw\Component\Batch\Job\Launcher\SymfonyLauncher;
use Raw\Bundle\BatchBundle\Entity\JobInstance;
use Raw\Bundle\BatchBundle\MassAction\BatchJobMassAction;
use Raw\Component\Grid\Event\GridEvent;
use Raw\Component\Grid\GridBuilder;
use Raw\Component\Grid\GridEvents;
use Raw\Component\Grid\GridView;
use Raw\Component\Grid\Mapping\GridMetadataBuilder;
use Raw\Component\Grid\Grid;
use Raw\Component\Grid\GridExtension;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\VarDumper\VarDumper;

class MassActionsExtension extends GridExtension
{
    use ContainerAwareTrait;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var SymfonyLauncher
     */
    private $jobLauncher;

    /**
     * @var MassActionRegistry
     */
    private $massActionRegistry;

    public function setContainer(ContainerInterface $container)
    {
        $this->router = $container->get('router');
        $this->entityManager = $container->get('doctrine.orm.default_entity_manager');
        $this->jobLauncher = $container->get('raw_batch.job_launcher');
        $this->massActionRegistry = $container->get('raw_grid.mass_action_registry');
        return $this;
    }

    private function executeMassAction($type, array $ids, array $records, array $options)
    {
        $massAction = $this->massActionRegistry->getMassAction($type);
        $resolver = new OptionsResolver();
        $massAction->configureOptions($resolver);
        $options = $resolver->resolve($options);
        $massAction->execute($ids, $records, $options);
    }

    public function build(GridBuilder $builder)
    {
        $builder->addEventListener(GridEvents::PRE_GET_DATA, function(GridEvent $event){

            $grid = $event->getGrid();

            if($grid->getParameter('mass_action') === null) {
                return;
            }

            $requestData = array_merge([
                'name' => null,
                'ids' => [],
                'records' => [],
            ], $grid->getParameter('mass_action', []));

            $actionName = $requestData['name'];

            $massActions = $grid->getProperty('mass_actions');
            $massAction = $massActions[$actionName];

            $this->executeMassAction($massAction['type'], $requestData['ids'], $requestData['records'], $massAction['options']);
        });
    }

    public function buildView(GridView $view, Grid $grid)
    {
        $massActions = [];
        $hiddenProperties = ['options'];//['job_instance', 'route', 'route_params'];
        foreach($grid->getProperty('mass_actions') as $name => $massAction) {
            foreach($hiddenProperties as $property) {
                unset($massAction[$property]);
            }
            $massActions[$name] = $massAction;
        }
        $view->vars['massActions'] = $massActions;
    }

    public function load(array $configs, GridMetadataBuilder $builder)
    {
        $massActions = $this->processConfiguration(new Configuration(), $configs);

        foreach($massActions as $name => $massAction) {
            $massAction['name'] = $name;
            $massAction['url'] = $this->router->generate('raw_grid.api.grid.mass_action', [
                'gridName' => $builder->getName(),
            ]);
            $massActions[$name] = $massAction;
        }

        $builder->setProperty('mass_actions', $massActions);
    }

}