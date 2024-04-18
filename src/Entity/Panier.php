<?php

namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\User;
use App\Entity\Produit;
use App\Repository\PanierRepository;

/**
 * Panier
 *
 * @ORM\Table(name="panier", indexes={@ORM\Index(name="produitFK", columns={"Id_Produit"}), @ORM\Index(name="fk_panier_user", columns={"id_user"})})
 * @ORM\Entity(repositoryClass="App\Repository\PanierRepository")
 */
class Panier
{
    /**
     * 
     *
     * @ORM\Column(name="Id_Panier", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    public $Id_Panier;

    /**
     * 
     *
     * @ORM\Column(name="QuantiteParProduit", type="integer", nullable=false)
     */
    public $QuantiteParProduit;

    /**
     * 
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_user", referencedColumnName="id_user")
     * })
     */
    public $id_user;

    /**
     * 
     *
     * @ORM\ManyToOne(targetEntity="Produit")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="Id_Produit", referencedColumnName="Id_Produit")
     * })
     */
    public $Id_Produit;



/**
     * 
     *
     * @ORM\Column(name="PrixPanierUnitaire", type="float", nullable=false)
     */
   

     public $PrixPanierUnitaire;
    public function getprixunitairepanier()
    {
        return $this->PrixPanierUnitaire;
    }
    public function setprixpanierunitaire(float $PrixPanierUnitaire): self
    {
        $this->PrixPanierUnitaire = $PrixPanierUnitaire;

        return $this;
    }



    public function getIdPanier(): ?int
    {
        return $this->Id_Panier;
    }

    public function getQuantiteparproduit(): ?int
    {
        return $this->QuantiteParProduit;
    }

    public function setQuantiteparproduit(int $quantiteparproduit): self
    {
        $this->QuantiteParProduit = $quantiteparproduit;

        return $this;
    }

    public function getIdUser()
    {
        return $this->id_user;
    }

    public function setIdUser(?User $idUser): self
    {
        $this->id_user = $idUser;

        return $this;
    }

    public function getIdProduit()
    {
        return $this->Id_Produit;
    }

    public function setIdProduit(?Produit $idProduit): self
    {
        $this->Id_Produit = $idProduit;

        return $this;
    }

    public function getPrixPanierUnitaire(): ?float
    {
        return $this->PrixPanierUnitaire;
    }


}
