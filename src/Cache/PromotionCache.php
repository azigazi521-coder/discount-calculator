<?php

declare(strict_types=1);

namespace App\Cache;

use App\Entity\Product;
use App\Repository\PromotionRepository;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class PromotionCache
{
    public function __construct(
        private CacheInterface $cache,
        private PromotionRepository $repository,
    ) {}

    public function findValidForProduct(Product $product, string $requestDate): ?array
    {
        return $this->cache->get("find-valid-for-product-{$product->getId()}", function (ItemInterface $item)
        use ($product, $requestDate) {

            $item->expiresAfter(60);
            
            return $this->repository->findValidForProduct(
                $product,
                date_create_immutable($requestDate)
            );
        });
    }
}
