<?php

namespace Juszczyk\DuplicateCms\Block\Adminhtml\Block\Edit;

use Magento\Cms\Block\Adminhtml\Block\Edit\GenericButton;
use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

class DuplicateButton extends GenericButton implements ButtonProviderInterface
{

    /**
     * @inheritDoc
     */
    public function getButtonData(): array
    {
        $data = [];
        if ($this->getBlockId()) {
            $data = [
                'label' => __('Duplicate'),
                'class' => 'duplicate',
                'on_click' => 'deleteConfirm(\'' . __(
                        'Are you sure you want to duplicate this block?'
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
                'block_id' => $this->getBlockId()
            ]
        );
    }
}
