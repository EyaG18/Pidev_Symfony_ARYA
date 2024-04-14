<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\Repository\UserRepository;
/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity
 */
#[ORM\Entity(repositoryClass:UserRepository::class)]
class User
{
    
    /**
     * @var int
     *
     * @ORM\Column(name="id_user", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    #[ORM\Column(name: "id_user", type: "integer", nullable: false)]
    private $idUser;

    

    /**
     * @var string|null
     *
     * @ORM\Column(name="nomuser", type="string", length=255, nullable=true)
     */
    #[ORM\Column(name: "nomuser", type: "string", length: 255, nullable: true)]
    #[Assert\NotBlank]
    #[Assert\Regex(pattern: "/^\p{L}+$/u", message: "The name cannot contain numbers.")]
    #[Assert\Length(min: 3)]
    private $nomuser;

    
    /**
     * @var string|null
     *
     * @ORM\Column(name="prenomuser", type="string", length=255, nullable=true)
     */
    #[ORM\Column(name: "prenomuser", type: "string", length: 255, nullable: true)]
    #[Assert\NotBlank]
    #[Assert\Regex(pattern: "/^\p{L}+$/u", message: "The last name cannot contain numbers.")]
    #[Assert\Length(min: 3)]
    private $prenomuser;

    /**
     * @var string
     *
     * @ORM\Column(name="AdrUser", type="string", length=255, nullable=false)
     */
    #[ORM\Column(name: "AdrUser", type: "string", length: 255, nullable: false)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 5)]
    private $adruser;

    /**
     * @var string
     *
     * @ORM\Column(name="EmailUsr", type="string", length=255, nullable=false)
     */
    #[ORM\Column(name: "EmailUsr", type: "string", length: 255, nullable: false)]
    #[Assert\NotBlank]
    #[Assert\Email]
    private $emailusr;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255, nullable=false)
     */
    #[ORM\Column(name: "password", type: "string", length: 255, nullable: false)]
    #[Assert\NotBlank]
    #[Assert\Regex(
        pattern: "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_])[A-Za-z\d\W_]{8,}$/",
        message: "The password must contain at least one uppercase letter, one lowercase letter, one number, one special character, and be at least 8 characters long."
    )]
    private $password;

    
    /**
     * @var int
     *
     * @ORM\Column(name="Numtel", type="integer", nullable=false)
     */
    #[ORM\Column(name: "Numtel", type: "string", length: 255, nullable: false)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 8)]
    private $numtel;

    
    /**
     * @var string|null
     *
     * @ORM\Column(name="Role", type="string", length=30, nullable=true)
     */
    #[ORM\Column(name: "Role", type: "string", length: 0, nullable: true)]
    private $role;

    #[ORM\Column(name: "image", type: "string", length: 255, nullable: true)]
    private $image;

    public function getIdUser(): ?int
    {
        return $this->idUser;
    }

    public function getNomuser(): ?string
    {
        return $this->nomuser;
    }

    public function setNomuser(?string $nomuser): static
    {
        $this->nomuser = $nomuser;

        return $this;
    }

    public function getPrenomuser(): ?string
    {
        return $this->prenomuser;
    }

    public function setPrenomuser(?string $prenomuser): static
    {
        $this->prenomuser = $prenomuser;

        return $this;
    }

    public function getAdruser(): ?string
    {
        return $this->adruser;
    }

    public function setAdruser(string $adruser): static
    {
        $this->adruser = $adruser;

        return $this;
    }

    public function getEmailusr(): ?string
    {
        return $this->emailusr;
    }

    public function setEmailusr(string $emailusr): static
    {
        $this->emailusr = $emailusr;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getNumtel(): ?int
    {
        return $this->numtel;
    }

    public function setNumtel(int $numtel): static
    {
        $this->numtel = $numtel;

        return $this;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(?string $role): static
    {
        $this->role = $role;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): static
    {
        $this->image = $image;

        return $this;
    }
}
