<?php
namespace Raw\Component\Grid\Extension\Actions;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;
use Raw\Component\Batch\Job\Launcher\SymfonyLauncher;
use Raw\Bundle\BatchBundle\Entity\JobInstance;
use Raw\Component\Grid\GridView;
use Raw\Component\Grid\Mapping\GridMetadataBuilder;
use Raw\Filter\Context\Loader\ArrayLoader;
use Raw\Filter\Filter;
use Raw\Filter\FiltererFactory;
use Raw\Filter\Storage\InMemoryStorage;
use Raw\Component\Grid\Grid;
use Raw\Component\Grid\GridExtension;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\VarDumper\VarDumper;

class ActionsExtension extends GridExtension
{
    public function buildView(GridView $view, Grid $grid)
    {
        $view->vars['actions'] = $grid->getProperty('actions');
    }

    public function load(array $configs, GridMetadataBuilder $builder)
    {
        $actions = $this->processConfiguration(new Configuration(), $configs);
        $builder->setProperty('actions', $actions);
    }
}