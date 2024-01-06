<?php

namespace Juszczyk\DuplicateCms\Controller\Adminhtml\Page;

use Juszczyk\DuplicateCms\Model\Duplicator\Page as PageDuplicator;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Cms\Model\ResourceModel\Page\CollectionFactory;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Ui\Component\MassAction\Filter;

class MassDuplicate extends Action implements HttpPostActionInterface
{
    const ADMIN_RESOURCE = 'Magento_Cms::page';

    protected Filter $filter;

    protected CollectionFactory $collectionFactory;

    protected PageDuplicator $pageDuplicator;

    public function __construct(
        Context $context,
        Filter $filter,
        CollectionFactory $collectionFactory,
        PageDuplicator $pageDuplicator
    ) {
        $this->filter = $filter;
        $this->collectionFactory = $collectionFactory;
        $this->pageDuplicator = $pageDuplicator;
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
            $this->pageDuplicator->duplicate((int)$block->getId());
        }

        $this->messageManager->addSuccessMessage(__('A total of %1 record(s) have been duplicated.', $collectionSize));

        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        return $resultRedirect->setPath('*/*/');
    }
}