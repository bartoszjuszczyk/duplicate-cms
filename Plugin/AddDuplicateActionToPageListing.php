<?php

namespace Juszczyk\DuplicateCms\Plugin;

use Magento\Cms\Ui\Component\Listing\Column\PageActions;
use Magento\Framework\UrlInterface;

class AddDuplicateActionToPageListing
{
    protected UrlInterface $urlBuilder;

    /**
     * @param UrlInterface $urlBuilder
     */
    public function __construct(UrlInterface $urlBuilder)
    {
        $this->urlBuilder = $urlBuilder;
    }

    /**
     * @param PageActions $subject
     * @param array $result
     * @return array
     */
    public function afterPrepareDataSource(PageActions $subject, array $result): array
    {
        if (isset($result['data']['items'])) {
            foreach ($result['data']['items'] as &$item) {
                if (isset($item['page_id'])) {
                    $item['actions']['duplicate'] = $this->getDuplicateAction($item['page_id']);
                }
            }
        }

        return $result;
    }

    /**
     * @param int $pageId
     * @return array[]
     */
    protected function getDuplicateAction(int $pageId): array
    {
        return [
            'href' => $this->urlBuilder->getUrl(
                'cms/page/duplicate',
                [
                    'page_id' => $pageId
                ]
            ),
            'label' => __('Duplicate'),
            'confirm' => [
                'title' => __('Duplicate Page'),
                'message' => __('Are you sure you want to duplicate this page?'),
            ]
        ];
    }
}
