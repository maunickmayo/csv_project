<?php

namespace App\Entity;

use App\Repository\StationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StationRepository::class)]
class Station
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $ligneLogo = null;

    #[ORM\Column]
    private ?bool $terminus = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $connections = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getLigneLogo(): ?string
    {
        return $this->ligneLogo;
    }

    public function setLigneLogo(string $ligneLogo): static
    {
        $this->ligneLogo = $ligneLogo;

        return $this;
    }

    public function isTerminus(): ?bool
    {
        return $this->terminus;
    }

    public function setTerminus(bool $terminus): static
    {
        $this->terminus = $terminus;

        return $this;
    }

    public function getConnections(): ?string
    {
        return $this->connections;
    }

    public function setConnections(?string $connections): static
    {
        $this->connections = $connections;

        return $this;
    }
}
