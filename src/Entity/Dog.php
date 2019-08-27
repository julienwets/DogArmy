<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\File\File;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints\Length;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @Vich\Uploadable
 * @ORM\Entity(repositoryClass="App\Repository\DogRepository")
 */
class Dog
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $gender;

    /**
     * @ORM\Column(type="datetime")
     */
    private $birthDate;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $details = [];

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $instagram;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $houseTrained;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Assert\Length(
     *      min = 20,
     *      minMessage = "Veuillez écrire une description d'au-moins 20 caractères",
     *      max = 500,
     *      maxMessage = "La description de votre chien ne peut pas dépasser 500 caractères"
     *  )
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     * @var string
     */
    private $image;

    /**
     * @Vich\UploadableField(mapping="dog_picture", fileNameProperty="image", size="imageSize")
     * @Assert\NotBlank(message="Veuillez enregistrer une photo de votre chien.")
     *
     * @var File
     */
    private $imageFile;

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
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="dogs")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Sitting", mappedBy="dogs")
     */
    private $sittings;

    public function __construct()
    {
        $this->sittings = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(string $gender): self
    {
        $this->gender = $gender;

        return $this;
    }

    public function getBirthDate(): ?\DateTimeInterface
    {
        return $this->birthDate;
    }

    public function setBirthDate(\DateTimeInterface $birthDate): self
    {
        $this->birthDate = $birthDate;

        return $this;
    }

    public function getDetails(): ?array
    {
        return $this->details;
    }

    public function setDetails(?array $details): self
    {
        $this->details = $details;

        return $this;
    }

    public function getInstagram(): ?string
    {
        return $this->instagram;
    }

    public function setInstagram(?string $instagram): self
    {
        $this->instagram = $instagram;

        return $this;
    }

    public function getHouseTrained(): ?string
    {
        return $this->houseTrained;
    }

    public function setHouseTrained(string $houseTrained): self
    {
        $this->houseTrained = $houseTrained;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function setImageFile(File $image = null)
    {
        $this->imageFile = $image;

        // VERY IMPORTANT:
        // It is required that at least one field changes if you are using Doctrine,
        // otherwise the event listeners won't be called and the file is lost
        if ($image) {
            // if 'updatedAt' is not defined in your entity, use another property
            $this->updatedAt = new \DateTime('now');
        }
    }

    public function getImageFile()
    {
        return $this->imageFile;
    }

    public function setImage($image)
    {
        $this->image = $image;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function setImageSize(?int $imageSize): void
    {
        $this->imageSize = $imageSize;
    }

    public function getImageSize(): ?int
    {
        return $this->imageSize;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

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
            $sitting->addDog($this);
        }

        return $this;
    }

    public function removeSitting(Sitting $sitting): self
    {
        if ($this->sittings->contains($sitting)) {
            $this->sittings->removeElement($sitting);
            $sitting->removeDog($this);
        }

        return $this;
    }
}
