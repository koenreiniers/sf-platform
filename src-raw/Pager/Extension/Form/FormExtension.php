<?php
namespace Raw\Pager\Extension\Form;


use Raw\Pager\Event\BuildViewEvent;
use Raw\Pager\Event\HandleRequestEvent;
use Raw\Pager\Extension\Form\Type\PagerType;
use Raw\Pager\Pager;
use Raw\Pager\PagerBuilder;
use Raw\Pager\PagerEvents;
use Raw\Pager\PagerExtension;
use Raw\Pager\PagerView;
use Symfony\Component\Form\FormFactoryInterface;

class FormExtension extends PagerExtension
{
    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * FormExtension constructor.
     * @param FormFactoryInterface $formFactory
     */
    public function __construct(FormFactoryInterface $formFactory)
    {
        $this->formFactory = $formFactory;
    }

    public function handleRequest(Pager $pager, $request, array $options)
    {

    }

    public function buildPager(PagerBuilder $builder, array $options)
    {
        $form = $this->formFactory->createNamed($builder->getNamespace(), PagerType::class);

        $builder->addEventListener(PagerEvents::HANDLE_REQUEST, function(HandleRequestEvent $event) use($form) {

            $pager = $event->getPager();
            $request = $event->getRequest();
            $form->setData($pager);

            $form->handleRequest($request);
        });

        $builder->addEventListener(PagerEvents::BUILD_VIEW, function(BuildViewEvent $event) use($form) {
            $view = $event->getView();
            $view->vars['form'] = $form->createView();
        });

    }

    public function buildView(PagerView $view, Pager $pager, array $options)
    {

    }
}