<?php

namespace App\Entity;

use App\Repository\IsInRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=IsInRepository::class)
 */
class IsIn
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $idUser;

    /**
     * @ORM\Column(type="integer")
     */
    private $idCommunity;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdUser(): ?int
    {
        return $this->idUser;
    }

    public function setIdUser(int $idUser): self
    {
        $this->idUser = $idUser;

        return $this;
    }

    public function getIdCommunity(): ?int
    {
        return $this->idCommunity;
    }

    public function setIdCommunity(int $idCommunity): self
    {
        $this->idCommunity = $idCommunity;

        return $this;
    }
}
