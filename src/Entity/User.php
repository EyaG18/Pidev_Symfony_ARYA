<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User
{
    /**
     * 
     *
     * @ORM\Column(name="id_user", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id_user;

    /**
     * 
     *
     * @ORM\Column(name="nomuser", type="string", length=255, nullable=true)
     */
    private $nomuser;

    /**
     * 
     *
     * @ORM\Column(name="prenomuser", type="string", length=255, nullable=true)
     */
    private $prenomuser;

    /**
     * 
     *
     * @ORM\Column(name="AdrUser", type="string", length=255, nullable=false)
     */
    private $AdrUser;

    /**
     * 
     *
     * @ORM\Column(name="EmailUsr", type="string", length=255, nullable=false)
     */
    private $EmailUsr;

    /**
     * 
     *
     * @ORM\Column(name="password", type="string", length=255, nullable=false)
     */
    private $password;

    /**
     * 
     *
     * @ORM\Column(name="Numtel", type="integer", nullable=false)
     */
    private $Numtel;

    /**
     * 
     *
     * @ORM\Column(name="Role", type="string", length=30, nullable=true)
     */
    private $Role;

    public function getIdUser(): ?int
    {
        return $this->id_user;
    }

    public function getNomuser(): ?string
    {
        return $this->nomuser;
    }

    public function setNomuser(?string $nomuser): self
    {
        $this->nomuser = $nomuser;

        return $this;
    }

    public function getPrenomuser(): ?string
    {
        return $this->prenomuser;
    }

    public function setPrenomuser(?string $prenomuser): self
    {
        $this->prenomuser = $prenomuser;

        return $this;
    }

    public function getAdruser(): ?string
    {
        return $this->AdrUser;
    }

    public function setAdruser(string $adruser): self
    {
        $this->AdrUser = $adruser;

        return $this;
    }

    public function getEmailusr(): ?string
    {
        return $this->EmailUsr;
    }

    public function setEmailusr(string $emailusr): self
    {
        $this->EmailUsr = $emailusr;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getNumtel(): ?int
    {
        return $this->Numtel;
    }

    public function setNumtel(int $numtel): self
    {
        $this->Numtel = $numtel;

        return $this;
    }

    public function getRole(): ?string
    {
        return $this->Role;
    }

    public function setRole(?string $role): self
    {
        $this->Role = $role;

        return $this;
    }


}
