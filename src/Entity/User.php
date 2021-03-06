<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use PhpParser\Node\Expr\Cast\Bool_;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 * @Vich\Uploadable
 */
class User implements UserInterface, \Serializable
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @ORM\Column(type="string")
     */
    private $city;

    /**
     * @ORM\Column(type="string")
     */
    private $pseudo;

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     * @var string
     */
    private $image;

    /**
     * @Vich\UploadableField(mapping="user_images", fileNameProperty="image")
     * @var File
     */
    private $imageFile;

    /**
     * @ORM\OneToMany(targetEntity=IsInCommunity::class, mappedBy="user")
     */
    protected $communitys;

    /**
     * @ORM\OneToMany(targetEntity=Participation::class, mappedBy="user")
     */
    protected $event;

    /**
     * @ORM\OneToMany(targetEntity=Event::class, mappedBy="user", cascade={"persist"})
     */
    protected $eventsCreated;

    /**
     * @ORM\OneToMany(targetEntity=Friendship::class, mappedBy="first_user")
     */
    protected $friends;

    /**
     * @ORM\OneToMany(targetEntity=Friendship::class, mappedBy="second_user")
     */
    protected $friendsWithMe;

    /**
     * @ORM\OneToMany(targetEntity=Notification::class, mappedBy="user")
     */
    protected $notifications;

    /**
     * @ORM\OneToOne(targetEntity=Settings::class, cascade={"persist"})
     */
    protected $settings;

    /**
     * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="user")
     */
    private $comments;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
    }

    public function getNotifications()
    {
        $notifications = new ArrayCollection();
        foreach ($this->notifications as $notification) {
            if (!$notification->getSeen()) {
                $notifications->add($notification);
            }
        }
        return $notifications;
    }

    public function setCommunity($communitys)
    {
        $this->communitys = $communitys;
    }

    public function getCommunity()
    {
        return $this->communitys;
    }

    public function getEvent()
    {
        return $this->event;
    }

    public function getSettings()
    {
        return $this->settings;
    }

    public function setSettings($settings)
    {
        $this->settings = $settings;
    }

    public function getImageFile()
    {
        return $this->imageFile;
    }

    public function setImageFile(File $imageFile = null)
    {
        $this->imageFile = $imageFile;

        if ($imageFile) {
            // if 'updatedAt' is not defined in your entity, use another property
            $this->updatedAt = new \DateTime('now');
        }
    }

    public function setImage($image)
    {
        $this->image = $image;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): self
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getEventsCreated()
    {
        $eventsCreated = new ArrayCollection();
        foreach ($this->eventsCreated as $eventCreated) {
            if ($eventCreated->getDateSuppression() == null) {
                $eventsCreated->add($eventCreated);
            }
        }
        return $eventsCreated;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /** @see \Serializable::serialize() */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->email,
            $this->password,
            // see section on salt below
            // $this->salt,
        ));
    }

    /** @see \Serializable::unserialize() */
    public function unserialize($serialized)
    {
        list(
            $this->id,
            $this->email,
            $this->password,
            // see section on salt below
            // $this->salt
        ) = unserialize($serialized, array('allowed_classes' => false));
    }

    /**
     * Get the value of friendship
     */
    public function getFriendship()
    {
        $friendship = new ArrayCollection();
        foreach ($this->friends as $friends) {
            $friendship->add($friends);
        }
        foreach ($this->friendsWithMe as $friendsWithMe) {
            $friendship->add($friendsWithMe);
        }
        return $friendship;
    }

    public function isFriendWith($user): Bool
    {
        $vRetour = false;
        foreach ($this->getFriendship() as $friendship) {
            if ($friendship->getSecond_user() == $user || $friendship->getFirst_user() == $user) {
                if ($friendship->getValidate()) {
                    $vRetour = true;
                }
            }
        }

        return $vRetour;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setUser($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getUser() === $this) {
                $comment->setUser(null);
            }
        }

        return $this;
    }
}
