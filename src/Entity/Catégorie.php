<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\Repository\CategoryRepository;


/**
 * Catégorie
 *
 * @ORM\Table(name="catégorie", uniqueConstraints={@ORM\UniqueConstraint(name="UC_NomCategorie", columns={"NomCategorie"})})
 * @ORM\Entity(repositoryClass="App\Repository\CategoryRepository")
 */

 class Catégorie
{
    /**
     * 
     *
     * @ORM\Column(name="Id_Categorie", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
 
   
   public $Id_Categorie;






    /**
     * 
     *
     * @ORM\Column(name="NomCategorie", type="string", length=20, nullable=false)
     */

   

    public $NomCategorie;

    public function getIdCategorie(): ?int
    {
        return $this->Id_Categorie;
    }

    public function getNomcategorie(): ?string
    {
        return $this->NomCategorie;
    }

    public function setNomcategorie(string $NomCategorie): self
    {
        $this->NomCategorie = $NomCategorie;

        return $this;
    }

    
     public function toStringCategory(): ?string
     {
         return $this->getNomcategorie();
     }
     
    





}
