<?php
namespace Raw\Component\Menu\Extension;

use Knp\Menu\Factory\ExtensionInterface;
use Knp\Menu\ItemInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\VarDumper\VarDumper;

class ImportExtension implements ExtensionInterface
{
    use ContainerAwareTrait;

    public function buildOptions(array $options)
    {
        return array_merge(['import' => null,], $options);
    }
    public function buildItem(ItemInterface $item, array $options)
    {
        if($options['import'] === null) {
            return;
        }
        $provider = $this->container->get('knp_menu.menu_provider');

        $importedMenu = $provider->get($options['import']);

        $this->merge($item, $importedMenu);
    }

    private function merge(ItemInterface $target, ItemInterface $source)
    {
        $target->setLabel($source->getLabel());
        $target->setLabelAttributes($source->getLabelAttributes());
        $target->setLinkAttributes($source->getLinkAttributes());
        $target->setUri($source->getUri());
        $target->setAttributes($source->getAttributes());
        $target->setChildren($source->getChildren());
        $target->setChildrenAttributes($source->getChildrenAttributes());
        $target->setExtras($source->getExtras());
    }
}