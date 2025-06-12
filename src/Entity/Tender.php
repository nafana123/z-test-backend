<?php

namespace App\Entity;

use App\Repository\TenderRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: TenderRepository::class)]
class Tender
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['tender:list'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['tender:list'])]
    private ?string $externalCode = null;

    #[ORM\Column(length: 255)]
    #[Groups(['tender:list'])]
    private ?string $number = null;

    #[ORM\Column(length: 255)]
    #[Groups(['tender:list'])]
    private ?string $status = null;

    #[ORM\Column(length: 255)]
    #[Groups(['tender:list'])]
    private ?string $title = null;

    #[ORM\Column(type: 'datetime')]
    #[Groups(['tender:list'])]
    private \DateTimeInterface $updatedAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getExternalCode(): ?string
    {
        return $this->externalCode;
    }

    public function setExternalCode(string $externalCode): static
    {
        $this->externalCode = $externalCode;

        return $this;
    }

    public function getNumber(): ?string
    {
        return $this->number;
    }

    public function setNumber(string $number): static
    {
        $this->number = $number;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getUpdatedAt(): \DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}
