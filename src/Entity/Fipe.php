<?php

namespace App\Entity;

use App\Repository\FipeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FipeRepository::class)]
class Fipe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $carro_id = null;

    #[ORM\Column]
    private ?float $valor = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCarroId(): ?int
    {
        return $this->carro_id;
    }

    public function setCarroId(int $carro_id): static
    {
        $this->carro_id = $carro_id;

        return $this;
    }

    public function getValor(): ?float
    {
        return $this->valor;
    }

    public function setValor(float $valor): static
    {
        $this->valor = $valor;

        return $this;
    }
}
