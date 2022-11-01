<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Metadata\GraphQl\QueryCollection;
use ApiPlatform\Metadata\GraphQl\Query;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
            normalizationContext: ['groups' => ['read_item']],
            name: 'item_query',
        ),
        new QueryCollection(
            paginationType: 'page',
            normalizationContext: ['groups' => ['read_collection']],
            name: 'collection_query',
        ),
    ],
)]

class Store
{
    public function __construct(
        #[Groups(['read_item', 'read_collection'])]
        #[ORM\Id]
        #[ORM\GeneratedValue]
        #[ORM\Column(type: 'bigint', unique: true)]
        private readonly int $storeId,
    ) {
        $this->books = new ArrayCollection();
    }

    #[Groups(['read_item', 'read_collection'])]
    #[ORM\OneToMany(mappedBy: 'store', targetEntity: Book::class)]
    #[ORM\JoinColumn(name: 'store_id', referencedColumnName: 'store_id')]
    private Collection $books;

    #[Groups(['read_collection', 'write'])]
    #[Assert\NotBlank(allowNull: true)]
    #[ORM\Column(type: 'string', length: 50, nullable: true)]
    private ?string $name = null;
}