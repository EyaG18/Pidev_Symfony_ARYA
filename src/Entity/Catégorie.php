<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Catégorie
 *
 * @ORM\Table(name="catégorie", uniqueConstraints={@ORM\UniqueConstraint(name="UC_NomCatégorie", columns={"NomCatégorie"})})
 * @ORM\Entity(repositoryClass=App\Repository\CategorieRepository::class)
 */
class Catégorie
{
    /**
     * @var int
     *
     * @ORM\Column(name="Id_Catégorie", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idCatégorie;

    /**
     * @var string
     *
     * @ORM\Column(name="NomCatégorie", type="string", length=20, nullable=false)
     */
    private $nomcatégorie;

    public function getIdCatégorie(): ?int
    {
        return $this->idCatégorie;
    }

    public function getNomcatégorie(): ?string
    {
        return $this->nomcatégorie;
    }

    public function setNomcatégorie(string $nomcatégorie)
    {
        $this->nomcatégorie = $nomcatégorie;

        return $this;
    }


}