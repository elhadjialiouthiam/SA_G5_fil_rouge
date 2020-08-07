<?php

namespace App\Entity;

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
 *      collectionOperations={
 *           "show_user"={
 *              "method"="GET",
 *              "route_name"="show_user",
 *               "security" = "is_granted('ROLE_ADMIN')",
 *               "security_message" = "Seuls les admins ont le droit d'acces à ce ressource"
 *               },
 *          "add_user"={
 *              "method"="POST",
 *              "route_name"="add_user",
 *               "security" = "is_granted('ROLE_ADMIN')",
 *               "security_message" = "Seuls les admins ont le droit d'acces à ce ressource"
 *               },
 *           "get_apprenants"={
 *                  "method"="GET",
*                   "route_name"="apprenant_liste",
*                   },
*             "add_apprenant"={
*                   "method" = "POST",
*                   "security"="is_granted('ROLE_ADMIN')",
*                   "security_message"="Seuls les admins et les formateurs ont acces à ce ressource",
*                   "route_name" = "apprenant_add",
*                   "denormalization_context"={"groups":"apprenant:write"}
*                  },
*           "get_formateurs"={
*                  "method"="GET",
*                   "route_name"="formateur_liste",
*                   },
 *          "api_reset_pwd"={
 *              "route_name"="api_reset_pwd",
 *              "method"="POST",
 *              "denormalization_context"={"groups":"reset:write"},
 *          },
*             },
*           itemOperations={
*           "get_one_user"={
*               "method"="GET",
*               "path"="/admin/users/{id}" ,
*               }, 
*           "put_one_user"={
*               "method"="PUT",
*               "path"="/admin/users/{id}" ,
*               },
*           "archive_user"={
    *             "method"="DELETE",
    *               "route_name"="archive_user"
*},
*            "getOne_apprenant"={
 *                  "method"="GET",
 *                  "path"="apprenants/{id}",
 *                   "security" = "is_granted('ROLE_ADMIN') or is_granted('ROLE_CM) or object == user",
*                   "security_message" = "Seul un admin ou un CM ou le detenteur peut modifier ses informations"
*                   },
*            "getOne_formateur"={
*                  "method"="GET",
*                  "path"="formateurs/{id}",
*                   "security" = "is_granted('ROLE_ADMIN') or is_granted('ROLE_CM') or object == user",
*                   "security_message" = "Seul un admin ou un CM ou le detenteur peut modifier ses informations"
*                   },
*              "delete_apprenant"={
*                   "method" = "DELETE",
*                   "route_name" = "apprenant_delete",
*                   },
*             "update_apprenant" = {
*                   "method" = "PUT",
*                   "path" = "/apprenants/{id}",
*                   "security" = "is_granted('ROLE_ADMIN') or object == user",
*                   "security_message" = "Seul un admin ou le detenteur a access à ces informations"
*                   },
*             "update_formateur" = {
*                   "method" = "PUT",
*                   "path" = "/formateurs/{id}",
*                   "security" = "is_granted('ROLE_ADMIN') or object == user",
*                   "security_message" = "Seul un admin ou le detenteur peut modifier ses informations"
*                   }
*               }, 
 * )
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Assert\NotBlank(message = "L'email ne peut pas etre vide")
     * @Assert\Regex(
     * pattern="/^[a-zA-Z0-9_.-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$/",
     * message="Email Invalide"
     * )
     * @Groups({"reset:write","user:write","apprenant:write"})
     */
    private $email;

    /**
     * @ORM\Column(type="json")
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
     * @Groups({"user:write","apprenant:write"})
     */
    private $prenom;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message = "Le nom ne peut pas etre vide")
     * @Assert\Length(min = 3)
     * @Groups({"user:write","apprenant:write"})
     */
    private $nom;

    /**
     * @ORM\ManyToOne(targetEntity=Profil::class, inversedBy="users")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"user:write"})
     */
    private $profil;

    /**
     * @ORM\Column(type="blob", nullable=true)
     * @Groups({"user:write","apprenant:write"})
     */
    private $avatar;

    /**
     * @ORM\ManyToOne(targetEntity=ProfilDeSortie::class, inversedBy="users")
     * @Groups({"apprenant:write", "user:write"})
     */
    private $profilDeSortie;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $etat;

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

    public function getProfilDeSortie(): ?ProfilDeSortie
    {
        return $this->profilDeSortie;
    }

    public function setProfilDeSortie(?ProfilDeSortie $profilDeSortie): self
    {
        $this->profilDeSortie = $profilDeSortie;

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

}
