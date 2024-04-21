<?php

namespace App\Entity;
use App\Entity\Commande;
use App\Entity\User;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * Livraison
 *
 * @ORM\Table(name="livraison", uniqueConstraints={@ORM\UniqueConstraint(name="Reference", columns={"Reference"})}, indexes={@ORM\Index(name="fk_id_commande", columns={"id_commande"}), @ORM\Index(name="fk_id_user", columns={"id_user"})})
 * @ORM\Entity
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
     * @ORM\Column(name="Status_livraison", type="string", length=20, nullable=false)
     */
    private $statusLivraison = 'en attente';

 /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_livraison", type="date", nullable=false)
     * @Assert\GreaterThanOrEqual(
     *     "today +2 days",
     *     message="La date de livraison doit être au moins 2 jours après la date de commande."
     * )
     */
    private $dateLivraison;

    /**
     * @var float
     *
     * @ORM\Column(name="prix_livraison", type="float", precision=10, scale=0, nullable=false)
     */
    private $prixLivraison;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_user", referencedColumnName="id_user")
     * })
     */
    private $idUser;


    /**
     * @var Commande
     *
     * @ORM\ManyToOne(targetEntity="Commande")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_commande", referencedColumnName="id_commande")
     * })
     */
    private $idCommande;
      
         /**
     * @var int|null
     *
     * @ORM\Column(name="Reference", type="integer", nullable=true)
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

    public function setStatusLivraison(string $statusLivraison): self
    {
        $this->statusLivraison = $statusLivraison;

        return $this;
    }

    public function getDateLivraison(): ?\DateTimeInterface
    {
        return $this->dateLivraison;
    }

    public function setDateLivraison(\DateTimeInterface $dateLivraison): self
    {
        $this->dateLivraison = $dateLivraison;

        return $this;
    }

    public function getPrixLivraison(): ?float
    {
        return $this->prixLivraison;
    }

    public function setPrixLivraison(float $prixLivraison): self
    {
        $this->prixLivraison = $prixLivraison;

        return $this;
    }

    public function getIdUser(): ?User
    {
        return $this->idUser;
    }

    public function setIdUser(?User $idUser): self
    {
        $this->idUser = $idUser;

        return $this;
    }

    public function getIdCommande(): ?Commande
    {
        return $this->idCommande;
    }

    public function setIdCommande(?Commande $idCommande): self
    {
        $this->idCommande = $idCommande;

        return $this;
    }

    public function getReference(): ?int
    {
        return $this->reference;
    }

    public function setReference(?int $reference): self
    {
        $this->reference = $reference;

        return $this;
    }
}
