<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\BriefsRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=BriefsRepository::class)
 * @ApiResource(
 *      collectionOperations={
 *          "getAllBriefs" = {
 *          "method"="GET",
 *          "route_name"="getAllBriefs",
 *          "normalization_context" = {"groups"="brief:read"},
 * } ,
 *   "getBriefsOfAPromo"={
 *          "method"="GET",
 *          "route_name"="getBriefsOfAPromo"
 *          
 * },
 * "briefOfApprenantPromo"={
 *      "method"="get",
 *      "route_name"="briefOfApprenantPromo"
 * }
 * },
 *  itemOperations={
 *   "get",
 *      "getBriefOfGroup"={
 *          "method"="get",
 *          "path"="/formateurs/briefs/{id}",
 *          "default"={"id"=null}
 * },
 *      "getBriefOfGroup"={
 *           "method"="GET",
 *          "route_name"="getBriefOfGroup"
 * },
 * "getBriefsBrouillon"={
 *      "method"="GET",
 *      "route_name"="getBriefsBrouillon"
 * },
 * "getBriefsValides"={
 *       "method"="GET",
 *      "route_name"="getBriefsValides"
 * },
 * "getOneBriefInPromo"={
 *      "method"="GET",
 *      "route_name"="getBriefInPromo"
 * }
 *
 * }
 * 
 * )
 */
class Briefs
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"briefOfGroup:read", "brief:read"})
     */
    protected $id;

    /**
     * @Groups({"brief:read", "briefOfGroup:read"})
     * @ORM\Column(type="string", length=255)
     */
    private $titre;

    /**
     * @Groups({"brief:read", "briefOfGroup:read"})
     * @ORM\Column(type="string", length=255)
     */
    private $enonce;

    /**
     * @Groups({"brief:read"})
     * @ORM\Column(type="string", length=255)
     */
    private $context;

    /**
     * @Groups({"brief:read", "briefOfGroup:read"})
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @Groups({"brief:read", "briefOfGroup:read"})
     * @ORM\Column(type="date")
     */
    private $dateEcheance;

    /**
     * @Groups({"brief:read"})
     * @ORM\Column(type="string", length=255)
     */
    private $etats;

    /**
     * @Groups({"brief:read"})
     * @ORM\OneToMany(targetEntity=Ressources::class, mappedBy="briefs")
     */
    private $ressources;

    /**
     * @Groups({"brief:read", "briefOfGroup:read", "briefEtat:read"})
     * @ORM\ManyToMany(targetEntity=Niveau::class, inversedBy="briefs")
     */
    private $niveaux;

    /**
     * @Groups({"brief:read", "briefOfGroup:read", "briefEtat:read"})
     * @ORM\ManyToMany(targetEntity=Tag::class, inversedBy="briefs")
     */
    private $tags;

    /**
     * @ORM\ManyToOne(targetEntity=Formateur::class, inversedBy="briefs")
     * @Groups({"briefOfGroup:read"})
     */
    private $formateur;


    /**
     * @ORM\OneToMany(targetEntity=BriefPromo::class, mappedBy="briefs")
     * @Groups({"briefOfPromo:read"})
     */
    private $briefPromos;

    /**
     * @ORM\ManyToMany(targetEntity=LivrableAttendu::class, mappedBy="briefs")
     * @Groups({"brief:read"})
     */
    private $livrableAttendus;

    /**
     * @ORM\ManyToOne(targetEntity=BriefGroupe::class, inversedBy="briefs")
     * @Groups({"briefOfGroup:read"})
     */
    private $briefGroupe;

    public function __construct()
    {
        $this->ressources = new ArrayCollection();
        $this->niveaux = new ArrayCollection();
        $this->tags = new ArrayCollection();
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
