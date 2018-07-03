<?php
namespace AppBundle\Batch\Processor;

use AppBundle\Entity\Channel;
use AppBundle\Mage\MageRestFactory;
use Doctrine\ORM\EntityManager;
use Platform\Helper\ProductImportHelper;
use Platform\Magento\ArrayConverter\ProductArrayConverter;
use Raw\Component\Batch\Item\ItemProcessorInterface;
use Raw\Component\Batch\Item\Reader\BasicItemReader;
use Raw\Component\Batch\Step\StepExecutionAwareInterface;
use Raw\Component\Batch\Step\StepExecutionAwareTrait;

class ChannelProductProcessor implements ItemProcessorInterface, StepExecutionAwareInterface
{
    use StepExecutionAwareTrait;

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var ProductImportHelper
     */
    private $productImportHelper;

    /**
     * ChannelProductProcessor constructor.
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->productImportHelper = new ProductImportHelper($this->entityManager);
    }

    public function process($item)
    {
        $productData = $item;

        $channel = $this->entityManager->getRepository(Channel::class)->find($this->stepExecution->getJobParameters()['channel']);

        list($product) = $this->productImportHelper->insertProductData($channel, [$productData]);
        return $product;
    }

}