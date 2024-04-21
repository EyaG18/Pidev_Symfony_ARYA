<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\Repository\UserRepository;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity
 */
#[ORM\Entity(repositoryClass:UserRepository::class)]
class User implements UserInterface
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

    /**
     * @var string|null
     *
     * @ORM\Column(name="image", type="string", length=255, nullable=true)
     */
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
    public function getUsername(): ?string
    {
        // You can return any unique identifier for the user here,
        // such as email or username.
        return $this->emailusr;
    }

    public function getRoles(): array
    {
        // Return an array of roles for the user.
        // This method should return at least one role.
       
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_'.$this->role;
        return array_unique($roles);
    }

    public function getSalt(): ?string
    {
        // This method is not needed if you're not using
        // legacy password encryption algorithms.
        return null;
    }

    public function eraseCredentials(): void
    {
        // If you store any sensitive information in plain text
        // or other readable formats, erase it here.
        // This method is called after the user's password has been used.
    }
    
    public function getUserIdentifier(): ?string
    {
        // You can return any unique identifier for the user here,
        // such as email or username.
        return $this->emailusr;
    }
}
