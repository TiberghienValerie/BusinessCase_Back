<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\VilleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Bridge\Elasticsearch\DataProvider\Filter\OrderFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\NumericFilter;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ApiResource(
 *  attributes={"order"={"nomVille"="ASC"}},
 *  collectionOperations={
 *      "get",
 *      "post"={
 *          "security"="is_granted('ROLE_PROFESSIONNEL')"
 *      }
 *  },
 *  itemOperations={
 *     "get",
 *     "put"={
 *          "security"="is_granted('ROLE_PROFESSIONNEL')"
 *      },
 *      "delete"={
 *          "security"="is_granted('ROLE_ADMIN')"
 *      }
 *  },
 *  normalizationContext={
 *      "groups"={"ville:get"}
 *  }
 * )
 * @ApiFilter(SearchFilter::class, properties={"codePostal"="exact", "nomVille"="exact"})
 * @ApiFilter(OrderFilter::class, properties={"nomVille"="ASC"})
 * @ApiFilter(NumericFilter::class, properties={"id"})
 * @ORM\Entity(repositoryClass=VilleRepository::class)
 */
class Ville
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"ville:get", "garage:get"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=5)
     * @Groups({"ville:get", "garage:get"})
     * @Assert\NotBlank(message = "Not blanc interdit pour le code postal")
     * @Assert\NotNull(message = "Not null interdit pour le code postal")
     * @Assert\Length(max=5,maxMessage="Your codePostal cannot be longer than {{ limit }} characters")
     */
    private $codePostal;

    /**
     * @ORM\Column(type="string", length=100)
     * @Groups({"ville:get", "garage:get"})
     * @Assert\NotBlank(message = "Blanc interdit pour le nom de la ville")
     * @Assert\NotNull(message = "Not null interdit pour le nom de la ville")
     * @Assert\Length(max=100,maxMessage="Your nomVille cannot be longer than {{ limit }} characters")
     */
    private $nomVille;

    /**
     * @ORM\Column(type="string", length=50)
     * @Groups({"ville:get", "garage:get"})
     * @Assert\NotBlank(message = "Blanc interdit pour l'adresse 1")
     * @Assert\NotNull(message = "Not null interdit pour l'adresse 2")
     * @Assert\Length(max=50,maxMessage="Your adresse1 cannot be longer than {{ limit }} characters")
     */
    private $adresse1;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     * @Groups({"ville:get", "garage:get"})
     */
    private $adresse2;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     * @Groups({"ville:get", "garage:get"})
     */
    private $adresse3;

    /**
     * @ORM\OneToMany(targetEntity=Garage::class, mappedBy="ville")
     */
    private $garages;

    public function __construct()
    {
        $this->garages = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCodePostal(): ?string
    {
        return $this->codePostal;
    }

    public function setCodePostal(string $codePostal): self
    {
        $this->codePostal = $codePostal;

        return $this;
    }

    public function getNomVille(): ?string
    {
        return $this->nomVille;
    }

    public function setNomVille(string $nomVille): self
    {
        $this->nomVille = $nomVille;

        return $this;
    }

    public function getAdresse1(): ?string
    {
        return $this->adresse1;
    }

    public function setAdresse1(string $adresse1): self
    {
        $this->adresse1 = $adresse1;

        return $this;
    }

    public function getAdresse2(): ?string
    {
        return $this->adresse2;
    }

    public function setAdresse2(?string $adresse2): self
    {
        $this->adresse2 = $adresse2;

        return $this;
    }

    public function getAdresse3(): ?string
    {
        return $this->adresse3;
    }

    public function setAdresse3(?string $adresse3): self
    {
        $this->adresse3 = $adresse3;

        return $this;
    }

    /**
     * @return Collection|Garage[]
     */
    public function getGarages(): Collection
    {
        return $this->garages;
    }

    public function addGarage(Garage $garage): self
    {
        if (!$this->garages->contains($garage)) {
            $this->garages[] = $garage;
            $garage->setVille($this);
        }

        return $this;
    }

    public function removeGarage(Garage $garage): self
    {
        if ($this->garages->removeElement($garage)) {
            // set the owning side to null (unless already changed)
            if ($garage->getVille() === $this) {
                $garage->setVille(null);
            }
        }

        return $this;
    }
}
