<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="isincommunity",
    uniqueConstraints={
        @ORM\UniqueConstraint(name="user_participation_unique", columns={"user_id", "community_id"})
    }
  )
 */
class IsInCommunity
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $date;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     */
    protected $user;

    /**
     * @ORM\ManyToOne(targetEntity=Community::class, inversedBy="isincommunity")
     */
    protected $community;

    /**
     * @param mixed $date
     */
    public function setDate($date): void
    {
        $this->date = $date;
    }

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user): void
    {
        $this->user = $user;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $community
     */
    public function setCommunity($community): void
    {
        $this->community = $community;
    }

    /**
     * @return mixed
     */
    public function getCommunity()
    {
        return $this->community;
    }
}
