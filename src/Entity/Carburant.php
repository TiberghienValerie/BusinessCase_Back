<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\CarburantRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=CarburantRepository::class)
 */
class Carburant
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $NomCarburant;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomCarburant(): ?string
    {
        return $this->NomCarburant;
    }

    public function setNomCarburant(string $NomCarburant): self
    {
        $this->NomCarburant = $NomCarburant;

        return $this;
    }
}
