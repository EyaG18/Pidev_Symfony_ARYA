<?php

namespace App\Entity;
use App\Entity\User;
use App\Entity\Panier;
use App\Entity\Livraison;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Commande
 *
 * @ORM\Table(name="commande", uniqueConstraints={@ORM\UniqueConstraint(name="Reference", columns={"Reference"})}, indexes={@ORM\Index(name="fk_id", columns={"id_user"}), @ORM\Index(name="fk_id_panier", columns={"Id_Panier"})})
 * @ORM\Entity
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
     * @var float|null
     *
     * @ORM\Column(name="prix_total", type="float", nullable=true)
     */
    private $prixTotal;

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
     * @ORM\Column(name="Status", type="string", length=255, nullable=false)
     */
    private $status= 'en attente';

    /**
     * @var Panier
     *
     * @ORM\ManyToOne(targetEntity="Panier")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="Id_Panier", referencedColumnName="Id_Panier")
     * })
     */
    private $idPanier;

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
 * @ORM\ManyToOne(targetEntity="Panier", inversedBy="commandes")
 * @ORM\JoinColumn(name="Id_Panier", referencedColumnName="Id_Panier")
 */
private $panier;

public function getPanier(): ?Panier
{
    return $this->panier;
}

public function setPanier(?Panier $panier): self
{
    $this->panier = $panier;

    return $this;
}

    public function getIdCommande(): ?int
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

    public function getDateCom(): ?\DateTimeInterface
    {
        return $this->dateCom;
    }

    public function setDateCom(\DateTimeInterface $dateCom): self
    {
        $this->dateCom = $dateCom;

        return $this;
    }

    public function isLivrable(): ?bool
    {
        return $this->livrable;
    }

    public function setLivrable(bool $livrable): self
    {
        $this->livrable = $livrable;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getIdPanier(): ?Panier
    {
        return $this->idPanier;
    }

    public function setIdPanier(?Panier $idPanier): self
    {
        $this->idPanier = $idPanier;

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

    public function getPrixTotal(): ?float
    {
        return $this->prixTotal;
    }

    public function setPrixTotal(Panier $panier): self
    {
        // Calculez le prix total en multipliant la quantité de produit dans le panier par son prix individuel
        $totalPrice = $panier->getQuantiteparproduit() * $panier->getIdProduit()->getPrixp();
        $this->prixTotal = $totalPrice;
    
        return $this;
    }
    
    
    
   

}
