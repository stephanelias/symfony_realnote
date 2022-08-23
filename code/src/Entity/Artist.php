<?php

namespace App\Entity;

use App\Repository\ArtistRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ArtistRepository::class)
 */
class Artist
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\OneToOne(targetEntity=Photo::class, cascade={"persist", "remove"})
     */
    private $photo;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $real_name;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $birth_date;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $summary;

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

    public function getPhoto(): ?Photo
    {
        return $this->photo;
    }

    public function setPhoto(?Photo $photo): self
    {
        $this->photo = $photo;

        return $this;
    }

    public function getRealName(): ?string
    {
        return $this->real_name;
    }

    public function setRealName(?string $real_name): self
    {
        $this->real_name = $real_name;

        return $this;
    }

    public function getBirthDate(): ?\DateTimeInterface
    {
        return $this->birth_date;
    }

    public function setBirthDate(?\DateTimeInterface $birth_date): self
    {
        $this->birth_date = $birth_date;

        return $this;
    }

    public function getSummary(): ?string
    {
        return $this->summary;
    }

    public function setSummary(?string $summary): self
    {
        $this->summary = $summary;

        return $this;
    }

}
