<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Livraison
 *
 * @ORM\Table(name="livraison", uniqueConstraints={@ORM\UniqueConstraint(name="Reference", columns={"Reference"})}, indexes={@ORM\Index(name="fk_id_commande", columns={"id_commande"}), @ORM\Index(name="fk_id_user", columns={"id_user"})})
 * @ORM\Entity(repositoryClass=App\Repository\LivraisonRepository::class)
 */
class Livraison
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_livraison", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idLivraison;

    /**
     * @var string
     *
     * @ORM\Column(name="Status_livraison", type="string", length=25, nullable=false, options={"default"="en_attente"})
     */
    private $statusLivraison = 'en_attente';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_livraison", type="date", nullable=false)
     */
    private $dateLivraison;

    /**
     * @var float
     *
     * @ORM\Column(name="prix_livraison", type="float", precision=10, scale=0, nullable=false)
     */
    private $prixLivraison;

    /**
     * 
     *
     * @ORM\ManyToOne(targetEntity="Commande")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_commande", referencedColumnName="id_commande")
     * })
     */
    private $idCommande;

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
     * @ORM\ManyToOne(targetEntity="Commande")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="Reference", referencedColumnName="Reference")
     * })
     */
    private $reference;

    public function getIdLivraison(): ?int
    {
        return $this->idLivraison;
    }

    public function getStatusLivraison(): ?string
    {
        return $this->statusLivraison;
    }

    public function setStatusLivraison(string $statusLivraison)
    {
        $this->statusLivraison = $statusLivraison;

        return $this;
    }

    public function getDateLivraison(): ?\DateTimeInterface
    {
        return $this->dateLivraison;
    }

    public function setDateLivraison(\DateTimeInterface $dateLivraison)
    {
        $this->dateLivraison = $dateLivraison;

        return $this;
    }

    public function getPrixLivraison(): ?float
    {
        return $this->prixLivraison;
    }

    public function setPrixLivraison(float $prixLivraison)
    {
        $this->prixLivraison = $prixLivraison;

        return $this;
    }

    public function getIdCommande(): ?Commande
    {
        return $this->idCommande;
    }

    public function setIdCommande(?Commande $idCommande)
    {
        $this->idCommande = $idCommande;

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

    public function getReference(): ?Commande
    {
        return $this->reference;
    }

    public function setReference(?Commande $reference)
    {
        $this->reference = $reference;

        return $this;
    }


}