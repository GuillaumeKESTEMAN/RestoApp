<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[UniqueEntity('name')]
#[UniqueEntity('email')]
#[
    ApiResource(
        collectionOperations: [],
        itemOperations: [
            "get",
            "put",
            "patch",
            "delete"
        ],
        attributes: [
            "order" => ["name" => "ASC"],
            "security" => "is_granted('ROLE_USER') and (object == user)"
        ]
    )
]
#[ApiFilter(SearchFilter::class, properties: ["name" => "ipartial"])] class User implements PasswordAuthenticatedUserInterface, UserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', name: 'name')]
    #[Assert\NotBlank()]
    #[Assert\Length(min: 3)]
    #[ApiProperty(iri: "https://schema.org/name")]
    private string $name = '';

    #[ORM\Column(type: 'string', name: 'email')]
    #[Assert\NotBlank()]
    #[ApiProperty(iri: "https://schema.org/email")]
    private string $email = '';

    #[ORM\Column(type: 'string', name: 'password')]
    #[Assert\NotBlank()]
    #[Assert\Length(min: 5)]
    #[ApiProperty(readable: false, writable: false, iri: "https://schema.org/accessCode")]
    private string $password = '';

    /**
     * @ORM\Column(type="json")
     */
    #[ApiProperty(writable: false)]
    private array $roles = [];

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Meal::class, orphanRemoval: true)]
    private $meals;

    #[ORM\ManyToOne(targetEntity: Restaurant::class)]
    private ?Restaurant $favorite = null;

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

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    #[ApiProperty(readable: false)]
    public function getUserIdentifier(): string
    {
        return $this->name;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function eraseCredentials(): void
    {
        // utile pour supprimer la valeur du password de 'plainPassword' juste après l'avoir hashé dans 'password'
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
        $meal->setUser($this);
        if (!in_array($meal, (array)$this->meals)) {
            $this->meals[] = $meal;
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

    public function getFavorite(): ?Restaurant
    {
        return $this->favorite;
    }

    public function setFavorite(?Restaurant $favorite): void
    {
        $this->favorite = $favorite;
    }
}
