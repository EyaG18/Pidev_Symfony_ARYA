<?php

namespace App\Entity;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use PhpParser\Node\Stmt\Static_;

/**
 * Offre
 *
 * @ORM\Table(name="offre", indexes={@ORM\Index(name="fk_produit", columns={"Id_Produit"})})
 * @ORM\Entity
 */
class Offre
{
    /**
     * @var int
     *
     * @ORM\Column(name="idOffre", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idoffre;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_debut", type="date", nullable=false)
     */
    private $dateDebut;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_fin", type="date", nullable=false)
     */
    private $dateFin;

    /**
     * @var string
     *
     * @ORM\Column(name="reduction", type="string", length=255, nullable=false)
     */
    private $reduction;

    /**
     * @var string
     *
     * @ORM\Column(name="titre_offre", type="string", length=255, nullable=false)
     */
    private $titreOffre;

    /**
     * @var \Produit
     *
     * @ORM\ManyToOne(targetEntity="Produit")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="Id_Produit", referencedColumnName="Id_Produit")
     * })
     */
    private $idProduit;

    public function getIdoffre(): ?int
    {
        return $this->idoffre;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->dateDebut;
    }

    public function setDateDebut(\DateTimeInterface $dateDebut): static
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->dateFin;
    }

    public function setDateFin(\DateTimeInterface $dateFin): static
    {
        $this->dateFin = $dateFin;

        return $this;
    }

    public function getReduction(): ?string
    {
        return $this->reduction;
    }

    public function setReduction(string $reduction): static{
        $this->reduction = $reduction;

        return $this;
    }

    public function getTitreOffre(): ?string
    {
        return $this->titreOffre;
    }

    public function setTitreOffre(string $titreOffre): static
    {
        $this->titreOffre = $titreOffre;

        return $this;
    }

    public function getIdProduit(): ?Produit
    {
        return $this->idProduit;
    }

    public function setIdProduit(?Produit $idProduit): static
    {
        $this->idProduit = $idProduit;

        return $this;
    }


}
