<?php
namespace Raw\Pager\Extension\Core;

use Raw\Bundle\PagerBundle\Form\Type\PagerType;
use Raw\Pager\Event\BuildViewEvent;
use Raw\Pager\Event\HandleRequestEvent;
use Raw\Pager\Pager;
use Raw\Pager\PagerBuilder;
use Raw\Pager\PagerEvents;
use Raw\Pager\PagerExtension;
use Raw\Pager\PagerRegistry;
use Raw\Pager\PagerView;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\VarDumper\VarDumper;

class CoreExtension extends PagerExtension
{
    /**
     * @var PagerRegistry
     */
    private $registry;

    public function __construct(PagerRegistry $registry = null)
    {
        $this->registry = $registry ?: new PagerRegistry();
    }

    public function handleRequest(Pager $pager, $request, array $options)
    {
        $this->registry->register($pager);
    }

    public function buildPager(PagerBuilder $builder, array $options)
    {

    }

    public function buildView(PagerView $view, Pager $pager, array $options)
    {
        $totalCount = $pager->getTotalCount();
        $items = $pager->getItems();

        $rangeOffset = 3;
        $rangeStart = max(1, $pager->getCurrentPage() - $rangeOffset);
        $rangeEnd = min($pager->getAmountOfPages(), $pager->getCurrentPage() + $rangeOffset);
        $buttonsRange = range($rangeStart, $rangeEnd);

        $view->vars = [
            'pager' => $view,
            'namespace' => $pager->getNamespace(),
            'page' => $pager->getCurrentPage(),
            'currentPage' => $pager->getCurrentPage(),
            'pageSize' => $pager->getPageSize(),
            'amountOfPages' => $pager->getAmountOfPages(),
            'items' => $items,
            'totalCount' => $totalCount,
            'all_visible' => $totalCount === count($items),
            'offset' => $pager->getOffset(),
            'startIndex' => $pager->getOffset(),
            'endIndex' => $pager->getOffset() + count($items),
            'hasNextPage' => $pager->getCurrentPage() < $pager->getAmountOfPages(),
            'hasPreviousPage' => $pager->getCurrentPage() > 1,
            'previousPage' => $pager->getPreviousPage(),
            'nextPage' => $pager->getNextPage(),
            'firstPage' => 1,
            'lastPage' => $pager->getAmountOfPages(),
            'buttonsRange' => $buttonsRange,
        ];
    }
}