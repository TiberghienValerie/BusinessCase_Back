<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\PhotoRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Bridge\Elasticsearch\DataProvider\Filter\OrderFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\NumericFilter;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 * collectionOperations={
 *     "get",
 *     "post"={
 *              "security"="is_granted('ROLE_ADMIN') or object.annonce.garage.user== user"
 *          }
 *     },
 *     itemOperations={
 *     "get",
 *     "put"={
 *              "security"="is_granted('ROLE_ADMIN') or object.annonce.garage.user== user"
 *          },
 *     "delete"={
 *              "security"="is_granted('ROLE_ADMIN') or object.annonce.garage.user== user"
 *          }
 *     },
 *     normalizationContext={
 *          "groups"={"photo:get"}
 *     }
 * )
 * @ApiFilter(SearchFilter::class, properties={"nomPhotos"="exact"})
 * @ApiFilter(OrderFilter::class, properties={"id"="asc"})
 * @ApiFilter(NumericFilter::class, properties={"annonce.garage.user.id", "id"})
 * @ORM\Entity(repositoryClass=PhotoRepository::class)
 */
class Photo
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"photo:get"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"photo:get"})
     */
    private $nomPhotos;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"photo:get"})
     */
    private $pathPhotos;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"photo:get"})
     */
    private $ordre;

    /**
     * @ORM\ManyToOne(targetEntity=Annonce::class, inversedBy="photos")
     * @ORM\JoinColumn(nullable=false)
     */
    private $annonce;

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

    public function getAnnonce(): ?Annonce
    {
        return $this->annonce;
    }

    public function setAnnonce(?Annonce $annonce): self
    {
        $this->annonce = $annonce;

        return $this;
    }
}
