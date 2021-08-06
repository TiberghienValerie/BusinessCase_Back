<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Bridge\Elasticsearch\DataProvider\Filter\OrderFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\NumericFilter;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ApiResource(
 * collectionOperations={
 *     "get"={
 *              "security"="is_granted('ROLE_PROFESSIONNEL')"
 *      },
 *     "post"={
 *              "security"="is_granted('ROLE_ADMIN')"
 *          }
 *     },
 * itemOperations={
 *     "get"={
 *              "security"="is_granted('ROLE_PROFESSIONNEL')"
 *      },
 *     "put"={
 *              "security"="is_granted('ROLE_PROFESSIONNEL')"
 *          },
 *     "delete"={
 *              "security"="is_granted('ROLE_ADMIN')"
 *          }
 *     },
 * normalizationContext={
 *          "groups"={"user:get"}
 *     }
 * )
 * @ApiFilter(SearchFilter::class, properties={"nom"="exact","prenom"="exact","siret"="exact","telephone"="exact","username"="exact","email"="exact"})
 * @ApiFilter(OrderFilter::class, properties={"id"="asc"})
 * @ApiFilter(NumericFilter::class, properties={"id"})
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(
 *     fields={"username", "email", "siret"},
 *     errorPath="Infos",
 *     message="Email, uusername ou siret déjà utilisé."
 * )
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"user:get"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Assert\NotBlank(message = "Blanc interdit pour le userName")
     * @Assert\NotNull(message = "Not Null interdit pour le userName")
     * @Groups({"user:get"})
     */
    private $username;

    /**
     * @ORM\Column(type="json")
     * @Groups({"user:get"})
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     * @Groups({"user:get"})
     */
    private $password;

    /**
     * @var string|null
     * @SerializedName("password")
     */
    private $plainPassword;

    /**
     * @ORM\Column(type="string", length=50, unique=true)
     * @Assert\NotBlank(message = "Blanc interdit pour l'email'")
     * @Assert\Email(message="L'email n'est pas valide")
     * @Groups({"user:get"})
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=15)
     * @Assert\NotBlank(message = "Blanc interdit pour le telephone")
     * @Assert\NotNull(message = "Not null interdit pour le telephone")
     * @Assert\Length(max=15,maxMessage="Your telephone cannot be longer than {{ limit }} characters")
     * @Groups({"user:get"})
     */
    private $telephone;

    /**
     * @ORM\Column(type="string", length=14, unique=true)
     * @Assert\NotBlank(message = "Blanc interdit pour le siret")
     * @Assert\NotNull(message = "Not null interdit pour le siret")
     * @Assert\Length(max=14,maxMessage="Your siret cannot be longer than {{ limit }} characters")
     * @Groups({"user:get"})
     */
    private $siret;

    /**
     * @ORM\OneToMany(targetEntity=Garage::class, mappedBy="user", cascade={"remove"})
     */
    private $garages;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message = "Blanc interdit pour le nom")
     * @Assert\NotNull(message = "Not null interdit pour le nom")
     * @Groups({"user:get"})
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message = "Blanc interdit pour le prenom")
     * @Assert\NotNull(message = "Not null interdit pour le prenom")
     * @Groups({"user:get"})
     */
    private $prenom;

    public function __construct()
    {
        $this->garages = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->username;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_PROFESSIONNEL';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
         $this->plainPassword = null;
    }

    /**
     * @return string|null
     */
    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    /**
     * @param string|null $plainPassword
     * @return $this
     */
    public function setPlainPassword(?string $plainPassword): self
    {
        $this->plainPassword = $plainPassword;
        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

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

    public function getSiret(): ?string
    {
        return $this->siret;
    }

    public function setSiret(string $siret): self
    {
        $this->siret = $siret;

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
            $garage->setUser($this);
        }

        return $this;
    }

    public function removeGarage(Garage $garage): self
    {
        if ($this->garages->removeElement($garage)) {
            // set the owning side to null (unless already changed)
            if ($garage->getUser() === $this) {
                $garage->setUser(null);
            }
        }

        return $this;
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

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }
}
