<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\GraphQl\Query;
use ApiPlatform\Metadata\GraphQl\QueryCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\ApiResource;
use App\Resolver\BookCollectionResolver;
use App\Resolver\BookItemResolver;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
#[ApiResource(
    operations: [
        new Get(),
        new Patch(),
        new GetCollection(),
        new Post(),
    ],
    normalizationContext: ['groups' => ['read']],
    denormalizationContext: ['groups' => ['write']],
    paginationType: 'page',
    graphQlOperations: [
        new Query(
            resolver: BookItemResolver::class,
            normalizationContext: ['groups' => ['read_item']],
            name: 'item_query',
        ),
        new QueryCollection(
            resolver: BookCollectionResolver::class,
            paginationType: 'page',
            normalizationContext: ['groups' => ['read_collection']],
            name: 'collection_query',
        ),
    ],
)]
class Book
{
    public function __construct(
        #[Groups(['read_item', 'create'])]
        #[ORM\Id]
        #[ORM\GeneratedValue]
        #[ORM\Column(type: 'integer', unique: true)]
        private readonly int $bookId,

        #[Groups(['read_collection', 'create'])]
        #[ORM\ManyToOne(targetEntity: Store::class, inversedBy: 'books')]
        #[ORM\JoinColumn(name: 'store_id', referencedColumnName: 'store_id')]
        #[ApiProperty(readableLink: false, writableLink: false)]
        private readonly Store $store,
    ) {
    }

    #[Groups(['read_collection', 'write'])]
    #[Assert\NotBlank(allowNull: true)]
    #[ORM\Column(type: 'string', length: 50, nullable: true)]
    private ?string $name = null;
}