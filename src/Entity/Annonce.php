<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiFilter;
use App\Repository\AnnonceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Bridge\Elasticsearch\DataProvider\Filter\OrderFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\NumericFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\RangeFilter;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ApiResource(
 *  attributes={"order"={"DateAnnonce"="DESC"},"pagination_items_per_page"=30},
 *  collectionOperations={
 *      "get",
 *      "post"={
 *          "security"="is_granted('ROLE_ADMIN') or object.garage.user== user"
 *      }
 *  },
 *  itemOperations={
 *      "get",
 *      "put"={
 *          "security"="is_granted('ROLE_ADMIN') or object.garage.user== user"
 *      },
        "delete"={
 *          "security"="is_granted('ROLE_ADMIN') or object.garage.user== user"
 *      }
 *  },
 *  normalizationContext={
 *      "groups"={"annonce:get"}
 *  },
 *  denormalizationContext={
 *       "groups"={"annonce:get"}
 *  }
 * )
 * @ApiFilter(SearchFilter::class, properties={"refAnnonce"="exact","titre"="exact", "refAnnonce"="exact", "titre"="exact"})
 * @ApiFilter(OrderFilter::class, properties={"DateAnnonce"="DESC"})
 * @ApiFilter(NumericFilter::class, properties={"garage.user.id", "carburant.id", "modele.id", "modele.marque.id", "prix","kilometrage","anneeCirculation","id"})
 * @ApiFilter(RangeFilter::class, properties={"prix","kilometrage","anneeCirculation","id"})
 * @ORM\Entity(repositoryClass=AnnonceRepository::class)
 */
class Annonce
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"annonce:get"})
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"annonce:get"})
     * @Assert\DateTime(message="Type datetime")
     * @Assert\NotBlank(message = "notBlank")
     * @Assert\NotNull(message = "notNull")
     */
    private $DateAnnonce;

    /**
     * @ORM\Column(type="string", length=10, unique=true)
     * @Groups({"annonce:get"})
     * @Assert\NotBlank(message = "not Blank pour votre refAnnonce")
     * @Assert\NotNull(message = "Not null pour votre refAnnonce")
     * @Assert\Unique(message="La reférence est déjà utilisé")
     * @Assert\Length(max=10,maxMessage="Your refAnnonce cannot be longer than {{ limit }} characters")
     */
    private $refAnnonce;

    /**
     * @ORM\Column(type="string", length=50)
     * @Groups({"annonce:get"})
     * @Assert\NotBlank(message = "not Blank pour votre titre d'annonce")
     * @Assert\NotNull(message = "Not null pour votre titre d'annonce")
     * @Assert\Length(max=50,maxMessage="Your titre cannot be longer than {{ limit }} characters")
     */
    private $titre;

    /**
     * @ORM\Column(type="string", length=250)
     * @Assert\NotBlank(message = "not Blank pour votre description courte")
     * @Assert\NotNull(message = "not Null pour votre description courte")
     * @Groups({"annonce:get"})
     */
    private $descriptionCourte;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank(message = "not Blank pour votre description longue")
     * @Assert\NotNull(message = "not Null pour votre description longue")
     * @Groups({"annonce:get"})
     */
    private $descriptionLongue;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"annonce:get"})
     * @Assert\PositiveOrZero(message="not negative pour l'année de circulation")
     */
    private $anneeCirculation;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"annonce:get"})
     * @Assert\PositiveOrZero(message="not negative pour le kilometrage")
     */
    private $kilometrage;

    /**
     * @ORM\Column(type="decimal", precision=13, scale=2)
     * @Assert\PositiveOrZero(message="not negative pour le prix")
     * @Groups({"annonce:get"})
     */
    private $prix;

    /**
     * @ORM\ManyToOne(targetEntity=Carburant::class, inversedBy="annonces")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"annonce:get"})
     */
    private $carburant;

    /**
     * @ORM\ManyToOne(targetEntity=Modele::class, inversedBy="annonces")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"annonce:get"})
     */
    private $modele;

    /**
     * @ORM\ManyToOne(targetEntity=Garage::class, inversedBy="annonces")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"annonce:get"})
     */
    public $garage;

    /**
     * @ORM\OneToMany(targetEntity=Photo::class, mappedBy="annonce", cascade={"remove"})
     * @Groups({"annonce:get"})
     */
    private $photos;

    public function __construct()
    {
        $this->photos = new ArrayCollection();
    }

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

    public function getCarburant(): ?Carburant
    {
        return $this->carburant;
    }

    public function setCarburant(?Carburant $carburant): self
    {
        $this->carburant = $carburant;

        return $this;
    }

    public function getModele(): ?Modele
    {
        return $this->modele;
    }

    public function setModele(?Modele $modele): self
    {
        $this->modele = $modele;

        return $this;
    }

    public function getGarage(): ?Garage
    {
        return $this->garage;
    }

    public function setGarage(?Garage $garage): self
    {
        $this->garage = $garage;

        return $this;
    }

    /**
     * @return Collection|Photo[]
     */
    public function getPhotos(): Collection
    {
        return $this->photos;
    }

    public function addPhoto(Photo $photo): self
    {
        if (!$this->photos->contains($photo)) {
            $this->photos[] = $photo;
            $photo->setAnnonce($this);
        }

        return $this;
    }

    public function removePhoto(Photo $photo): self
    {
        if ($this->photos->removeElement($photo)) {
            // set the owning side to null (unless already changed)
            if ($photo->getAnnonce() === $this) {
                $photo->setAnnonce(null);
            }
        }

        return $this;
    }
}
