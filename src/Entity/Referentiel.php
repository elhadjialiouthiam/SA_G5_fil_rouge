<?php

namespace App\Entity;

use App\Entity\GroupCompetences;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ReferentielRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *     normalizationContext={"groups"={"referentiel:read"}},
 *       collectionOperations={
 *          "addReferentiel" = {
 *              "method"="post",
 *              "path"="/admin/referentiels",
 *              "security"="is_granted('ROLE_ADMIN')",
 *              "security_message"="Vous n'avez pas access à cette Ressource",
 *           },
 *          "GET" = {
 *              "path"="/admin/referentiels",
 *              "security"="is_granted('ROLE_ADMIN')",
 *              "security_message"="Vous n'avez pas access à cette Ressource",
 *          },
 *     },
 *       itemOperations={
 *          "getGrpes"={
 *              "path" = "/admin/referentiels/grpecompetences",
 *              "security"="is_granted('VIEW',object)",
 *              "security_message"="Vous n'avez pas access à cette Ressource",
 *          },
 *          "get"={
 *              "path" = "/admin/referentiels/{id}",
 *              "security"="is_granted('VIEW',object)",
 *              "security_message"="Vous n'avez pas access à cette Ressource",
 *              "default" = {"id"=null}
 *          },
 *          "getCompetence"={
 *              "path" = "/admin/referentiels/{id}/grpecompetences/{idgrp}",
 *              "requirements"={"id"="\d+"},
 *              "security"="is_granted('ROLE_ADMIN')",
 *              "security_message"="Vous n'avez pas access à cette Ressource",
 *          },
 *          "setReferentiel" = {
 *             "path" = "/admin/referentiels/{id<\d+>}",
 *              "requirements"={"id"="\d+"},
 *              "security"="is_granted('ROLE_ADMIN')",
 *              "security_message"="Vous n'avez pas access à cette Ressource",
 *          },
 *          "get_groupe_referentiel_id"={
 *              "method"="GET",
 *              "path"="/referentiels/{id_referentiel}/groupe_competences/{id_groupe}",
 *              "controller"=ShowGroupeByReferentiel::class,
 *              "route_name"="show_groupe_referentiel_id",
 *              "access_control"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_APPRENANT') or is_granted('ROLE_CM'))"
 *          },
 * }
 * )
 * @ORM\Entity(repositoryClass=ReferentielRepository::class)
 */
class Referentiel
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"referentiel:read_all","promo_ref_GrpeCompet_Competences:read","promos:read","referentiel:read","promo_ref_formateurs_apprenants:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"referentiel:read_all","promo_ref_GrpeCompet_Competences:read","promos:read","referentiel:read","promo_ref_formateurs_apprenants:read"})
     */
    private $libelle;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"referentiel:read_all","promo_ref_GrpeCompet_Competences:read","promos:read","referentiel:read","promo_ref_formateurs_apprenants:read"})
     */
    private $presentation;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"referentiel:read_all","promo_ref_GrpeCompet_Competences:read","promos:read","referentiel:read","promo_ref_formateurs_apprenants:read"})
     */
    private $programme;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"referentiel:read_all","promo_ref_GrpeCompet_Competences:read","promos:read","referentiel:read"})
     */
    private $critereAdmission;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"promos:read","referentiel:read"})
     * 
     */
    private $critereEvaluation;

    /**
     * @ORM\ManyToMany(targetEntity=GroupCompetences::class, inversedBy="referentiels",cascade={"persist"})
     * @Groups({"referentiel:read_all","promo_ref_GrpeCompet_Competences:read","promos:read","referentiel:read"})
     */
    private $groupeComptence;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isDeleted;

    /**
     * @ORM\OneToMany(targetEntity=Promos::class, mappedBy="referentiel", cascade={"persist"})
     * @Groups({"referentiel:read"})
     * 
     */
    private $promos;

    public function __construct()
    {
        $this->groupeComptence = new ArrayCollection();
        $this->promos = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getPresentation(): ?string
    {
        return $this->presentation;
    }

    public function setPresentation(string $presentation): self
    {
        $this->presentation = $presentation;

        return $this;
    }

    public function getProgramme(): ?string
    {
        return $this->programme;
    }

    public function setProgramme(string $programme): self
    {
        $this->programme = $programme;

        return $this;
    }

    public function getCritereAdmission(): ?string
    {
        return $this->critereAdmission;
    }

    public function setCritereAdmission(string $critereAdmission): self
    {
        $this->critereAdmission = $critereAdmission;

        return $this;
    }

    public function getCritereEvaluation(): ?string
    {
        return $this->critereEvaluation;
    }

    public function setCritereEvaluation(string $critereEvaluation): self
    {
        $this->critereEvaluation = $critereEvaluation;

        return $this;
    }

    /**
     * @return Collection|GroupCompetences[]
     */
    public function getGroupeComptence(): Collection
    {
        return $this->groupeComptence;
    }

    public function addGroupeComptence(GroupCompetences $groupeComptence): self
    {
        if (!$this->groupeComptence->contains($groupeComptence)) {
            $this->groupeComptence[] = $groupeComptence;
        }

        return $this;
    }

    public function removeGroupeComptence(GroupCompetences $groupeComptence): self
    {
        if ($this->groupeComptence->contains($groupeComptence)) {
            $this->groupeComptence->removeElement($groupeComptence);
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

    /**
     * @return Collection|Promos[]
     */
    public function getPromos(): Collection
    {
        return $this->promos;
    }

    public function addPromo(Promos $promo): self
    {
        if (!$this->promos->contains($promo)) {
            $this->promos[] = $promo;
            $promo->setReferentiel($this);
        }

        return $this;
    }

    public function removePromo(Promos $promo): self
    {
        if ($this->promos->contains($promo)) {
            $this->promos->removeElement($promo);
            // set the owning side to null (unless already changed)
            if ($promo->getReferentiel() === $this) {
                $promo->setReferentiel(null);
            }
        }

        return $this;
    }
}