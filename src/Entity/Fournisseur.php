<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Fournisseur
 *
 * @ORM\Table(name="fournisseur", indexes={@ORM\Index(name="Id_Produit", columns={"Id_Produit"})})
 * @ORM\Entity(repositoryClass=App\Repository\FournisseurRepository::class)
 */
class Fournisseur
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_fournisseur", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idFournisseur;

    /**
     * @var string
     *
     * @ORM\Column(name="nom_fournisseur", type="string", length=255, nullable=false)
     */
    private $nomFournisseur;

    /**
     * @var string
     *
     * @ORM\Column(name="num_fournisseur", type="string", length=255, nullable=false)
     */
    private $numFournisseur;

    /**
     * @var string
     *
     * @ORM\Column(name="adresse_fournisseur", type="string", length=255, nullable=false)
     */
    private $adresseFournisseur;

    /**
     * 
     *
     * @ORM\ManyToOne(targetEntity="Produit")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="Id_Produit", referencedColumnName="Id_Produit")
     * })
     */
    private $idProduit;

    public function getIdFournisseur(): ?int
    {
        return $this->idFournisseur;
    }

    public function getNomFournisseur(): ?string
    {
        return $this->nomFournisseur;
    }

    public function setNomFournisseur(string $nomFournisseur)
    {
        $this->nomFournisseur = $nomFournisseur;

        return $this;
    }

    public function getNumFournisseur(): ?string
    {
        return $this->numFournisseur;
    }

    public function setNumFournisseur(string $numFournisseur)
    {
        $this->numFournisseur = $numFournisseur;

        return $this;
    }

    public function getAdresseFournisseur(): ?string
    {
        return $this->adresseFournisseur;
    }

    public function setAdresseFournisseur(string $adresseFournisseur)
    {
        $this->adresseFournisseur = $adresseFournisseur;

        return $this;
    }

    public function getIdProduit(): ?Produit
    {
        return $this->idProduit;
    }

    public function setIdProduit(?Produit $idProduit)
    {
        $this->idProduit = $idProduit;

        return $this;
    }


}