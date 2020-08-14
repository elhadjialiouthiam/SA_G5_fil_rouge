<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\XmlRoot;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\GroupCompetencesRepository;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\Common\Collections\ArrayCollection;
use Hateoas\Configuration\Annotation as Hateoas;
use Symfony\Component\Serializer\Annotation\Groups;


/**
 * @ORM\Entity(repositoryClass=GroupCompetencesRepository::class)
 * @ApiResource(
 * normalizationContext={"groups":{"gc:read"}},
 *      collectionOperations={
 *          "add_groupCompetences"={
 *              "method"="post",
 *              "path"="/admin/grpecompetences",
 *              "access_control"="(is_granted('ROLE_ADMIN'))"
 * },
 *          "show_gcs"={
 *                "method"="get",
*                 "route_name"="show_gcs",
*                 "access_control"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM'))"
 * },
 *          "show_gc_competences"={
 *              "method"="get",
 *              "normalization_context"={"groups":"competence_only"},
 *               "path"="/admin/grpecompetences/competences",
 *              "access_control"="(is_granted('ROLE_ADMIN'))"
 * }
 * },
 *        itemOperations={
 *            "show_one_gc"={
 *               "method"="get",
 *              "path"="/admin/grpecompetences/{id}",
 *              "access_control"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM'))"
 * },
 *          "archive_gc"={
 *              "method"="delete",
 *              "route_name"="archive_gc",
 *              "access_control"="(is_granted('ROLE_ADMIN'))"
 * },
 *          "show_oneGc_competence"={
 *              "method"="get",
 *              "path"="/admin/grpecompetences/{id}/competences",
 *              "normalization_context"={"groups":"competence_only"},
 *              "access_control"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM'))"
 * },
 *          "add_newCompetence"={
 *              "method"="PUT",
 *              "route_name"="ajout_gc_competence",
 *              "denormalization_context"={"groups":"write:addNc"},
 *              "access_control"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM'))"
 * },
 *          "remove_gc_competence"={
 *              "method"="DELETE",
 *              "route_name"="remove_gc_competence",
 *              "access_control"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM'))"
 * }
 * 
 * }
 * 
 * )
 */
class GroupCompetences
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"gc:read"})
     */
    private $libelle;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"gc:read"})
     */
    private $descriptif;

    /**
     * @ORM\ManyToMany(targetEntity=Competence::class, inversedBy="groupCompetences", cascade={"persist"})
     * @ApiSubresource
     * @Groups({"gc:read", "competence_only"})
     */
    private $competences;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $etat;

       /**
     * @ORM\ManyToMany(targetEntity=Referentiel::class, mappedBy="groupeComptence")
     */
    private $referentiels;

    public function __construct()
    {
        $this->competences = new ArrayCollection();
        $this->referentiels = new ArrayCollection();
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

    public function getDescriptif(): ?string
    {
        return $this->descriptif;
    }

    public function setDescriptif(?string $descriptif): self
    {
        $this->descriptif = $descriptif;

        return $this;
    }

    /**
     * @return Collection|Competence[]
     */
    public function getCompetences(): Collection
    {
        return $this->competences;
    }

    public function addCompetence(Competence $competence): self
    {
        if (!$this->competences->contains($competence)) {
            $this->competences[] = $competence;
        }

        return $this;
    }

    public function removeCompetence(Competence $competence): self
    {
        if ($this->competences->contains($competence)) {
            $this->competences->removeElement($competence);
        }

        return $this;
    }

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(string $etat): self
    {
        $this->etat = $etat;

        return $this;
    }

    /**
     * @return Collection|Referentiel[]
     */
    public function getReferentiels(): Collection
    {
        return $this->referentiels;
    }

    public function addReferentiel(Referentiel $referentiel): self
    {
        if (!$this->referentiels->contains($referentiel)) {
            $this->referentiels[] = $referentiel;
            $referentiel->addGroupeComptence($this);
        }

        return $this;
    }

    public function removeReferentiel(Referentiel $referentiel): self
    {
        if ($this->referentiels->contains($referentiel)) {
            $this->referentiels->removeElement($referentiel);
            $referentiel->removeGroupeComptence($this);
        }

        return $this;
    }
}
