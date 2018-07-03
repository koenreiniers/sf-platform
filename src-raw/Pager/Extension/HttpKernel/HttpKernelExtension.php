<?php
namespace Raw\Pager\Extension\HttpKernel;

use Raw\Bundle\PagerBundle\Form\Type\PagerType;
use Raw\Pager\Event\BuildViewEvent;
use Raw\Pager\Event\HandleRequestEvent;
use Raw\Pager\Pager;
use Raw\Pager\PagerBuilder;
use Raw\Pager\PagerEvents;
use Raw\Pager\PagerExtension;
use Raw\Pager\PagerView;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\VarDumper\VarDumper;

class HttpKernelExtension extends PagerExtension
{

    public function handleRequest(Pager $pager, $request, array $options)
    {

    }

    public function buildPager(PagerBuilder $builder, array $options)
    {

    }

    public function buildView(PagerView $view, Pager $pager, array $options)
    {

    }
}