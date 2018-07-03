<?php
namespace Raw\Pager\Extension\Form\Type;

use Raw\Pager\Extension\Form\Type\PagerFormMetaType;
use Raw\Pager\Extension\Form\Type\PageSizeType;
use Raw\Pager\Pager;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\VarDumper\VarDumper;

class PagerType extends AbstractType
{
    use ContainerAwareTrait;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    public function setContainer(ContainerInterface $container)
    {
        $this->router = $container->get('router');
        $this->requestStack = $container->get('request_stack');
        $this->formFactory = $container->get('form.factory');
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        if(empty($view->vars['pager_id'])) {
            $view->vars['pager_id'] = uniqid('pager_', true);
        }

        /** @var Pager $pager */
        $pager = $form->getData();

        $metaForm = $this->formFactory
            ->createNamedBuilder('_raw_pager_form_meta', PagerFormMetaType::class)
            ->getForm()->setData([
                'namespace' => $pager->getNamespace(),
                'totalCount' => $pager->getTotalCount()
            ]);

        $view->vars['meta_form'] = $metaForm->createView();
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function(FormEvent $event){
            /** @var Pager $pager */
            $pager = $event->getData();
            $form = $event->getForm();

            if($pager === null) {
                return;
            }

            $form->add('page', IntegerType::class, [
                'property_path' => 'currentPage',
                'attr' => [
                    'min' => 1,
                    'max' => $pager->getAmountOfPages(),
                ],
                'disabled' => $pager->getAmountOfPages() === 1,
            ]);

            $form->add('pageSize', PageSizeType::class);
        });


    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'csrf_protection' => false,
            'data_class' => Pager::class,
        ]);
    }
}