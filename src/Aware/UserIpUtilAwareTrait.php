<?php

declare(strict_types=1);

namespace App\Aware;

use App\Util\UserIpUtil;

trait UserIpUtilAwareTrait
{
    private ?UserIpUtil $userIpUtil = null;

    public function setUserIpUtil(UserIpUtil $userIpUtil): void
    {
        $this->userIpUtil = $userIpUtil;
    }
}