<?php

namespace Juszczyk\DuplicateCms\Plugin;

use Magento\Cms\Ui\Component\Listing\Column\BlockActions;
use Magento\Framework\UrlInterface;

class AddDuplicateActionToBlockListing
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
     * @param BlockActions $subject
     * @param array $result
     * @return array
     */
    public function afterPrepareDataSource(BlockActions $subject, array $result): array
    {
        if (isset($result['data']['items'])) {
            foreach ($result['data']['items'] as &$item) {
                if (isset($item['block_id'])) {
                    $item['actions']['duplicate'] = $this->getDuplicateAction($item['block_id']);
                }
            }
        }

        return $result;
    }

    /**
     * @param int $blockId
     * @return array[]
     */
    protected function getDuplicateAction(int $blockId): array
    {
        return [
            'href' => $this->urlBuilder->getUrl(
                'cms/block/duplicate',
                [
                    'block_id' => $blockId
                ]
            ),
            'label' => __('Duplicate'),
            'confirm' => [
                'title' => __('Duplicate Block'),
                'message' => __('Are you sure you want to duplicate this block?'),
            ]
        ];
    }
}
