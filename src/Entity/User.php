<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
class User implements PasswordAuthenticatedUserInterface, UserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', name: 'name')]
    #[Assert\NotBlank()]
    #[Assert\Length(min:'3')]
    private string $name = '';

    #[ORM\Column(type: 'string', name: 'email')]
    #[Assert\NotBlank()]
    private string $email = '';
    
    #[ORM\Column(type: 'string', name: 'password')]
    #[Assert\NotBlank()]
    #[Assert\Length(min:'5')]
    private string $password = '';

    #[ORM\Column(type: 'integer', name: 'meal_id', nullable: true)]
    private ?int $mealId = null;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

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

    public function getMealId(): ?int
    {
        return $this->mealId;
    }

    public function setMealId(int $mealId): void
    {
        $this->mealId = $mealId;
    }

    public function getUserIdentifier(): string
    {
        return (string) $this->name;
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

    public function eraseCredentials()
    {
        
    }
}
