<?php

namespace Juszczyk\DuplicateCms\Model\Duplicator;

use Juszczyk\DuplicateCms\Api\DuplicatorInterface;
use Magento\Cms\Api\BlockRepositoryInterface;
use Magento\Cms\Api\Data\BlockInterface;
use Magento\Cms\Model\BlockFactory;
use Magento\Framework\Exception\LocalizedException;

class Block implements DuplicatorInterface
{
    protected BlockRepositoryInterface $blockRepository;

    protected BlockFactory $blockFactory;

    /**
     * @param BlockRepositoryInterface $blockRepository
     * @param BlockFactory $blockFactory
     */
    public function __construct(
        BlockRepositoryInterface $blockRepository,
        BlockFactory $blockFactory
    ) {
        $this->blockRepository = $blockRepository;
        $this->blockFactory = $blockFactory;
    }

    /**
     * @inheritDoc
     */
    public function duplicate(int $id): int
    {
        $originalBlock = $this->getBlock($id);
        $duplicatedBlock = $this->blockFactory->create();
        $duplicatedBlock->setData($this->prepareOriginalBlockData($originalBlock));
        $this->blockRepository->save($duplicatedBlock);

        return (int)$duplicatedBlock->getId();
    }

    /**
     * @param int $id
     * @return BlockInterface
     * @throws LocalizedException
     */
    protected function getBlock(int $id): BlockInterface
    {
        return $this->blockRepository->getById($id);
    }

    /**
     * @param BlockInterface $block
     * @return array
     */
    protected function prepareOriginalBlockData(BlockInterface $block): array
    {
        $blockData = $block->getData();
        $dataKeysToRemove = [
            'block_id',
            'creation_time',
            'update_time'
        ];
        foreach ($dataKeysToRemove as $key) {
            unset($blockData[$key]);
        }

        return $this->setUniqueTitleAndIdentifier($blockData);
    }

    /**
     * @param array $blockData
     * @return array
     */
    protected function setUniqueTitleAndIdentifier(array $blockData): array
    {
        if (str_contains($blockData['title'], ' - duplicated ')) {
            $blockData['title'] = str_replace(' - duplicated ', '', $blockData['title']);
            $blockData['title'] = substr($blockData['title'], 0, -19);
        }
        if (str_contains($blockData['identifier'], '-duplicated-')) {
            $blockData['identifier'] = str_replace('-duplicated-', '', $blockData['identifier']);
            $blockData['identifier'] = substr($blockData['identifier'], 0, -14);
        }
        $blockData['title'] .= ' - duplicated ' . date('Y-m-d H:i:s');
        $blockData['identifier'] .= '-duplicated-' . date('YmdHis');

        return $blockData;
    }
}
