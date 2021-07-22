<?php

namespace App\Entity;

use App\Repository\PhotoRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PhotoRepository::class)
 */
class Photo
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
    private $nomPhotos;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $pathPhotos;

    /**
     * @ORM\Column(type="integer")
     */
    private $ordre;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomPhotos(): ?string
    {
        return $this->nomPhotos;
    }

    public function setNomPhotos(string $nomPhotos): self
    {
        $this->nomPhotos = $nomPhotos;

        return $this;
    }

    public function getPathPhotos(): ?string
    {
        return $this->pathPhotos;
    }

    public function setPathPhotos(string $pathPhotos): self
    {
        $this->pathPhotos = $pathPhotos;

        return $this;
    }

    public function getOrdre(): ?int
    {
        return $this->ordre;
    }

    public function setOrdre(int $ordre): self
    {
        $this->ordre = $ordre;

        return $this;
    }
}
