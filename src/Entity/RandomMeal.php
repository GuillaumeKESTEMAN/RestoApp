<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Repository\RandomMealRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RandomMealRepository::class)]
#[
    ApiResource(
        collectionOperations: [
            "get"
        ],
        itemOperations: [
            "get",
            "put" => ["security" => "is_granted('ROLE_ADMIN')"],
            "patch" => ["security" => "is_granted('ROLE_ADMIN')"],
            "delete" => ["security" => "is_granted('ROLE_ADMIN')"]
        ],
        attributes: [
            "order" => ["date" => "DESC"],
            "security" => "is_granted('ROLE_USER')"
        ]
    )
]
#[ApiFilter(SearchFilter::class, properties: ["meal" => "ipartial"])]
class RandomMeal
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Meal::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Meal $meal;

    #[ORM\Column(type: 'datetime')]
    #[ApiProperty(iri: "https://schema.org/DateTime")]
    private ?\DateTime $date = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Meal|null
     */
    public function getMeal(): ?Meal
    {
        return $this->meal;
    }

    /**
     * @param Meal|null $meal
     */
    public function setMeal(?Meal $meal): void
    {
        $this->meal = $meal;
    }

    /**
     * @return \DateTime|null
     */
    public function getDate(): ?\DateTime
    {
        return $this->date;
    }

    /**
     * @param \DateTime|null $date
     */
    public function setDate(?\DateTime $date): void
    {
        $this->date = $date;
    }
}
