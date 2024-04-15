<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Panier;

/**
 * Commande
 *
 * @ORM\Table(name="commande", uniqueConstraints={@ORM\UniqueConstraint(name="Reference", columns={"Reference"})}, indexes={@ORM\Index(name="fk_id", columns={"id_user"}), @ORM\Index(name="fk_id_panier", columns={"Id_Panier"})})
 * @ORM\Entity(repositoryClass=App\Repository\CommandeRepository::class)
 */
class Commande
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_commande", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idCommande;

    /**
     * @var int|null
     *
     * @ORM\Column(name="Reference", type="integer", nullable=true)
     */
    private $reference;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="Date_com", type="date", nullable=false)
     */
    private $dateCom;

    /**
     * @var bool
     *
     * @ORM\Column(name="livrable", type="boolean", nullable=false)
     */
    private $livrable;

    /**
     * @var string
     *
     * @ORM\Column(name="Status", type="string", length=50, nullable=false, options={"default"="en_attente"})
     */
    private $status = 'en_attente';

    /**
     * 
     *
     * @ORM\ManyToOne(targetEntity="Panier")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="Id_Panier", referencedColumnName="Id_Panier")
     * })
     */
    private $idPanier;

    /**
     * 
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_user", referencedColumnName="id_user")
     * })
     */
    private $idUser;

    public function getIdCommande(): ?int
    {
        return $this->idCommande;
    }

    public function getReference(): ?int
    {
        return $this->reference;
    }

    public function setReference(?int $reference)
    {
        $this->reference = $reference;

        return $this;
    }

    public function getDateCom(): ?\DateTimeInterface
    {
        return $this->dateCom;
    }

    public function setDateCom(\DateTimeInterface $dateCom)
    {
        $this->dateCom = $dateCom;

        return $this;
    }

    public function isLivrable(): ?bool
    {
        return $this->livrable;
    }

    public function setLivrable(bool $livrable)
    {
        $this->livrable = $livrable;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status)
    {
        $this->status = $status;

        return $this;
    }

    public function getIdPanier(): ?Panier
    {
        return $this->idPanier;
    }

    public function setIdPanier(?Panier $idPanier)
    {
        $this->idPanier = $idPanier;

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


}