<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Produit;
use App\Entity\User;


/**
 * Panier
 *
 * @ORM\Table(name="panier", indexes={@ORM\Index(name="produitFK", columns={"Id_Produit"}), @ORM\Index(name="fk_panier_user", columns={"id_user"})})
 * @ORM\Entity(repositoryClass=App\Repository\PaierRepository::class)
 */
class Panier
{
    /**
     * @var int
     *
     * @ORM\Column(name="Id_Panier", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idPanier;

    /**
     * @var int
     *
     * @ORM\Column(name="QuantiteParProduit", type="integer", nullable=false)
     */
    private $quantiteparproduit;

    /**
     * 
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_user", referencedColumnName="id_user")
     * })
     */
    private $idUser;

    /**
     * 
     *
     * @ORM\ManyToOne(targetEntity="Produit")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="Id_Produit", referencedColumnName="Id_Produit")
     * })
     */
    private $idProduit;

    public function getIdPanier(): ?int
    {
        return $this->idPanier;
    }

    public function getQuantiteparproduit(): ?int
    {
        return $this->quantiteparproduit;
    }

    public function setQuantiteparproduit(int $quantiteparproduit)
    {
        $this->quantiteparproduit = $quantiteparproduit;

        return $this;
    }

    public function getIdUser(): ?User
    {
        return $this->idUser;
    }

    public function setIdUser(?User $idUser)
    {
        $this->idUser = $idUser;

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