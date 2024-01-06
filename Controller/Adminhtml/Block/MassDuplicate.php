<?php

namespace Juszczyk\DuplicateCms\Controller\Adminhtml\Block;

use Juszczyk\DuplicateCms\Model\Duplicator\Block as BlockDuplicator;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Cms\Model\ResourceModel\Block\CollectionFactory;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Ui\Component\MassAction\Filter;

class MassDuplicate extends Action implements HttpPostActionInterface
{
    const ADMIN_RESOURCE = 'Magento_Cms::block';

    protected Filter $filter;

    protected CollectionFactory $collectionFactory;

    protected BlockDuplicator $blockDuplicator;

    public function __construct(
        Context $context,
        Filter $filter,
        CollectionFactory $collectionFactory,
        BlockDuplicator $blockDuplicator
    ) {
        $this->filter = $filter;
        $this->collectionFactory = $collectionFactory;
        $this->blockDuplicator = $blockDuplicator;
        parent::__construct($context);
    }

    /**
     * @inheritDoc
     */
    public function execute()
    {
        $collection = $this->filter->getCollection($this->collectionFactory->create());
        $collectionSize = $collection->getSize();

        foreach ($collection as $block) {
            $this->blockDuplicator->duplicate((int)$block->getId());
        }

        $this->messageManager->addSuccessMessage(__('A total of %1 record(s) have been duplicated.', $collectionSize));

        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        return $resultRedirect->setPath('*/*/');
    }
}