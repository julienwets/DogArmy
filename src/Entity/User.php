<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Serializable;

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
     */
    private $email;

    /**
     * @ORM\Column(type="array")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     * 
     * @Vich\UploadableField(mapping="user_picture", fileNameProperty="imageName", size="imageSize")
     * 
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

    function setPassword($password)
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

    function addRole($role)
    {
        $this->roles[] = $role;
    }


    public function eraseCredentials()
    { }

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
        ) = unserialize($serialized);
    }

    function getId()
    {
        return $this->id;
    }

    function getEmail()
    {
        return $this->email;
    }

    function getPlainPassword()
    {
        return $this->plainPassword;
    }

    function setId($id)
    {
        $this->id = $id;
    }

    function setEmail($email)
    {
        $this->email = $email;
    }

    function setPlainPassword($plainPassword)
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

    function getDuringWork()
    {
        return $this->duringWork;
    }

    function setDuringWork($duringWork)
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

    public function getZipCode(): ?string
    {
        return $this->zipCode;
    }

    public function setZipCode(string $zipCode): self
    {
        $this->zipCode = $zipCode;

        return $this;
    }
}
