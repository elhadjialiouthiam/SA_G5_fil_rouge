<?php

namespace App\Entity;

use App\Repository\LivrableAttenduRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LivrableAttenduRepository::class)
 */
class LivrableAttendu
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateLivraison;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $libelle;

    /**
     * @ORM\ManyToMany(targetEntity=Briefs::class, inversedBy="livrableAttendus")
     */
    private $briefs;

    /**
     * @ORM\ManyToMany(targetEntity=Livrablespartiels::class, inversedBy="livrableAttendus")
     */
    private $livrablesPartiels;

    /**
     * @ORM\OneToMany(targetEntity=LivrablesAprennant::class, mappedBy="livrableAttendu")
     */
    private $LivrablesAprennant;

    public function __construct()
    {
        $this->briefs = new ArrayCollection();
        $this->livrablesPartiels = new ArrayCollection();
        $this->LivrablesAprennant = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDateLivraison(): ?\DateTimeInterface
    {
        return $this->dateLivraison;
    }

    public function setDateLivraison(\DateTimeInterface $dateLivraison): self
    {
        $this->dateLivraison = $dateLivraison;

        return $this;
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

    /**
     * @return Collection|Briefs[]
     */
    public function getBriefs(): Collection
    {
        return $this->briefs;
    }

    public function addBrief(Briefs $brief): self
    {
        if (!$this->briefs->contains($brief)) {
            $this->briefs[] = $brief;
        }

        return $this;
    }

    public function removeBrief(Briefs $brief): self
    {
        if ($this->briefs->contains($brief)) {
            $this->briefs->removeElement($brief);
        }

        return $this;
    }

    /**
     * @return Collection|Livrablespartiels[]
     */
    public function getLivrablesPartiels(): Collection
    {
        return $this->livrablesPartiels;
    }

    public function addLivrablesPartiel(Livrablespartiels $livrablesPartiel): self
    {
        if (!$this->livrablesPartiels->contains($livrablesPartiel)) {
            $this->livrablesPartiels[] = $livrablesPartiel;
        }

        return $this;
    }

    public function removeLivrablesPartiel(Livrablespartiels $livrablesPartiel): self
    {
        if ($this->livrablesPartiels->contains($livrablesPartiel)) {
            $this->livrablesPartiels->removeElement($livrablesPartiel);
        }

        return $this;
    }

    /**
     * @return Collection|LivrablesAprennant[]
     */
    public function getLivrablesAprennant(): Collection
    {
        return $this->LivrablesAprennant;
    }

    public function addLivrablesAprennant(LivrablesAprennant $livrablesAprennant): self
    {
        if (!$this->LivrablesAprennant->contains($livrablesAprennant)) {
            $this->LivrablesAprennant[] = $livrablesAprennant;
            $livrablesAprennant->setLivrableAttendu($this);
        }

        return $this;
    }

    public function removeLivrablesAprennant(LivrablesAprennant $livrablesAprennant): self
    {
        if ($this->LivrablesAprennant->contains($livrablesAprennant)) {
            $this->LivrablesAprennant->removeElement($livrablesAprennant);
            // set the owning side to null (unless already changed)
            if ($livrablesAprennant->getLivrableAttendu() === $this) {
                $livrablesAprennant->setLivrableAttendu(null);
            }
        }

        return $this;
    }
}
