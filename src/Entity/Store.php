<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
#[ApiResource(
    collectionOperations: [
        'get',
        'post',
    ],
    graphql: [
        'item_query' => [
            'normalization_context' => ['groups' => ['read']],
        ],
        'collection_query' => [
            'pagination_type' => 'page',
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
class Store
{
    public function __construct(
        #[Groups(['read', 'create'])]
        #[Assert\Uuid]
        #[ORM\Id]
        #[ORM\Column(type: 'string', length: 36, unique: true)]
        private readonly string $storeId,
    ) {
        $this->books = new ArrayCollection();
    }

    #[ORM\OneToMany(mappedBy: 'store', targetEntity: Book::class)]
    #[ORM\JoinColumn(name: 'store_id', referencedColumnName: 'store_id')]
    private Collection $books;
}