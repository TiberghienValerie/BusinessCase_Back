<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\AnnonceRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=AnnonceRepository::class)
 */
class Annonce
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $DateAnnonce;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $refAnnonce;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $titre;

    /**
     * @ORM\Column(type="string", length=250)
     */
    private $descriptionCourte;

    /**
     * @ORM\Column(type="text")
     */
    private $descriptionLongue;

    /**
     * @ORM\Column(type="integer")
     */
    private $anneeCirculation;

    /**
     * @ORM\Column(type="float")
     */
    private $kilometrage;

    /**
     * @ORM\Column(type="float")
     */
    private $prix;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateAnnonce(): ?\DateTimeInterface
    {
        return $this->DateAnnonce;
    }

    public function setDateAnnonce(\DateTimeInterface $DateAnnonce): self
    {
        $this->DateAnnonce = $DateAnnonce;

        return $this;
    }

    public function getRefAnnonce(): ?string
    {
        return $this->refAnnonce;
    }

    public function setRefAnnonce(string $refAnnonce): self
    {
        $this->refAnnonce = $refAnnonce;

        return $this;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getDescriptionCourte(): ?string
    {
        return $this->descriptionCourte;
    }

    public function setDescriptionCourte(string $descriptionCourte): self
    {
        $this->descriptionCourte = $descriptionCourte;

        return $this;
    }

    public function getDescriptionLongue(): ?string
    {
        return $this->descriptionLongue;
    }

    public function setDescriptionLongue(string $descriptionLongue): self
    {
        $this->descriptionLongue = $descriptionLongue;

        return $this;
    }

    public function getAnneeCirculation(): ?int
    {
        return $this->anneeCirculation;
    }

    public function setAnneeCirculation(int $anneeCirculation): self
    {
        $this->anneeCirculation = $anneeCirculation;

        return $this;
    }

    public function getKilometrage(): ?float
    {
        return $this->kilometrage;
    }

    public function setKilometrage(float $kilometrage): self
    {
        $this->kilometrage = $kilometrage;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): self
    {
        $this->prix = $prix;

        return $this;
    }
}
