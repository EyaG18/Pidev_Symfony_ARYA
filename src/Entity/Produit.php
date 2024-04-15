<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Catégorie;
use Doctrine\DBAL\Types\Types;
use App\Repository\ProduitRepository;



/**
 * Produit
 *
 * @ORM\Table(name="produit", uniqueConstraints={@ORM\UniqueConstraint(name="UC_NomP", columns={"NomP"})}, indexes={@ORM\Index(name="fk_categorieP", columns={"Id_Catégorie"})})
 * @ORM\Entity(repositoryClass="App\Repository\ProduitRepository")
 */
#[ORM\Entity(repositoryClass: ProduitRepository::class)]
class Produit
{
    /**
     * 
     *
     * @ORM\Column(name="Id_Produit", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    //private  ?int $Id_Produit = null;
    public $Id_Produit;

    /**
     * 
     *
     * @ORM\Column(name="NomP", type="string", length=20, nullable=false)
     */
    #[ORM\Column(length: 20)]
    //private ?string $NomP=null;
    public $NomP;

    /**
     * 
     *
     * @ORM\Column(name="PrixP", type="float", precision=10, scale=0, nullable=false)
     */
    #[ORM\Column]
    //private ?float $PrixP=null;
    public $PrixP;

    /**
     * 
     *
     * @ORM\Column(name="QteP", type="integer", nullable=false)
     */
    #[ORM\Column]
    //private ?int $QteP=null;
    public $QteP;

    /**
     * 
     *
     * @ORM\Column(name="QteSeuilP", type="integer", nullable=false)
     */

    #[ORM\Column]
    //private ?int $QteSeuilP=null;
    public $QteSeuilP;

    /**
     *
     *
     * @ORM\Column(name="ImageP", type="string", length=255, nullable=false)
     */

    #[ORM\Column(length: 255)]
    //private ?string $ImageP=null;
    public $ImageP;


    /**
     * 
     *
     * @ORM\ManyToOne(targetEntity="Catégorie" , inversedBy="produits")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="Id_Catégorie", referencedColumnName="Id_Catégorie")
     * })
     */
    #[ORM\ManyToOne(inversedBy: 'produits')]
    //private ?Catégorie $Id_Catégorie=null;

    //private $categorie;

    public $Id_Catégorie;


    public function getIdProduit(): ?int
    {
        return $this->Id_Produit;
    }

    public function getNomp(): ?string
    {
        return $this->NomP;
    }

    public function setNomp(string $nomp): self
    {
        $this->NomP = $nomp;

        return $this;
    }

    public function getPrixp(): ?float
    {
        return $this->PrixP;
    }

    public function setPrixp(float $prixp): self
    {
        $this->PrixP = $prixp;

        return $this;
    }

    public function getQtep(): ?int
    {
        return $this->QteP;
    }

    public function setQtep(int $qtep): self
    {
        $this->QteP = $qtep;

        return $this;
    }

    public function getQteseuilp(): ?int
    {
        return $this->QteSeuilP;
    }

    public function setQteseuilp(int $qteseuilp): self
    {
        $this->QteSeuilP = $qteseuilp;

        return $this;
    }

    public function getImagep(): ?string
    {
        return $this->ImageP;
    }

    public function setImagep(string $imagep): self
    {
        $this->ImageP = $imagep;

        return $this;
    }



    public function getIdCatégorie(): ?int
    {
        return $this->Id_Catégorie;
    }

    public function setIdCatégorie(?Catégorie $Id_Catégorie)
    {
        $this->Id_Catégorie = $Id_Catégorie;

        return $this;
    }

}