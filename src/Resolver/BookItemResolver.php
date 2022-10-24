<?php

declare(strict_types=1);

namespace App\Resolver;

use ApiPlatform\Core\GraphQl\Resolver\QueryItemResolverInterface;
use App\Entity\Book;

class BookItemResolver implements QueryItemResolverInterface
{
    public function __invoke($item, array $context): ?Book
    {
        return $item;
    }
}