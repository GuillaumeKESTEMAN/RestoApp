<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Repository\RestaurantRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: RestaurantRepository::class)]
#[UniqueEntity('name')]
#[
    ApiResource(
        collectionOperations: [
            "get"
        ],
        itemOperations: [
            "get",
            "put" => ["security" => "object == user.getFavorite()"],
            "patch" => ["security" => "object == user.getFavorite()"],
            "delete" => ["security" => "object == user.getFavorite()"]
        ],
        attributes: [
            "order" => ["name" => "ASC"],
            "security" => "is_granted('ROLE_USER')"
        ]
    )
]
#[ApiFilter(SearchFilter::class, properties: ["name" => "ipartial"])]
class Restaurant
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', name: 'name')]
    #[ApiProperty(iri: "https://schema.org/name")]
    private ?string $name = null;

    private ?int $userId = null;

    #[ORM\OneToMany(mappedBy: 'restaurant', targetEntity: Meal::class, orphanRemoval: true)]
    #[ApiProperty(iri: "https://schema.org/Collection")]
    private Collection $meals;

    public function __construct()
    {
        $this->meals = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getUserId(): ?int
    {
        return $this->userId;
    }

    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }

    /**
     * @return Collection<int, Meal>
     */
    public function getMeals(): Collection
    {
        return $this->meals;
    }

    public function addMeal(Meal $meal): self
    {
        if (!$this->meals->contains($meal)) {
            $meal->setRestaurant($this);
            $this->meals->add($meal);
        }

        return $this;
    }

    public function removeMeal(Meal $meal): self
    {
        if ($this->meals->removeElement($meal)) {
            // set the owning side to null (unless already changed)
            if ($meal->getUser() === $this) {
                $meal->setUser(null);
            }
        }

        return $this;
    }
}
