<?php

namespace Juszczyk\DuplicateCms\Controller\Adminhtml\Page;

use Exception;
use Juszczyk\DuplicateCms\Model\Duplicator\Page as PageDuplicator;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Cms\Ui\Component\Listing\Column\PageActions;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\UrlInterface;
use Psr\Log\LoggerInterface;

class Duplicate extends Action
{
    const ADMIN_RESOURCE = 'Magento_Cms::page';

    protected RequestInterface $request;

    protected LoggerInterface $logger;

    protected PageDuplicator $pageDuplicator;

    protected UrlInterface $urlBuilder;

    public function __construct(
        Context $context,
        RequestInterface $request,
        LoggerInterface $logger,
        PageDuplicator $pageDuplicator,
        UrlInterface $urlBuilder
    ) {
        parent::__construct($context);
        $this->request = $request;
        $this->logger = $logger;
        $this->pageDuplicator = $pageDuplicator;
        $this->urlBuilder = $urlBuilder;
    }

    /**
     * @inheritDoc
     */
    public function execute()
    {
        $pageId = $this->request->getParam('page_id', false);

        if (! $pageId) {
            $this->messageManager->addErrorMessage(
                __("Cannot find page to duplicate. Please try again.")
            );
            return $this->resultFactory->create(ResultFactory::TYPE_REDIRECT)->setRefererOrBaseUrl();
        }

        try {
            $duplicatedPageId = $this->pageDuplicator->duplicate((int)$pageId);
            $this->messageManager->addSuccessMessage(
                __('Page duplicated successfully.')
            );
            return $this->resultFactory->create(ResultFactory::TYPE_REDIRECT)
                ->setUrl(
                    $this->urlBuilder->getUrl(
                        PageActions::CMS_URL_PATH_EDIT,
                        [
                            'page_id' => $duplicatedPageId
                        ]
                    )
                );
        } catch (NoSuchEntityException $e) {
            $this->logger->error($e->getMessage());
            $this->logger->error($e->getTraceAsString());
            $this->messageManager->addErrorMessage(
                __("Cannot find page to duplicate. Please try again.")
            );
            return $this->resultFactory->create(ResultFactory::TYPE_REDIRECT)->setRefererOrBaseUrl();
        } catch (Exception $e) {
            $this->logger->error($e->getMessage());
            $this->logger->error($e->getTraceAsString());
            $this->messageManager->addErrorMessage(
                __("Cannot duplicate right now. Please try again.")
            );
            return $this->resultFactory->create(ResultFactory::TYPE_REDIRECT)->setRefererOrBaseUrl();
        }
    }
}