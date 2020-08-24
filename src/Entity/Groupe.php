<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\GroupeRepository;
use App\Controller\ApprenantController;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=GroupeRepository::class)
 * @ApiResource(
 *      denormalizationContext={"groups":{"groupe:write"}},
 *      normalizationContext={"groups":{"groupe:read"}},
 *  collectionOperations={
 *      "add_groupe"={
 *          "method"="POST",
 *          "path"="admin/groupes"
 * },
 *      "show_groupes"={
 *           "method"="GET",
 *           "route_name"="show_groupes"
 *  },
 *    "show_groups_apprenants",
 * },
 *  itemOperations={
 *          "show_one_group"={
 *              "method"="GET",
 *              "path"="admin/groupes/{id}"
 * },
 *          "modify_groupe"={
 *              "method"="PUT",
 *              "path"="admin/groupes/{id}"
 * },
 *          "remove_apprenant"={
 *              "method"="DELETE",
 *              "route_name"="remove_apprenant",
 *              "path"="admin/groupes/path",
 *            },
 *        "archive_group"={
 *              "method"="DELETE",
 *              "route_name"="archive_groupe",
 *            },
 *          "listeApp_group"={
 *              "method"="GET",
 *              "path"="admin/groupes/{id}/apprenants",
 *              "normalization_context"={"groups"="groupe_apprenants:read"}
 *  },
 *       "remove_apprenant"={
 *              "method"="get",
 *              "path"="admin/groupes/{id}/apprenants/{iden}",
 *  },
 * "add_apprenant_group"={
 *       "method"="put",
 *              "path"="admin/groupes/{id}/apprenants/{iden}",   
 * }
 * }
 * )
 * 
 */
class Groupe
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"briefOfGroup:read","apprenantlivable:read"})
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"groupe:write", "groupe:read","apprenantlivable:read"})
     */
    private $nom;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups({"groupe:read","apprenantlivable:read"})
     */
    private $datecreation;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"groupe:write", "groupe:read","apprenantlivable:read"})
     */
    private $statut;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"groupe:write", "groupe:read","apprenantlivable:read"})
     */
    private $type;

    /**
     * @ORM\ManyToMany(targetEntity=Apprenant::class, inversedBy="groupes")
     * @Groups({"groupe:write", "groupe:read", "groupe_apprenants:read", "briefOfGroup:read"})
     */
    private $Apprenants;

    /**
     * @ORM\ManyToMany(targetEntity=Formateur::class, inversedBy="groupes")
     * @Groups({"groupe:write"})
     */
    private $formateurs;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $etat;

    /**
     * @ORM\ManyToOne(targetEntity=Promos::class, inversedBy="groupes", cascade={"persist"})
     */
    private $promos;

    /**
     * @ORM\ManyToOne(targetEntity=BriefGroupe::class, inversedBy="groupes")
     */
    private $briefGroupe;

    public function __construct()
    {
        $this->Apprenants = new ArrayCollection();
        $this->formateurs = new ArrayCollection();
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

    public function getDatecreation(): ?\DateTimeInterface
    {
        return $this->datecreation;
    }

    public function setDatecreation(\DateTimeInterface $datecreation): self
    {
        $this->datecreation = $datecreation;

        return $this;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): self
    {
        $this->statut = $statut;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return Collection|Apprenant[]
     */
    public function getApprenants(): Collection
    {
        return $this->Apprenants;
    }

    public function addApprenant(Apprenant $apprenant): self
    {
        if (!$this->Apprenants->contains($apprenant)) {
            $this->Apprenants[] = $apprenant;
        }

        return $this;
    }

    public function removeApprenant(Apprenant $apprenant): self
    {
        if ($this->Apprenants->contains($apprenant)) {
            $this->Apprenants->removeElement($apprenant);
        }

        return $this;
    }

    /**
     * @return Collection|Formateur[]
     */
    public function getFormateurs(): Collection
    {
        return $this->formateurs;
    }

    public function addFormateur(Formateur $formateur): self
    {
        if (!$this->formateurs->contains($formateur)) {
            $this->formateurs[] = $formateur;
        }

        return $this;
    }

    public function removeFormateur(Formateur $formateur): self
    {
        if ($this->formateurs->contains($formateur)) {
            $this->formateurs->removeElement($formateur);
        }

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

    public function getPromos(): ?Promos
    {
        return $this->promos;
    }

    public function setPromos(?Promos $promos): self
    {
        $this->promos = $promos;

        return $this;
    }

    public function getBriefGroupe(): ?BriefGroupe
    {
        return $this->briefGroupe;
    }

    public function setBriefGroupe(?BriefGroupe $briefGroupe): self
    {
        $this->briefGroupe = $briefGroupe;

        return $this;
    }

    

}
