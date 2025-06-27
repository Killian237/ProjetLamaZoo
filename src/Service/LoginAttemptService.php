<?php


namespace App\Service;

use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Contracts\Cache\ItemInterface;

class LoginAttemptService
{
    private $cache;
    private const BASE_DURATION = 30; // 30s de base

    public function __construct()
    {
        $this->cache = new FilesystemAdapter();
    }

    public function addAttempt(string $identifier): void
    {
        $attempts = $this->getAttempts($identifier) + 1;
        $blockDuration = $this->calculateDuration($attempts);
        
        $this->cache->get($identifier, function(ItemInterface $item) use ($blockDuration, $attempts) {
            $item->expiresAfter($blockDuration);
            return [
                'attempts' => $attempts,
                'blocked_until' => time() + $blockDuration
            ];
        });
    }

    public function isBlocked(string $identifier): bool
    {
        return $this->getRemainingTime($identifier) > 0;
    }

    public function getRemainingTime(string $identifier): int
    {
        $item = $this->cache->getItem($identifier);
        if (!$item->isHit()) return 0;
        
        $data = $item->get();
        return max(0, $data['blocked_until'] - time());
    }

    private function getAttempts(string $identifier): int
    {
        $item = $this->cache->getItem($identifier);
        return $item->isHit() ? $item->get()['attempts'] : 0;
    }

    private function calculateDuration(int $attempts): int
    {
        return match(true) {
            $attempts <= 3 => 0, // Pas de blocage avant 3 échecs
            $attempts == 4 => self::BASE_DURATION,
            $attempts == 5 => 60, // 1 minute
            default => min(300, 60 * pow(2, $attempts - 5)) // Croissance exponentielle (max 5min)
        };
    }
}
