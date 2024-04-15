<?php

namespace App\Entity;

use App\Repository\FournisseurRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FournisseurRepository::class)]
class Fournisseur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $id_fournisseur = null;

    #[ORM\Column]
    private ?int $id_produit = null;

    #[ORM\Column(length: 255)]
    private ?string $nom_fournisseur = null;

    #[ORM\Column]
    private ?int $num_fournisseur = null;

    #[ORM\Column(length: 255)]
    private ?string $adresse_fournisseur = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdFournisseur(): ?int
    {
        return $this->id_fournisseur;
    }

    public function setIdFournisseur(int $id_fournisseur): static
    {
        $this->id_fournisseur = $id_fournisseur;

        return $this;
    }

    public function getIdProduit(): ?int
    {
        return $this->id_produit;
    }

    public function setIdProduit(int $id_produit): static
    {
        $this->id_produit = $id_produit;

        return $this;
    }

    public function getNomFournisseur(): ?string
    {
        return $this->nom_fournisseur;
    }

    public function setNomFournisseur(string $nom_fournisseur): static
    {
        $this->nom_fournisseur = $nom_fournisseur;

        return $this;
    }

    public function getNumFournisseur(): ?int
    {
        return $this->num_fournisseur;
    }

    public function setNumFournisseur(int $num_fournisseur): static
    {
        $this->num_fournisseur = $num_fournisseur;

        return $this;
    }

    public function getAdresseFournisseur(): ?string
    {
        return $this->adresse_fournisseur;
    }

    public function setAdresseFournisseur(string $adresse_fournisseur): static
    {
        $this->adresse_fournisseur = $adresse_fournisseur;

        return $this;
    }
}
