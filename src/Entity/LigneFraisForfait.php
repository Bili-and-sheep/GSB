<?php

namespace App\Entity;

use App\Repository\LigneFraisForfaitRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LigneFraisForfaitRepository::class)]
class LigneFraisForfait
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $quantite = null;

    #[ORM\ManyToOne(inversedBy: 'LigneFraisForfait')]
    private ?FicheFrais $ficheFrais = null;

    #[ORM\ManyToOne(inversedBy: 'ligneFraisForfaits')]
    private ?FraisForfait $FraisForfait = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(int $quantite): static
    {
        $this->quantite = $quantite;

        return $this;
    }

    public function getFicheFrais(): ?FicheFrais
    {
        return $this->ficheFrais;
    }

    public function setFicheFrais(?FicheFrais $ficheFrais): static
    {
        $this->ficheFrais = $ficheFrais;

        return $this;
    }

    public function getFraisForfait(): ?FraisForfait
    {
        return $this->FraisForfait;
    }

    public function setFraisForfait(?FraisForfait $FraisForfait): static
    {
        $this->FraisForfait = $FraisForfait;

        return $this;
    }

    public function getMontant(): float
    {
        // Le montant total de toute la ligne de frais forfait
        return $this->FraisForfait->getMontant() * $this->quantite;
    }
    public function getMontantLL(): float
    {
        // Le montant total est calculé comme quantité x montant unitaire
        return $this->quantite * $this->FraisForfait->getMontant();
    }
}
