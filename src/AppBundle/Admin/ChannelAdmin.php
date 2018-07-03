<?php
namespace AppBundle\Admin;

use AppBundle\Admin\Channel\ChannelViewPage;
use AppBundle\Entity\Channel;
use AppBundle\Form\Type\ChannelType;
use Doctrine\ORM\EntityManager;
use Raw\Component\Admin\Admin;
use Raw\Component\Admin\AdminBuilder;
use Raw\Component\Admin\AdminInterface;
use Raw\Component\Admin\Easy\Actions;
use Raw\Component\Admin\Easy\Element\FormElement;
use Raw\Component\Admin\Easy\Element\GridElement;
use Raw\Component\Admin\Easy\Element\LayoutElement;
use Raw\Component\Admin\EasyAdmin;
use Raw\Component\Admin\Page\GridAdminPage;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChannelAdmin extends Admin
{
    use ContainerAwareTrait;


    protected function get($id)
    {
        return $this->container->get($id);
    }

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * UserAdmin constructor.
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    protected function configure()
    {
        $this->setClassName(Channel::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'className' => Channel::class,
            'gridName' => 'channels_grid',
        ]);
    }

    protected function buildFormLayout(FormElement $form, $entity, array $options)
    {
        $form
            ->setFormType(ChannelType::class)
            ->template('AppBundle:Channel:create/content.html.twig');
//            ->addTabset()
//                ->addTab('General')
//                    ->fieldset('General')
//                        ->field('name')
//                        ->field('platformName')
//                    ->endFieldset()
//                        ->fieldset('Parameters')
//                        ->field('parameters')
//                    ->endFieldset()
//                ->endTab()
//            ->endTabset();
    }

    public function build(AdminBuilder $builder, array $options)
    {
        $builder->addPage('list', GridAdminPage::class, [
            'path' => '',
            'grid_name' => 'channels_grid',
        ]);
    }

    public function buildViewLayout(LayoutElement $layout, $entity, array $options)
    {
        $page = new ChannelViewPage($this->get('app.platform_helper'), $this->get('sales.statistics.order'));
        $page->buildLayout($layout, $entity, $options);
    }

    public function buildListLayout(LayoutElement $layout, array $options)
    {
        $layout
            ->addGrid('channels_grid');
    }

    public function buildEditLayout(FormElement $layout, $entity, array $options)
    {
        $this->buildFormLayout($layout, $entity, $options);
    }

    public function buildCreateLayout(FormElement $layout, $entity, array $options)
    {
        $this->buildFormLayout($layout, $entity, $options);
    }

    public function getAdapter(Channel $channel)
    {
        return $this->get('app.platform_helper')->getAdapter($channel);
    }
}