<?php

declare(strict_types=1);

namespace App\Service;

use App\Aware\UserIpUtilAwareInterface;
use App\Aware\UserIpUtilAwareTrait;
use App\Util\UserIpUtil;
use Michelf\MarkdownInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Symfony\Component\Cache\CacheItem;
use Symfony\Contracts\Cache\CacheInterface;

class MarkdownService implements LoggerAwareInterface, UserIpUtilAwareInterface
{
    use LoggerAwareTrait;
    use UserIpUtilAwareTrait;

    private CacheInterface $cache;
    private MarkdownInterface $markdown;
    private bool $isDebug;

    public function __construct(
        CacheInterface $cache,
        MarkdownInterface $markdown,
        bool $isDebug
    ) {
        $this->cache = $cache;
        $this->markdown = $markdown;
        $this->isDebug = $isDebug;
    }


    public function parse(string $text): string
    {
        return $this->cache->get(
            md5($text),
            fn(CacheItem $item) => $this->markdown->transform($text)
        );
    }
}