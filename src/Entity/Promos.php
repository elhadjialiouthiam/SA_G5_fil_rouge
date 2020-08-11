<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\PromosRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
  * @ApiResource(
 *       attributes={
 *      "input_formats"={"json"={"application/ld+json", "application/json"}},
 *      "output_formats"={"json"={"application/ld+json", "application/json"}}
 *     },
 *     normalizationContext={"groups"={"promos:read"}},
 *     collectionOperations={
 *          "get_promos" = {
 *              "method"="GET",
 *              "path"="/admin/promos",
 *           },
 *          "get_principal" = {
 *              "method"="GET",
 *              "path"="/admin/promos/principal",
 *           },
 *          "get_attente" = {
 *              "method"="GET",
 *              "path"="/admin/promos/apprenants/attente",
 *           },
 *          "add_promo" = {
 *              "method"="POST",
 *              "path"="/admin/promos",
 *           }
 *     },
 *     itemOperations={
 *          "setPromo"={
 *              "path" = "/admin/promo/{id}",
 *              "method"="PUT",
 *              "requirements"={"id"="\d+"},
 *              "defaults"={"id"=null},
 *              "security"="is_granted('VIEW',object)",
 *              "security_message"="Vous n'avez pas access à cette Ressource",
 *          },
 *          "api_promos_get_item"={
 *              "path" = "/admin/promos/{id<\d+>}",
 *              "requirements"={"id"="\d+"},
 *              "defaults"={"id"=null},
 *              "security"="is_granted('VIEW',object)",
 *              "security_message"="Vous n'avez pas access à cette Ressource",
 *          },
 *          "getRefWaitingStudents"={
 *              "path" = "/admin/promo/{id<\d+>}/apprenants/attente",
 *              "requirements"={"id"="\d+"},
 *              "security"="is_granted('VIEW',object)",
 *              "security_message"="Vous n'avez pas access à cette Ressource",
 *          },
 *          "get"={
 *              "path"="/admin/promo/{id<\d+>}/formateurs",
 *              "requirements"={"id"="\d+"},
 *              "security"="is_granted('VIEW',object)",
 *              "security_message"="Vous n'avez pas access à cette Ressource",
 *          },
 *          
 *      "get_promo_id_groupes_id_apprenants"={
 *          "method"="GET",
 *          "path"="/admin/promos/{id_promo}/groupes/{id_groupe}/apprenants",
 *          "requirements"={"id_promo"="\d+"},
 *          "controller"=ShowApprenantsByGroupeAndPromo::class,
 *          "route_name"="show_promo_id_groupes_id_apprenants",
 *          "access_control"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM'))",
 *      },
 *      "getPrincipal"={
 *          "method"="GET",
 *          "path"="/admin/promos/{id}/principal",
 *          "requirements"={"id"="\d+"},
 *          "route_name"="getPrincipal",
 *          "access_control"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM'))",
 *      },
 *      "get_promo_id_ref_GroupeCompet_Competences"={
 *          "method"="GET",
 *          "path"="/admin/promo/{id}/referentiels",
 *          "requirements"={"id"="\d+"},
 *          "route_name"="show_promo_id_ref_GroupeCompet_Competences",
 *          "access_control"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM'))",
 *      },
 *      "add_Formateurs_in_promos"={
 *          "method"="PUT",
 *          "path"="/admin/promo/{id}/formateurs",
 *          "requirements"={"id"="\d+"},
 *          "route_name"="add_Formateurs_in_promo",
 *          "access_control"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM'))",
 *      },
 *      "delete_Formateurs_in_promos"={
 *          "method"="DELETE",
 *          "path"="/admin/promo/{id}/formateurs/{id_formateurs}",
 *          "requirements"={"id"="\d+"},
 *          "route_name"="delete_Formateurs_in_promo",
 *          "access_control"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM'))",
 *      },
 *      "add_Apprenant_in_promos"={
 *          "method"="PUT",
 *          "path"="/admin/promo/{id}/apprenants",
 *          "requirements"={"id"="\d+"},
 *          "route_name"="add_Apprenant_in_promo",
 *          "access_control"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM'))",
 *      },
 *      "delete_apprenants_in_promos"={
 *          "method"="DELETE",
 *          "path"="/admin/promo/{id}/apprenants/{id_apprenant}",
 *          "requirements"={"id"="\d+"},
 *          "route_name"="delete_apprenants_in_promo",
 *          "access_control"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM'))",
 *      },
 *      "Modifier_statut_groupe"={
 *          "method"="PUT",
 *          "path"="/admin/promo/{id}/groupes/{id_groupe}",
 *          "requirements"={"id"="\d+"},
 *          "route_name"="Modifier_statut_groupe",
 *          "access_control"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM'))",
 *      },
 * }
 * )
 * @ORM\Entity(repositoryClass=PromosRepository::class)
 */
class Promos
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"promos:read"})
     * @Groups({"promo_ref_GrpeCompet_Competences:read","referentiel:read","promoFormateurApprenant:read_all","promo_groupe_apprenants:read","promo_ref_formateurs_apprenants:read"})
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"promos:read"})
     * @Groups({"promo_ref_GrpeCompet_Competences:read","referentiel:read","promoFormateurApprenant:read_all","promo_groupe_apprenants:read","promo_ref_formateurs_apprenants:read"})
     */
    private $langue;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"promos:read"})
     * @Groups({"referentiel:read","promoFormateurApprenant:read_all","promo_groupe_apprenants:read","promo_ref_formateurs_apprenants:read"})
     */
    private $titre;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"promos:read"})
     * @Groups({"promo_ref_GrpeCompet_Competences:read","referentiel:read","promoFormateurApprenant:read_all"})
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"promos:read"})
     * @Groups({"referentiel:read"})
     */
    private $lieu;

    /**
     * @ORM\Column(type="date")
     * @Groups({"promos:read"})
     */
    private $dateDebut;

    /**
     * @ORM\Column(type="date")
     * @Groups({"promos:read"})
     */
    private $dateFinProvisoire;

    /**
     * @ORM\Column(type="date", nullable=true)
     * 
     */
    private $dateFinReelle;

    /**
     * @ORM\ManyToOne(targetEntity=Referentiel::class, inversedBy="promos",cascade={"persist"})
     * @Groups({"promo_ref_GrpeCompet_Competences:read","promos:read","promo_ref_formateurs_apprenants:read"})
     */
    private $referentiel;

    /**
     * @ORM\ManyToMany(targetEntity=Formateur::class, inversedBy="promos",cascade={"persist"})
     * @Groups({"promos:read","promo_ref_formateurs_apprenants:read"})
     */
    private $formateur;

    /**
     * @ORM\OneToMany(targetEntity=Groupe::class, mappedBy="promos")
     * @Groups({"promos:read", "promoFormateurApprenant:read_all","promo_groupe_apprenants:read","promo_ref_formateurs_apprenants:read"})
     */
    private $groupes;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isDeleted;

    /**
     * @ORM\ManyToOne(targetEntity=Admin::class, inversedBy="promos")
     * 
     */
    private $admin;

    /**
     * @ORM\Column(type="boolean")
     */
    private $etat;

    /**
     * @ORM\OneToMany(targetEntity=Apprenant::class, mappedBy="promos")
     */
    private $apprenants;

    public function __construct()
    {
        $this->formateur = new ArrayCollection();
        $this->groupes = new ArrayCollection();
        $this->apprenants = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLangue(): ?string
    {
        return $this->langue;
    }

    public function setLangue(string $langue): self
    {
        $this->langue = $langue;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getLieu(): ?string
    {
        return $this->lieu;
    }

    public function setLieu(string $lieu): self
    {
        $this->lieu = $lieu;

        return $this;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->dateDebut;
    }

    public function setDateDebut(\DateTimeInterface $dateDebut): self
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    public function getDateFinProvisoire(): ?\DateTimeInterface
    {
        return $this->dateFinProvisoire;
    }

    public function setDateFinProvisoire(\DateTimeInterface $dateFinProvisoire): self
    {
        $this->dateFinProvisoire = $dateFinProvisoire;

        return $this;
    }

    public function getDateFinReelle(): ?\DateTimeInterface
    {
        return $this->dateFinReelle;
    }

    public function setDateFinReelle(?\DateTimeInterface $dateFinReelle): self
    {
        $this->dateFinReelle = $dateFinReelle;

        return $this;
    }

    public function getReferentiel(): ?Referentiel
    {
        return $this->referentiel;
    }

    public function setReferentiel(?Referentiel $referentiel): self
    {
        $this->referentiel = $referentiel;

        return $this;
    }

    /**
     * @return Collection|Formateur[]
     */
    public function getFormateur(): Collection
    {
        return $this->formateur;
    }

    public function addFormateur(Formateur $formateur): self
    {
        if (!$this->formateur->contains($formateur)) {
            $this->formateur[] = $formateur;
        }

        return $this;
    }

    public function removeFormateur(Formateur $formateur): self
    {
        if ($this->formateur->contains($formateur)) {
            $this->formateur->removeElement($formateur);
        }

        return $this;
    }

    /**
     * @return Collection|Groupe[]
     */
    public function getGroupes(): Collection
    {
        return $this->groupes;
    }

    public function addGroupe(Groupe $groupe): self
    {
        if (!$this->groupes->contains($groupe)) {
            $this->groupes[] = $groupe;
            $groupe->setPromos($this);
        }

        return $this;
    }

    public function removeGroupe(Groupe $groupe): self
    {
        if ($this->groupes->contains($groupe)) {
            $this->groupes->removeElement($groupe);
            // set the owning side to null (unless already changed)
            if ($groupe->getPromos() === $this) {
                $groupe->setPromos(null);
            }
        }

        return $this;
    }

    public function getIsDeleted(): ?bool
    {
        return $this->isDeleted;
    }

    public function setIsDeleted(bool $isDeleted): self
    {
        $this->isDeleted = $isDeleted;

        return $this;
    }

    public function getAdmin(): ?Admin
    {
        return $this->admin;
    }

    public function setAdmin(?Admin $admin): self
    {
        $this->admin = $admin;

        return $this;
    }

    public function getEtat(): ?bool
    {
        return $this->etat;
    }

    public function setEtat(bool $etat): self
    {
        $this->etat = $etat;

        return $this;
    }

    /**
     * @return Collection|Apprenant[]
     */
    public function getApprenants(): Collection
    {
        return $this->apprenants;
    }

    public function addApprenant(Apprenant $apprenant): self
    {
        if (!$this->apprenants->contains($apprenant)) {
            $this->apprenants[] = $apprenant;
            $apprenant->setPromos($this);
        }

        return $this;
    }

    public function removeApprenant(Apprenant $apprenant): self
    {
        if ($this->apprenants->contains($apprenant)) {
            $this->apprenants->removeElement($apprenant);
            // set the owning side to null (unless already changed)
            if ($apprenant->getPromos() === $this) {
                $apprenant->setPromos(null);
            }
        }

        return $this;
    }
}
