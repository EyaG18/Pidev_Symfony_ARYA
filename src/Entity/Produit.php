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

class Produit
{
    /**
     * 
     *
     * @ORM\Column(name="Id_Produit", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
  
     //public   $Id_Produit = null;
    public $Id_Produit;

    /**
     * 
     *
     * @ORM\Column(name="NomP", type="string", length=20, nullable=false)
     */
   
    
    public $NomP;

    /**
     * 
     *
     * @ORM\Column(name="PrixP", type="float", precision=10, scale=0, nullable=false)
     */
  
    //private ?float $PrixP=null;
    public $PrixP;

    /**
     * 
     *
     * @ORM\Column(name="QteP", type="integer", nullable=false)
     */
   
     //private ?int $QteP=null;
     public $QteP;

    /**
     * 
     *
     * @ORM\Column(name="QteSeuilP", type="integer", nullable=false)
     */
    
   
     //private ?int $QteSeuilP=null;
     public $QteSeuilP;

    /**
     *
     *
     * @ORM\Column(name="ImageP", type="string", length=255, nullable=false)
     */

     
     //private ?string $ImageP=null;
     public $ImageP;


    /**
     * 
     *
     * @ORM\ManyToOne(targetEntity="Catégorie" , inversedBy="produits")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="Id_Categorie", referencedColumnName="Id_Categorie")
     * })
     */
    //#[ORM\ManyToOne(inversedBy:'produits')]
    //private ?Catégorie $Id_Catégorie=null;
  
    //private $categorie;

    public $Id_Categorie;


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

  

    public function getIdCatégorie()
    {
        return $this->Id_Categorie;
    }
    public function setIdCategorie( $idCategoryp): self
    {
        $this->Id_Categorie = $idCategoryp;

        return $this;
    }

    public function getIdCategorie(): ?Catégorie
    {
        return $this->Id_Categorie;
    }


}
