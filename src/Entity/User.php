<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ApiResource(
 * denormalizationContext={"groups":{"user:write"}},
 * normalizationContext={"groups":{"user:read"}},
 *     collectionOperations={
 *          "liste_users"={
 *              "method"="get",
 *              "path"="/admin/users",
 *              "security" = "is_granted('ROLE_ADMIN')",
 *              "security_message" = "Accès refusé"
 * },
 *          "api_reset_pwd"={
 *              "route_name"="api_reset_pwd",
 *              "method"="POST",
 *              "denormalization_context"={"groups":"reset:write"},
 *              "security" = "is_granted('ROLE_ADMIN')",
 *              "security_message" = "Accès refusé"
 *          },
*             },
*           itemOperations={
*           "get_one_user"={
*               "method"="GET",
*               "path"="/admin/users/{id}" ,
 *               "security" = "is_granted('ROLE_ADMIN')",
 *              "security_message" = "Accès refusé"
*               }, 
*           "put_one_user"={
*               "method"="PUT",
*               "path"="/admin/users/{id}" ,
*               "security" = "is_granted('ROLE_ADMIN')",
 *              "security_message" = "Accès refusé"
*               },
*           "archive_user"={
*             "method"="DELETE",
*              "path"= "/admin/users/{id}",
*               "security" = "is_granted('ROLE_ADMIN')",
 *              "security_message" = "Accès refusé"
*  },

* }, 
 *)
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="type", type="string")
 * @ORM\DiscriminatorMap({"apprenant"="Apprenant", "formateur"="Formateur", "admin"="Admin", "cm"="CM", "user"="User"})
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Assert\NotBlank(message = "L'email ne peut pas etre vide")
     * @Assert\Regex(
     * pattern="/^[a-zA-Z0-9_.-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$/",
     * message="Email Invalide"
     * )
     * @Groups({"reset:read","user:write","apprenant:write", "groupe:read", "groupe_apprenants:read", "user:read", "promos:write", "briefOfGroup:read"})
     */ 
    private $email;

    
    /**
     * Column(type="json")
     */
    private $roles = [];


    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     * @Groups({"user:write","apprenant:write"})
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message = "Le prenom ne peut pas etre vide")
     * @Assert\Length(min = 3)
     * @Groups({"user:write","apprenant:write", "groupe:read", "groupe_apprenants:read", "user:read", "briefOfGroup:read"})
     */
    private $prenom;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message = "Le nom ne peut pas etre vide")
     * @Assert\Length(min = 3)
     * @Groups({"user:write","apprenant:write", "groupe:read", "groupe_apprenants:read", "user:read", "briefOfGroup:read"})
     */
    private $nom;

    /**
     * @ORM\ManyToOne(targetEntity=Profil::class, inversedBy="users")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"user:write", "user:read"})
     */
    private $profil;

    /**
     * @ORM\Column(type="blob", nullable=true)
     * @Groups({"user:write","apprenant:write"})
     */
    private $avatar;


    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $etat;

   
    private $type;

    /**
     * @ORM\OneToMany(targetEntity=CommentairesGenerale::class, mappedBy="user")
     */
    private $commentairesGenerales;

    public function __construct()
    {
        $this->commentairesGenerales = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_'.strtoupper($this->profil->getLibelle());

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getProfil(): ?Profil
    {
        return $this->profil;
    }

    public function setProfil(?Profil $profil): self
    {
        $this->profil = $profil;

        return $this;
    }

    public function getAvatar()
    {
        return $this->avatar;
    }

    public function setAvatar($avatar): self
    {
        $this->avatar = $avatar;

        return $this;
    }


    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(?string $etat): self
    {
        $this->etat = $etat;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @return Collection|CommentairesGenerale[]
     */
    public function getCommentairesGenerales(): Collection
    {
        return $this->commentairesGenerales;
    }

    public function addCommentairesGenerale(CommentairesGenerale $commentairesGenerale): self
    {
        if (!$this->commentairesGenerales->contains($commentairesGenerale)) {
            $this->commentairesGenerales[] = $commentairesGenerale;
            $commentairesGenerale->setUser($this);
        }

        return $this;
    }

    public function removeCommentairesGenerale(CommentairesGenerale $commentairesGenerale): self
    {
        if ($this->commentairesGenerales->contains($commentairesGenerale)) {
            $this->commentairesGenerales->removeElement($commentairesGenerale);
            // set the owning side to null (unless already changed)
            if ($commentairesGenerale->getUser() === $this) {
                $commentairesGenerale->setUser(null);
            }
        }

        return $this;
    }



}
