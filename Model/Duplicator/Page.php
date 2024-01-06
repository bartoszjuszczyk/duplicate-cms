<?php

namespace Juszczyk\DuplicateCms\Model\Duplicator;

use Juszczyk\DuplicateCms\Api\DuplicatorInterface;
use Magento\Cms\Api\Data\PageInterface;
use Magento\Cms\Api\PageRepositoryInterface;
use Magento\Cms\Model\PageFactory;
use Magento\Framework\Exception\LocalizedException;

class Page implements DuplicatorInterface
{
    protected PageRepositoryInterface $pageRepository;

    protected PageFactory $pageFactory;

    /**
     * @param PageRepositoryInterface $pageRepository
     * @param PageFactory $pageFactory
     */
    public function __construct(
        PageRepositoryInterface $pageRepository,
        PageFactory $pageFactory
    ) {
        $this->pageRepository = $pageRepository;
        $this->pageFactory = $pageFactory;
    }

    /**
     * @inheritDoc
     */
    public function duplicate(int $id): int
    {
        $originalPage = $this->getPage($id);
        $duplicatedPage = $this->pageFactory->create();
        $duplicatedPage->setData($this->prepareOriginalPageData($originalPage));
        $this->pageRepository->save($duplicatedPage);

        return (int)$duplicatedPage->getId();
    }

    /**
     * @param int $id
     * @return PageInterface
     * @throws LocalizedException
     */
    protected function getPage(int $id): PageInterface
    {
        return $this->pageRepository->getById($id);
    }

    /**
     * @param PageInterface $page
     * @return array
     */
    protected function prepareOriginalPageData(PageInterface $page): array
    {
        $pageData = $page->getData();
        $dataKeysToRemove = [
            'page_id',
            'creation_time',
            'update_time'
        ];
        foreach ($dataKeysToRemove as $key) {
            unset($pageData[$key]);
        }

        return $this->setUniqueTitleAndIdentifier($pageData);
    }

    /**
     * @param array $pageData
     * @return array
     */
    protected function setUniqueTitleAndIdentifier(array $pageData): array
    {
        if (str_contains($pageData['title'], ' - duplicated ')) {
            $pageData['title'] = str_replace(' - duplicated ', '', $pageData['title']);
            $pageData['title'] = substr($pageData['title'], -19);
        }
        if (str_contains($pageData['identifier'], '-duplicated-')) {
            $pageData['identifier'] = str_replace('-duplicated-', '', $pageData['identifier']);
            $pageData['identifier'] = substr($pageData['identifier'], -14);
        }
        $pageData['title'] .= ' - duplicated ' . date('Y-m-d H:i:s');
        $pageData['identifier'] .= '-duplicated-' . date('YmdHis');

        return $pageData;
    }
}
