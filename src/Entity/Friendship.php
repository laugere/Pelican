<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="friendship",
    uniqueConstraints={
        @ORM\UniqueConstraint(name="user_friendship_unique", columns={"first_user_id", "second_user_id"})
    }
  )
 */
class Friendship
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
     * @ORM\Column(type="boolean")
     */
    protected $validate;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="second_user")
     */
    protected $first_user;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="first_user")
     */
    protected $second_user;

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
     * @param mixed $first_user
     */
    public function setFirst_user($first_user): void
    {
        $this->first_user = $first_user;
    }

    /**
     * @return mixed
     */
    public function getFirst_user()
    {
        return $this->first_user;
    }

    /**
     * @param mixed $second_user
     */
    public function setSecond_user($second_user): void
    {
        $this->second_user = $second_user;
    }

    /**
     * @return mixed
     */
    public function getSecond_user()
    {
        return $this->second_user;
    }

    /**
     * @param mixed $validate
     */
    public function setValidate($validate): void
    {
        $this->validate = $validate;
    }

    /**
     * @return mixed
     */
    public function getValidate()
    {
        return $this->validate;
    }
}
