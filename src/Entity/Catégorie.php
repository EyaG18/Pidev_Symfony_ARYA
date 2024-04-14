<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\Repository\CategoryRepository;

/**
 * Catégorie
 *
 * @ORM\Table(name="catégorie", uniqueConstraints={@ORM\UniqueConstraint(name="UC_NomCatégorie", columns={"NomCatégorie"})})
 * @ORM\Entity(repositoryClass="App\Repository\CategoryRepository")
 */
#[ORM\Entity(repositoryClass:CategoryRepository::class)]
 class Catégorie
{
    /**
     * 
     *
     * @ORM\Column(name="Id_Catégorie", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
   //private  ?int $Id_Catégorie = null;
   public $Id_Catégorie;






    /**
     * 
     *
     * @ORM\Column(name="NomCatégorie", type="string", length=20, nullable=false)
     */

    #[ORM\Column(length:20)]
    //private ?string $NomCategorie=null;
    public $NomCatégorie;

    public function getIdCatégorie(): ?int
    {
        return $this->Id_Catégorie;
    }

    public function getNomcatégorie(): ?string
    {
        return $this->NomCatégorie;
    }

    public function setNomcatégorie(string $nomcatégorie): self
    {
        $this->NomCatégorie = $nomcatégorie;

        return $this;
    }

     /**
     * @ORM\OneToMany(targetEntity="App\Entity\Produit", mappedBy="Id_Catégorie")
     */
    private $produits;

    public function __construct()
    {
        $this->produits = new ArrayCollection();
    }

 /**
     * @return Collection|Produit[]
     */
    public function getProduits(): Collection
    {
        return $this->produits;
    }

    public function addProduit(Produit $produit): static
    {
        if (!$this->produits->contains($produit)) {
            $this->produits->add($produit);
            $produit->setIdCatégorie($this);
        }

        return $this;
    }

    public function removeProduit(Produit $produit): static
    {
        if ($this->produits->removeElement($produit)) {
            // set the owning side to null (unless already changed)
            if ($produit->getIdCatégorie() === $this) {
                $produit->setIdCatégorie(null);
            }
        }

        return $this;
    }

    





}
