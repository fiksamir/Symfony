<?php

declare(strict_types=1);

namespace App\Util;

class UserIpUtil
{
    public function getIp(): ?string
    {
        return $_SERVER['REMOTE_ADDR'] ?? null;
    }
}