<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass=App\Repository\UserRepository::class)
 */
class User
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_user", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idUser;

    /**
     * @var string|null
     *
     * @ORM\Column(name="nomuser", type="string", length=255, nullable=true)
     */
    private $nomuser;

    /**
     * @var string|null
     *
     * @ORM\Column(name="prenomuser", type="string", length=255, nullable=true)
     */
    private $prenomuser;

    /**
     * @var string
     *
     * @ORM\Column(name="AdrUser", type="string", length=255, nullable=false)
     */
    private $adruser;

    /**
     * @var string
     *
     * @ORM\Column(name="EmailUsr", type="string", length=255, nullable=false)
     */
    private $emailusr;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255, nullable=false)
     */
    private $password;

    /**
     * @var int
     *
     * @ORM\Column(name="Numtel", type="integer", nullable=false)
     */
    private $numtel;

    /**
     * @var string|null
     *
     * @ORM\Column(name="Role", type="string", length=25, nullable=true)
     */
    private $role;

    public function getIdUser(): ?int
    {
        return $this->idUser;
    }

    public function getNomuser(): ?string
    {
        return $this->nomuser;
    }

    public function setNomuser(?string $nomuser)
    {
        $this->nomuser = $nomuser;

        return $this;
    }

    public function getPrenomuser(): ?string
    {
        return $this->prenomuser;
    }

    public function setPrenomuser(?string $prenomuser)
    {
        $this->prenomuser = $prenomuser;

        return $this;
    }

    public function getAdruser(): ?string
    {
        return $this->adruser;
    }

    public function setAdruser(string $adruser)
    {
        $this->adruser = $adruser;

        return $this;
    }

    public function getEmailusr(): ?string
    {
        return $this->emailusr;
    }

    public function setEmailusr(string $emailusr)
    {
        $this->emailusr = $emailusr;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password)
    {
        $this->password = $password;

        return $this;
    }

    public function getNumtel(): ?int
    {
        return $this->numtel;
    }

    public function setNumtel(int $numtel)
    {
        $this->numtel = $numtel;

        return $this;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(?string $role)
    {
        $this->role = $role;

        return $this;
    }


}