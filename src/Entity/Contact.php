<?php

namespace App\Entity;

use App\Repository\ContactRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ContactRepository::class)]
class Contact
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    public function getId(): ?int
    {
        return $this->id;
    }

    #[ORM\Column(type: 'string', name: 'email')]
    public string $email = '';

    #[ORM\Column(type: 'integer', name: 'user_id')]
    public int $userId = 0;

    #[ORM\Column(type: 'string', name: 'date')]
    public string $date = '';

    #[ORM\Column(type: 'string', name: 'sujet')]
    #[Assert\NotBlank()]
    public string $sujet = '';

    #[ORM\Column(type: 'string', name: 'description')]
    #[Assert\Length(min:'10')]
    #[Assert\NotBlank()]
    public string $description = '';

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getUserId(): ?int
    {
        return $this->userId;
    }

    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }

    public function getDate(): ?string
    {
        return $this->date;
    }

    public function setDate(string $date): void
    {
        $this->date = $date;
    }

    public function getSujet(): ?string
    {
        return $this->sujet;
    }

    public function setSujet(string $sujet): void
    {
        $this->sujet = $sujet;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }
}
