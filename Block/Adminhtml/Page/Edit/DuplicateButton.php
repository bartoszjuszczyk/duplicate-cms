<?php

namespace Juszczyk\DuplicateCms\Block\Adminhtml\Page\Edit;

use Magento\Cms\Block\Adminhtml\Page\Edit\GenericButton;
use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

class DuplicateButton extends GenericButton implements ButtonProviderInterface
{

    /**
     * @inheritDoc
     */
    public function getButtonData(): array
    {
        $data = [];
        if ($this->getPageId()) {
            $data = [
                'label' => __('Duplicate'),
                'class' => 'duplicate',
                'on_click' => 'deleteConfirm(\'' . __(
                        'Are you sure you want to duplicate this page?'
                    ) . '\', \'' . $this->getDuplicateUrl() . '\', {"data": {}})',
                'sort_order' => 25,
            ];
        }
        return $data;
    }

    /**
     * @return string
     */
    protected function getDuplicateUrl(): string
    {
        return $this->getUrl(
            '*/*/duplicate',
            [
                'page_id' => $this->getPageId()
            ]
        );
    }
}
