<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\CarroRepository;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity(repositoryClass: CarroRepository::class)]
#[ApiResource]
class Carro
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $fabricante = null;

    #[ORM\Column(length: 255)]
    private ?string $modelo = null;

    #[ORM\Column]
    private ?int $ano = null;

    #[ORM\Column(length: 255)]
    private ?string $version = null;

    #[ORM\Column(length: 255)]
    private ?string $combustivel = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getFabricante(): ?string
    {
        return $this->fabricante;
    }

    public function setFabricante(string $fabricante): static
    {
        $this->fabricante = $fabricante;

        return $this;
    }

    public function getModelo(): ?string
    {
        return $this->modelo;
    }

    public function setModelo(string $modelo): static
    {
        $this->modelo = $modelo;

        return $this;
    }

    public function getAno(): ?int
    {
        return $this->ano;
    }

    public function setAno(int $ano): static
    {
        $this->ano = $ano;

        return $this;
    }

    public function getVersion(): ?string
    {
        return $this->version;
    }

    public function setVersion(string $version): static
    {
        $this->version = $version;

        return $this;
    }

    public function getCombustivel(): ?string
    {
        return $this->combustivel;
    }

    public function setCombustivel(string $combustivel): static
    {
        $this->combustivel = $combustivel;

        return $this;
    }
}
