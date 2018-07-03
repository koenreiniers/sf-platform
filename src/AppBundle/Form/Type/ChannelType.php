<?php
namespace AppBundle\Form\Type;

use AppBundle\Entity\Channel;
use Platform\PlatformRegistry;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\VarDumper\VarDumper;

class ChannelType extends AbstractType
{
    /**
     * @var PlatformRegistry
     */
    private $platformRegistry;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * ChannelType constructor.
     * @param PlatformRegistry $platformRegistry
     */
    public function __construct(PlatformRegistry $platformRegistry, RouterInterface $router)
    {
        $this->platformRegistry = $platformRegistry;
        $this->router = $router;
    }


    public function addParameterFormNew(FormInterface $form, $platformName)
    {

        $formFactory = $form->getConfig()->getFormFactory();

        $parametersBuilder = $formFactory->createNamedBuilder('parameters', FormType::class, null, [
            'allow_extra_fields' => true,
        ]);

        $parametersBuilder->setAutoInitialize(false);

        if($platformName) {
            $adapter = $this->platformRegistry->getPlatformAdapter($platformName);
            $adapter->buildParameterForm($parametersBuilder);
        }

        $form->add($parametersBuilder->getForm());
    }

    public function addParameterForm(FormEvent $event)
    {
        /** @var Channel $channel */
        $channel = $event->getData();


        $form = $event->getForm();

        $platformName = null;
        if($channel !== null) {
            $platformName = $channel->getPlatformName();
        }

        $this->addParameterFormNew($form, $platformName);

    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        if(!$options['reloadable']) {
            return;
        }
        $attr = $view->vars['attr'];

        $attr = array_merge($attr, [
            'data-reloadable-form' => '',
            'data-reloadable-form-url' => $this->router->generate('app.channel.load_form', [], UrlGeneratorInterface::ABSOLUTE_URL),
        ]);

        $view->vars['attr'] = $attr;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', TextType::class);

        $choices = [];
        foreach($this->platformRegistry->getAdapterNames() as $adapterName) {
            $platformLabel = ucfirst($adapterName);
            $choices[$platformLabel] = $adapterName;
        }
        $builder->add('platformName', ChoiceType::class, [
            'label' => 'Platform',
            'placeholder' => 'Choose a platform',
            'choices' => $choices,
            'attr' => [
                'data-change' => 'reload',
            ]
        ]);

        $parametersBuilder = $builder->getFormFactory()->createNamedBuilder('parameters', FormType::class, null, [
            'allow_extra_fields' => true,
        ]);
        $builder->add($parametersBuilder);

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function(FormEvent $event){
            $channel = $event->getData();

            if($channel !== null && $channel->getId() !== null) {
                $event->getForm()->remove('platformName');
            }
            $this->addParameterForm($event);
        });

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function(FormEvent $event){
            /** @var array $channelData */
            $channelData = $event->getData();

            $form = $event->getForm();

            $platformName = isset($channelData['platformName']) ? $channelData['platformName'] : null;

            if($platformName === null) {
                return;
            }

            $this->addParameterFormNew($form, $platformName);

            return;
        });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Channel::class,
            'allow_extra_fields' => true,
            'reloadable' => true,
        ]);
    }
}