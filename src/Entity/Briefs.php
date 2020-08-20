<?php

namespace App\Entity;

use App\Repository\BriefsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BriefsRepository::class)
 */
class Briefs
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $titre;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $enonce;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $context;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="date")
     */
    private $dateEcheance;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $etats;

    /**
     * @ORM\OneToMany(targetEntity=Ressources::class, mappedBy="briefs")
     */
    private $ressources;

    /**
     * @ORM\ManyToMany(targetEntity=Niveau::class, inversedBy="briefs")
     */
    private $niveaux;

    /**
     * @ORM\ManyToMany(targetEntity=Tag::class, inversedBy="briefs")
     */
    private $tags;

    /**
     * @ORM\ManyToOne(targetEntity=Formateur::class, inversedBy="briefs")
     */
    private $formateur;

    /**
     * @ORM\ManyToMany(targetEntity=Groupe::class, inversedBy="briefs")
     */
    private $groupes;

    /**
     * @ORM\ManyToMany(targetEntity=Apprenant::class, inversedBy="briefs")
     */
    private $apprenants;

    /**
     * @ORM\OneToMany(targetEntity=BriefPromo::class, mappedBy="briefs")
     */
    private $briefPromos;

    /**
     * @ORM\ManyToMany(targetEntity=LivrableAttendu::class, mappedBy="briefs")
     */
    private $livrableAttendus;

    public function __construct()
    {
        $this->ressources = new ArrayCollection();
        $this->niveaux = new ArrayCollection();
        $this->tags = new ArrayCollection();
        $this->groupes = new ArrayCollection();
        $this->apprenants = new ArrayCollection();
        $this->briefPromos = new ArrayCollection();
        $this->livrableAttendus = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getEnonce(): ?string
    {
        return $this->enonce;
    }

    public function setEnonce(string $enonce): self
    {
        $this->enonce = $enonce;

        return $this;
    }

    public function getContext(): ?string
    {
        return $this->context;
    }

    public function setContext(string $context): self
    {
        $this->context = $context;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getDateEcheance(): ?\DateTimeInterface
    {
        return $this->dateEcheance;
    }

    public function setDateEcheance(\DateTimeInterface $dateEcheance): self
    {
        $this->dateEcheance = $dateEcheance;

        return $this;
    }

    public function getEtats(): ?string
    {
        return $this->etats;
    }

    public function setEtats(string $etats): self
    {
        $this->etats = $etats;

        return $this;
    }

    /**
     * @return Collection|Ressources[]
     */
    public function getRessources(): Collection
    {
        return $this->ressources;
    }

    public function addRessource(Ressources $ressource): self
    {
        if (!$this->ressources->contains($ressource)) {
            $this->ressources[] = $ressource;
            $ressource->setBriefs($this);
        }

        return $this;
    }

    public function removeRessource(Ressources $ressource): self
    {
        if ($this->ressources->contains($ressource)) {
            $this->ressources->removeElement($ressource);
            // set the owning side to null (unless already changed)
            if ($ressource->getBriefs() === $this) {
                $ressource->setBriefs(null);
            }
        }

        return $this;
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
        }

        return $this;
    }

    public function removeNiveau(Niveau $niveau): self
    {
        if ($this->niveaux->contains($niveau)) {
            $this->niveaux->removeElement($niveau);
        }

        return $this;
    }

    /**
     * @return Collection|Tag[]
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Tag $tag): self
    {
        if (!$this->tags->contains($tag)) {
            $this->tags[] = $tag;
        }

        return $this;
    }

    public function removeTag(Tag $tag): self
    {
        if ($this->tags->contains($tag)) {
            $this->tags->removeElement($tag);
        }

        return $this;
    }

    public function getFormateur(): ?Formateur
    {
        return $this->formateur;
    }

    public function setFormateur(?Formateur $formateur): self
    {
        $this->formateur = $formateur;

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
        }

        return $this;
    }

    public function removeGroupe(Groupe $groupe): self
    {
        if ($this->groupes->contains($groupe)) {
            $this->groupes->removeElement($groupe);
        }

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
        }

        return $this;
    }

    public function removeApprenant(Apprenant $apprenant): self
    {
        if ($this->apprenants->contains($apprenant)) {
            $this->apprenants->removeElement($apprenant);
        }

        return $this;
    }

    /**
     * @return Collection|BriefPromo[]
     */
    public function getBriefPromos(): Collection
    {
        return $this->briefPromos;
    }

    public function addBriefPromo(BriefPromo $briefPromo): self
    {
        if (!$this->briefPromos->contains($briefPromo)) {
            $this->briefPromos[] = $briefPromo;
            $briefPromo->setBriefs($this);
        }

        return $this;
    }

    public function removeBriefPromo(BriefPromo $briefPromo): self
    {
        if ($this->briefPromos->contains($briefPromo)) {
            $this->briefPromos->removeElement($briefPromo);
            // set the owning side to null (unless already changed)
            if ($briefPromo->getBriefs() === $this) {
                $briefPromo->setBriefs(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|LivrableAttendu[]
     */
    public function getLivrableAttendus(): Collection
    {
        return $this->livrableAttendus;
    }

    public function addLivrableAttendu(LivrableAttendu $livrableAttendu): self
    {
        if (!$this->livrableAttendus->contains($livrableAttendu)) {
            $this->livrableAttendus[] = $livrableAttendu;
            $livrableAttendu->addBrief($this);
        }

        return $this;
    }

    public function removeLivrableAttendu(LivrableAttendu $livrableAttendu): self
    {
        if ($this->livrableAttendus->contains($livrableAttendu)) {
            $this->livrableAttendus->removeElement($livrableAttendu);
            $livrableAttendu->removeBrief($this);
        }

        return $this;
    }
}
