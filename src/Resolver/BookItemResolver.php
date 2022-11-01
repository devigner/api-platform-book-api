<?php

declare(strict_types=1);

namespace App\Resolver;

use ApiPlatform\GraphQl\Resolver\QueryItemResolverInterface;
use App\Entity\Book;

class BookItemResolver implements QueryItemResolverInterface
{
    public function __invoke(?object $item, array $context): object
    {
        return $item;
    }
}