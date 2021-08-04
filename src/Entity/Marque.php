<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\MarqueRepository;
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
 *          "groups"={"marque:get"}
 *     }
 * )
 * @ApiFilter(SearchFilter::class, properties={"nomMarque"="exact"})
 * @ApiFilter(OrderFilter::class, properties={"id"="asc"})
 * @ApiFilter(NumericFilter::class, properties={"id"})
 *
 * @ORM\Entity(repositoryClass=MarqueRepository::class)
 */
class Marque
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"marque:get", "modele:get", "annonce:get"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50, unique=true)
     * @Groups({"marque:get", "modele:get", "annonce:get"})
     * @Assert\NotBlank(message = "notBlank")
     * @Assert\NotNull(message = "notNull")
     * @Assert\Length(max=50,maxMessage="Your nomMarque cannot be longer than {{ limit }} characters")
     */
    private $nomMarque;

    /**
     * @ORM\OneToMany(targetEntity=Modele::class, mappedBy="Marque")
     * @Groups({"marque:get"})
     */
    private $modeles;

    public function __construct()
    {
        $this->modeles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomMarque(): ?string
    {
        return $this->nomMarque;
    }

    public function setNomMarque(string $nomMarque): self
    {
        $this->nomMarque = $nomMarque;

        return $this;
    }

    /**
     * @return Collection|Modele[]
     */
    public function getModeles(): Collection
    {
        return $this->modeles;
    }

    public function addModele(Modele $modele): self
    {
        if (!$this->modeles->contains($modele)) {
            $this->modeles[] = $modele;
            $modele->setMarque($this);
        }

        return $this;
    }

    public function removeModele(Modele $modele): self
    {
        if ($this->modeles->removeElement($modele)) {
            // set the owning side to null (unless already changed)
            if ($modele->getMarque() === $this) {
                $modele->setMarque(null);
            }
        }

        return $this;
    }
}
