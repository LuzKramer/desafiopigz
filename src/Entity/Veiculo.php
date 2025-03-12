<?php

namespace App\Entity;

use App\Repository\VeiculoRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VeiculoRepository::class)]
class Veiculo
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $carro_id = null;

    #[ORM\Column(length: 255)]
    private ?string $cor = null;

    #[ORM\Column]
    private ?float $valor = null;

    #[ORM\Column(type: Types::BIGINT)]
    private ?string $kilometragem = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
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

    public function getCor(): ?string
    {
        return $this->cor;
    }

    public function setCor(string $cor): static
    {
        $this->cor = $cor;

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

    public function getKilometragem(): ?string
    {
        return $this->kilometragem;
    }

    public function setKilometragem(string $kilometragem): static
    {
        $this->kilometragem = $kilometragem;

        return $this;
    }
}
