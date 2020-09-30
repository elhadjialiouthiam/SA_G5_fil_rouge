<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Controller\CompetenceController;
use App\Repository\CompetenceRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=CompetenceRepository::class)
 * @ApiResource(
 * denormalizationContext={"groups":{"competence:write"}},
 * normalizationContext={"groups":{"competence:read"}},
 *      collectionOperations={
 *          "show_comptences"={
 *              "method" = "get",
 *              "path"="/admin/competences",
 *              "access_control"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM'))"
 * },
 *        "post"={
 *          "path"="/admin/competences",
 *          "access_control"="(is_granted('ROLE_ADMIN'))"
 *      }
 * },
 * itemOperations={
 *      "show_one_competence"={
 *          "method"="get",
 *          "path"="/admin/competences/{id}",
 *           "access_control"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM'))"
 * },
 *       "update_competence"={
 *          "method"="put",
 *          "path"="/admin/competences/{id}",
 *          "access_control"="(is_granted('ROLE_ADMIN'))"
 *          
 * },
 *      "archive_competence"={
 *          "method"="delete",
 *          "route_name"="archive_competence",
 *          "access_control"="(is_granted('ROLE_ADMIN'))"
 * }
 * }
 * )
 */
class Competence
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"write:addNc", "brief:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"competence:write", "competence:read", "gc:read", "brief:read"})
     */
    private $libelle;

    /**
     * @ORM\ManyToMany(targetEntity=GroupCompetences::class, mappedBy="competences", cascade={"persist"})
     */
    private $groupCompetences;

    /**
     * @ORM\Column(type="text")
     * @Groups({"competence:write", "competence:read", "gc:read", "competence_only", "brief:read"})
     */
    private $descriptif;

     /**
     * @ORM\OneToMany(targetEntity=Niveau::class, mappedBy="competence")
     * @Groups({"competence:write", "competence:read", "gc:read","competence_only"})
     */
    private $niveaux;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $etat;

    /**
     * @ORM\OneToMany(targetEntity=CompetencesValide::class, mappedBy="competence")
     */
    private $competencesValides;


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

    
    public function __construct()
    {
   
        $this->niveaux = new ArrayCollection();
        $this->competencesValides = new ArrayCollection();
    }




    /**
     * @return Collection|Niveau[]
     */
    public function getNiveaux(): Collection
    {
        return $this->niveaux;
    }

    public function addNiveau(Niveau $niveau): self
    {
        if (!$this->niveaux->contains($niveau)) {
            $this->niveaux[] = $niveau;
            $niveau->setCompetence($this);
        }

        return $this;
    }

    public function removeNiveau(Niveau $niveau): self
    {
        if ($this->niveaux->contains($niveau)) {
            $this->niveaux->removeElement($niveau);
            // set the owning side to null (unless already changed)
            if ($niveau->getCompetence() === $this) {
                $niveau->setCompetence(null);
            }
        }

        return $this;
    }

    public function getDescriptif(): ?string
    {
        return $this->descriptif;
    }

    public function setDescriptif(string $descriptif): self
    {
        $this->descriptif = $descriptif;

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

    /**
     * @return Collection|CompetencesValide[]
     */
    public function getCompetencesValides(): Collection
    {
        return $this->competencesValides;
    }

    public function addCompetencesValide(CompetencesValide $competencesValide): self
    {
        if (!$this->competencesValides->contains($competencesValide)) {
            $this->competencesValides[] = $competencesValide;
            $competencesValide->setCompetence($this);
        }

        return $this;
    }

    public function removeCompetencesValide(CompetencesValide $competencesValide): self
    {
        if ($this->competencesValides->contains($competencesValide)) {
            $this->competencesValides->removeElement($competencesValide);
            // set the owning side to null (unless already changed)
            if ($competencesValide->getCompetence() === $this) {
                $competencesValide->setCompetence(null);
            }
        }

        return $this;
    }


}
