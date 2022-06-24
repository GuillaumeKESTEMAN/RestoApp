<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Repository\MealRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MealRepository::class)]
#[
    ApiResource(
        collectionOperations: [
            "get",
            "post"
        ],
        itemOperations: [
            "get",
            "put" => ["security" => "object.getUser() == user"],
            "patch" => ["security" => "object.getUser() == user"],
            "delete" => ["security" => "object.getUser() == user"]
        ],
        attributes: [
            "order" => ["restaurant" => "ASC"],
            "security" => "is_granted('ROLE_USER')"
        ]
    )
]
#[ApiFilter(SearchFilter::class, properties: ["restaurant" => "iexact", "name" => "ipartial"])]
class Meal
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Restaurant::class, inversedBy: 'meals')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Restaurant $restaurant = null;

    #[ORM\Column(name: 'name', type: 'string')]
    #[ApiProperty(iri: "https://schema.org/name")]
    private ?string $name = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'meals')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRestaurant(): ?Restaurant
    {
        return $this->restaurant;
    }

    public function setRestaurant(Restaurant $restaurant): self
    {
        $this->restaurant = $restaurant;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
