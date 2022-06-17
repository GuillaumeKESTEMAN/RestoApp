<?php

namespace App\Entity;

use App\Repository\RandomMealRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RandomMealRepository::class)]
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
    public ?\DateTime $date = null;

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
