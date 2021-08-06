<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\GarageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Bridge\Elasticsearch\DataProvider\Filter\OrderFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\NumericFilter;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ApiResource(
 * collectionOperations={
 *     "get",
 *     "post"={
 *              "security"="is_granted('ROLE_PROFESSIONNEL')"
 *          }
 *     },
 *     itemOperations={
 *     "get",
 *     "put"={
 *              "security"="is_granted('ROLE_ADMIN') or object.user == user"
 *          },
 *     "delete"={
 *              "security"="is_granted('ROLE_ADMIN') or object.user== user"
 *          }
 *     },
 *     normalizationContext={
 *          "groups"={"garage:get"}
 *     }
 * )
 * @ApiFilter(SearchFilter::class, properties={"nom"="exact", "telephone"="exact"})
 * @ApiFilter(OrderFilter::class, properties={"id"="asc"})
 * @ApiFilter(NumericFilter::class, properties={"user.id", "id"})
 *
 * @ORM\Entity(repositoryClass=GarageRepository::class)
 */
class Garage
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"garage:get", "annonce:get"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank(message = "Blanc interdit pour le nom")
     * @Assert\NotNull(message = "Not Null interdit pour le nom")
     * @Groups({"garage:get", "annonce:get"})
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=15)
     * @Assert\NotBlank(message = "Blanc interdit pour le telephone")
     * @Assert\NotNull(message = "not null interdit pour le telephone")
     * @Groups({"garage:get"})
     */
    private $telephone;

    /**
     * @ORM\ManyToOne(targetEntity=Ville::class, inversedBy="garages", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"garage:get"})
     */
    private $ville;

    /**
     * @ORM\OneToMany(targetEntity=Annonce::class, mappedBy="garage", cascade={"remove"})
     * @Groups({"garage:get"})
     */
    private $annonces;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="garages")
     * @ORM\JoinColumn(nullable=false)
     */
    public $user;

    public function __construct()
    {
        $this->annonces = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getVille(): ?Ville
    {
        return $this->ville;
    }

    public function setVille(?Ville $ville): self
    {
        $this->ville = $ville;

        return $this;
    }

    /**
     * @return Collection|Annonce[]
     */
    public function getAnnonces(): Collection
    {
        return $this->annonces;
    }

    public function addAnnonce(Annonce $annonce): self
    {
        if (!$this->annonces->contains($annonce)) {
            $this->annonces[] = $annonce;
            $annonce->setGarage($this);
        }

        return $this;
    }

    public function removeAnnonce(Annonce $annonce): self
    {
        if ($this->annonces->removeElement($annonce)) {
            // set the owning side to null (unless already changed)
            if ($annonce->getGarage() === $this) {
                $annonce->setGarage(null);
            }
        }

        return $this;
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
}
