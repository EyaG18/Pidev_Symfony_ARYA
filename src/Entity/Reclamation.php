<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Reclamation
 *
 * @ORM\Table(name="reclamation", indexes={@ORM\Index(name="fk_reclamation", columns={"id_client"})})
 * @ORM\Entity(repositoryClass=App\Repository\ReclamationRepository::class)
 */
class Reclamation
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_reclamation", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idReclamation;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255, nullable=false)
     */
    private $description;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_reclamation", type="datetime", nullable=false, options={"default"="CURRENT_TIMESTAMP"})
     *  [Assert\NotBlank(message:"email ne peut pas être vide.")]
     */
    private $dateReclamation;



    /**
     * @var string|null
     *
     * @ORM\Column(name="statu_reclamation", type="string", length=25, nullable=true, options={"default"="en attente"})
     */
    private $statuReclamation = 'en attente';

    /**
     * @var string|null
     *
     * @ORM\Column(name="type_reclamation", type="string", length=50, nullable=true)
     */
    private $typeReclamation;

    /**
     * 
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_client", referencedColumnName="id_user")
     * })
     */
    private $idClient;

    public function getIdReclamation()
    {
        return $this->idReclamation;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    public function getDateReclamation()
    {
        return $this->dateReclamation;
    }

    public function setDateReclamation($dateReclamation)
    {
        $this->dateReclamation = $dateReclamation;

        return $this;
    }

    public function getStatuReclamation()
    {
        return $this->statuReclamation;
    }

    public function setStatuReclamation($statuReclamation)
    {
        $this->statuReclamation = $statuReclamation;

        return $this;
    }

    public function getTypeReclamation()
    {
        return $this->typeReclamation;
    }

    public function setTypeReclamation($typeReclamation)
    {
        $this->typeReclamation = $typeReclamation;

        return $this;
    }

    public function getIdClient()
    {
        return $this->idClient;
    }

    public function setIdClient(?User $user)
    {
        $this->idClient = $user;

        return $this;
    }


}