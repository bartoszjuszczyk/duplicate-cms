<?php

namespace Juszczyk\DuplicateCms\Api;

use Magento\Framework\Exception\LocalizedException;

interface DuplicatorInterface
{
    /**
     * @param int $id
     * @return int
     * @throws LocalizedException
     */
    public function duplicate(int $id): int;
}