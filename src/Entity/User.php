<?php

namespace App\Entity;

use Serializable;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\File\File;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @Vich\Uploadable
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity(fields={"email"}, message="Un compte possédant cette adresse e-mail existe déja")
 */
class User implements UserInterface, Serializable
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Assert\Email(
     *     message = "L'adresse e-mail '{{ value }}' n'est pas valide.",
     * )
     */
    private $email;

    /**
     * @ORM\Column(type="array")
     */
    private $roles = [];

    /**
     * @var string
     * @ORM\Column(type="string")
     * @Assert\Length(
     *      min = 6,
     *      minMessage = "Votre mot de passe doit contenir au-moins 6 caractères",
     *      max = 25,
     *      maxMessage = "Votre mot de passe ne peut pas comporter plus de 25 caractères"
     *  )
     * @Assert\NotCompromisedPassword(
     *      message = "haveibeenpwned.com nous indique que votre mot de passe a déjà été compromis ! Veuillez en utiliser un autre",
     *      skipOnError = true  
     *)
     */
    private $password;

    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     *
     * @Assert\File(
     *     maxSize = "2M",
     *     maxSizeMessage ="Votre image ne peut pas dépasser 2Mo",
     *     mimeTypes = {"image/png", "image/jpeg"},
     *     mimeTypesMessage = "Votre image doit être au format .jpg ou .png"
     *      )
     * @Vich\UploadableField(mapping="user_picture", fileNameProperty="imageName", size="imageSize")
     * @var File
     */
    private $imageFile;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     * @var string
     */
    private $imageName;

    /**
     * @ORM\Column(type="integer", nullable=true)
     *
     * @var integer
     */
    private $imageSize;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @var \DateTime
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $homeType;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $availableOn = [];

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $dogSize = [];

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $homeDetails = [];

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $duringWork;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $otherPreferences = [];

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Dog", mappedBy="user")
     */
    private $dogs;

    /**
     * @ORM\Column(type="string")
     */
    private $zipCode;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Sitting", mappedBy="user", orphanRemoval=true)
     */
    private $sittings;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Message", mappedBy="user", orphanRemoval=true)
     */
    private $messages;

    /**
     * @ORM\Column(type="boolean")
     */
    private $needsHelp = false;

    /**
     * @ORM\Column(type="string", length=25)
     * @Assert\Length(
     *      min = 2,
     *      minMessage = "Votre prénom doit contenir au-moins 2 caractères",
     *      max = 25,
     *      maxMessage = "Votre prénom ne peut pas comporter plus de 25 caractères"
     *  )
     */
    private $firstname;

    public function __toString()
    {
        return (string) $this->getEmail();
    }

    public function __construct()
    {
        // may not be needed, see section on salt below
        // $this->salt = md5(uniqid('', true));
        $this->roles = ['ROLE_USER'];
        $this->organizers = new ArrayCollection();
        $this->sittings = new ArrayCollection();
        $this->messages = new ArrayCollection();
    }

    public function getUsername()
    {
        return $this->email;
    }

    public function getSalt()
    {
        // you *may* need a real salt depending on your encoder
        // see section on salt below
        return null;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    // modifier la méthode getRoles
    public function getRoles()
    {
        return $this->roles;
    }

    public function setRoles(array $roles)
    {
        if (!in_array('ROLE_USER', $roles)) {
            $roles[] = 'ROLE_USER';
        }

        $this->roles = $roles;
        return $this;
    }

    public function addRole($role)
    {
        $this->roles[] = $role;
    }

    public function eraseCredentials()
    {}

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
            $this->password
            // see section on salt below
            // $this->salt
        ) = unserialize($serialized);
    }

    public function getId()
    {
        return $this->id;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function setPlainPassword($plainPassword)
    {
        $this->plainPassword = $plainPassword;
    }

    public function setImageFile(?File $imageFile = null): void
    {
        $this->imageFile = $imageFile;

        if (null !== $imageFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function setImageName(?string $imageName): void
    {
        $this->imageName = $imageName;
    }

    public function getImageName(): ?string
    {
        return $this->imageName;
    }

    public function setImageSize(?int $imageSize): void
    {
        $this->imageSize = $imageSize;
    }

    public function getImageSize(): ?int
    {
        return $this->imageSize;
    }

    public function getHomeType()
    {
        return $this->homeType;
    }

    public function setHomeType($homeType)
    {
        $this->homeType = $homeType;

        return $this;
    }

    public function getAvailableOn(): ?array
    {
        return $this->availableOn;
    }

    public function setAvailableOn(?array $availableOn): self
    {
        $this->availableOn = $availableOn;

        return $this;
    }

    public function getDogSize(): ?array
    {
        return $this->dogSize;
    }

    public function setDogSize(?array $dogSize): self
    {
        $this->dogSize = $dogSize;

        return $this;
    }

    public function getHomeDetails(): ?array
    {
        return $this->homeDetails;
    }

    public function setHomeDetails(?array $homeDetails): self
    {
        $this->homeDetails = $homeDetails;

        return $this;
    }

    public function getDuringWork()
    {
        return $this->duringWork;
    }

    public function setDuringWork($duringWork)
    {
        $this->duringWork = $duringWork;
    }

    public function getOtherPreferences(): ?array
    {
        return $this->otherPreferences;
    }

    public function setOtherPreferences(?array $otherPreferences): self
    {
        $this->otherPreferences = $otherPreferences;

        return $this;
    }

    /**
     * @return Collection|Dog[]
     */
    public function getDogs(): Collection
    {
        return $this->dogs;
    }

    public function addDog(Dog $dog): self
    {
        if (!$this->dogs->contains($dog)) {
            $this->dogs[] = $dog;
            $dog->setUser($this);
        }

        return $this;
    }

    public function removeDog(Dog $dog): self
    {
        if ($this->dogs->contains($dog)) {
            $this->dogs->removeElement($dog);
            // set the owning side to null (unless already changed)
            if ($dog->getUser() === $this) {
                $dog->setUser(null);
            }
        }

        return $this;
    }

    public function hasDogs()
    {
        if ($this->dogs->isEmpty()) {
            return true;
        } else {
            return false;
        }
    }

    public function getZipCode(): ?string
    {
        return $this->zipCode;
    }

    public function setZipCode(string $zipCode): self
    {
        $this->zipCode = $zipCode;

        return $this;
    }

    /**
     * @return Collection|Sitting[]
     */
    public function getSittings(): Collection
    {
        return $this->sittings;
    }

    public function addSitting(Sitting $sitting): self
    {
        if (!$this->sittings->contains($sitting)) {
            $this->sittings[] = $sitting;
            $sitting->setUser($this);
        }

        return $this;
    }

    public function removeSitting(Sitting $sitting): self
    {
        if ($this->sittings->contains($sitting)) {
            $this->sittings->removeElement($sitting);
            // set the owning side to null (unless already changed)
            if ($sitting->getUser() === $this) {
                $sitting->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Message[]
     */
    public function getMessages(): Collection
    {
        return $this->messages;
    }

    public function addMessage(Message $message): self
    {
        if (!$this->messages->contains($message)) {
            $this->messages[] = $message;
            $message->setUser($this);
        }

        return $this;
    }

    public function removeMessage(Message $message): self
    {
        if ($this->messages->contains($message)) {
            $this->messages->removeElement($message);
            // set the owning side to null (unless already changed)
            if ($message->getUser() === $this) {
                $message->setUser(null);
            }
        }

        return $this;
    }

    public function getNeedsHelp(): ?bool
    {
        return $this->needsHelp;
    }

    public function setNeedsHelp(bool $needsHelp): self
    {
        $this->needsHelp = $needsHelp;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }
}
