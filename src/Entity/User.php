<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @Vich\Uploadable
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 */
class User implements UserInterface
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
     * @ORM\Column(type="json")
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
     * @ORM\Column(type="string", length=255)
     *
     * @var string
     */
    private $imageName;

    /**
     * @ORM\Column(type="integer")
     *
     * @var integer
     */
    private $imageSize;

    /**
     * @ORM\Column(type="datetime")
     *
     * @var \DateTime
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $homeType = [];

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
     * @ORM\Column(type="array", nullable=true)
     */
    private $duringWork = [];

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $otherPreferences = [];


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

    public function getHomeType(): ?array
    {
        return $this->homeType;
    }

    public function setHomeType(?array $homeType): self
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

    public function getDuringWork(): ?array
    {
        return $this->duringWork;
    }

    public function setDuringWork(?array $duringWork): self
    {
        $this->duringWork = $duringWork;

        return $this;
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
}
