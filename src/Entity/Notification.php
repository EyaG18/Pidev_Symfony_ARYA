<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Notification
 *
 * @ORM\Table(name="notification")
 * @ORM\Entity(repositoryClass=App\Repository\NotificationRepository::class)
 */
class Notification
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_n", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idN;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=25, nullable=false)
     */
    private $type;



    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime", nullable=false,options={"default"="CURRENT_TIMESTAMP"})
     */
    private $date;

    public function getIdN(): ?int
    {
        return $this->idN;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type)
    {
        $this->type = $type;

        return $this;
    }



    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date)
    {
        $this->date = $date;

        return $this;
    }


}