<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Repository\ContactRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ContactRepository::class)]
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
            "order" => ["date" => "DESC"],
            "security" => "is_granted('ROLE_USER')"
        ]
    )]
#[ApiFilter(SearchFilter::class, properties: ["email" => "exact", "sujet" => "ipartial", "description" => "ipartial", "date" => "ipartial"])]
class Contact
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    public function getId(): ?int
    {
        return $this->id;
    }

    #[ORM\Column()]
    #[ApiProperty(iri: "https://schema.org/email")]
    private string $email = '';

    #[ORM\Column(type: 'datetime')]
    #[ApiProperty(iri: "https://schema.org/DateTime")]
    private ?\DateTime $date = null;

    #[ORM\Column()]
    #[Assert\NotBlank()]
    #[ApiProperty(iri: "https://schema.org/about")]
    private string $sujet = '';

    #[ORM\Column()]
    #[Assert\Length(min: 10)]
    #[Assert\NotBlank()]
    #[ApiProperty(iri: "https://schema.org/description")]
    private string $description = '';

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user;

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getDate(): ?\DateTime
    {
        return $this->date;
    }

    public function setDate(?\DateTime $date): void
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
