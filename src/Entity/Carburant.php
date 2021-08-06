<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\CarburantRepository;
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
 *     collectionOperations={
 *     "get",
 *     "post"={
 *              "security"="is_granted('ROLE_ADMIN')"
 *          }
 *     },
 *     itemOperations={
 *     "get",
 *     "put"={
 *              "security"="is_granted('ROLE_ADMIN')"
 *          },
 *     "delete"={
 *              "security"="is_granted('ROLE_ADMIN')"
 *          }
 *     },
 *     normalizationContext={
 *          "groups"={"carburant:get"}
 *     }
 * )
 * @ApiFilter(SearchFilter::class, properties={"NomCarburant"="exact"})
 * @ApiFilter(OrderFilter::class, properties={"NomCarburant"="asc"})
 * @ApiFilter(NumericFilter::class, properties={"id"})
 * @ORM\Entity(repositoryClass=CarburantRepository::class)
 */
class Carburant
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"carburant:get", "annonce:get"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\NotBlank(message = "Blanc interdit pour le nom du carburant")
     * @Assert\NotNull(message = "Not null interdit pour le Nom du carburant")
     * @Assert\Length(max=50,maxMessage="Your NomCarburant cannot be longer than {{ limit }} characters")
     * @Groups({"carburant:get", "annonce:get"})
     */
    private $NomCarburant;

    /**
     * @ORM\OneToMany(targetEntity=Annonce::class, mappedBy="carburant")
     */
    private $annonces;

    public function __construct()
    {
        $this->annonces = new ArrayCollection();
    }

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
            $annonce->setCarburant($this);
        }

        return $this;
    }

    public function removeAnnonce(Annonce $annonce): self
    {
        if ($this->annonces->removeElement($annonce)) {
            // set the owning side to null (unless already changed)
            if ($annonce->getCarburant() === $this) {
                $annonce->setCarburant(null);
            }
        }

        return $this;
    }
}
