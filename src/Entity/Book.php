<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Resolver\BookCollectionResolver;
use App\Resolver\BookItemResolver;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
#[ApiResource(
    collectionOperations: [
        'get',
        'post' => ['denormalization_context' => ['groups' => ['read']]],
    ],
    graphql: [
        'item_query' => [
            'item_query' => BookItemResolver::class,
            'normalization_context' => ['groups' => ['read']],
        ],
        'collection_query' => [
            'pagination_type' => 'page',
            'collection_query' => BookCollectionResolver::class,
            'normalization_context' => ['groups' => ['read']],
        ],
    ],
    itemOperations: [
        'get',
        'patch',
    ],
    attributes: [
        'pagination_type' => 'page',
    ],
    denormalizationContext: ['groups' => ['write']],
    normalizationContext: ['groups' => ['read']],
)]
class Book
{
    public function __construct(
        #[Groups(['read', 'create'])]
        #[Assert\Uuid]
        #[ORM\Id]
        #[ORM\Column(type: 'string', length: 36, unique: true)]
        private readonly string $bookId,

        #[ORM\ManyToOne(targetEntity: Store::class, inversedBy: 'books')]
        #[ORM\JoinColumn(name: 'store_id', referencedColumnName: 'store_id')]
        #[ApiProperty(readableLink: false, writableLink: false)]
        private readonly Store $store,
    ) {
        
    }
}