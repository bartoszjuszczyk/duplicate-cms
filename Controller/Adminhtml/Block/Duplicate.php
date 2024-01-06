<?php

namespace Juszczyk\DuplicateCms\Controller\Adminhtml\Block;

use Exception;
use Juszczyk\DuplicateCms\Model\Duplicator\Block as BlockDuplicator;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Cms\Ui\Component\Listing\Column\BlockActions;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\UrlInterface;
use Psr\Log\LoggerInterface;

class Duplicate extends Action
{
    const ADMIN_RESOURCE = 'Magento_Cms::block';

    protected RequestInterface $request;

    protected LoggerInterface $logger;

    protected PageDuplicator $pageDuplicator;

    protected UrlInterface $urlBuilder;

    public function __construct(
        Context $context,
        RequestInterface $request,
        LoggerInterface $logger,
        BlockDuplicator $blockDuplicator,
        UrlInterface $urlBuilder
    ) {
        parent::__construct($context);
        $this->request = $request;
        $this->logger = $logger;
        $this->blockDuplicator = $blockDuplicator;
        $this->urlBuilder = $urlBuilder;
    }

    /**
     * @inheritDoc
     */
    public function execute()
    {
        $blockId = $this->request->getParam('block_id', false);

        if (! $blockId) {
            $this->messageManager->addErrorMessage(
                __("Cannot find block to duplicate. Please try again.")
            );
            return $this->resultFactory->create(ResultFactory::TYPE_REDIRECT)->setRefererOrBaseUrl();
        }

        try {
            $duplicatedBlockId = $this->blockDuplicator->duplicate((int)$blockId);
            $this->messageManager->addSuccessMessage(
                __('Block duplicated successfully.')
            );
            return $this->resultFactory->create(ResultFactory::TYPE_REDIRECT)
                ->setUrl(
                    $this->urlBuilder->getUrl(
                        BlockActions::URL_PATH_EDIT,
                        [
                            'block_id' => $duplicatedBlockId
                        ]
                    )
                );
        } catch (NoSuchEntityException $e) {
            $this->logger->error($e->getMessage());
            $this->logger->error($e->getTraceAsString());
            $this->messageManager->addErrorMessage(
                __("Cannot find block to duplicate. Please try again.")
            );
            return $this->resultFactory->create(ResultFactory::TYPE_REDIRECT)->setRefererOrBaseUrl();
        } catch (Exception $e) {
            $this->logger->error($e->getMessage());
            $this->logger->error($e->getTraceAsString());
            $this->messageManager->addErrorMessage(
                __("Cannot duplicate block right now. Please try again.")
            );
            return $this->resultFactory->create(ResultFactory::TYPE_REDIRECT)->setRefererOrBaseUrl();
        }
    }
}