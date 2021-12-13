<?php

declare(strict_types=1);

namespace App\Aware;

use App\Util\UserIpUtil;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;

#[Autoconfigure(calls: [['setUserIpUtil', ['@App\Util\UserIpUtil']]])]
interface UserIpUtilAwareInterface
{
    public function setUserIpUtil(UserIpUtil $userIpUtil): void;
}