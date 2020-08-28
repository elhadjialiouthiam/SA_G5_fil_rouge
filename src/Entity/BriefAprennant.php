<?php

namespace App\Entity;

use App\Repository\BriefAprennantRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BriefAprennantRepository::class)
 */
class BriefAprennant
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $statut;

    /**
     * @ORM\OneToMany(targetEntity=Apprenant::class, mappedBy="briefAprennant")
     */
    private $apprenant;

    /**
     * @ORM\OneToMany(targetEntity=BriefPromo::class, mappedBy="briefAprennant")
     */
    private $briefPromo;

    public function __construct()
    {
        $this->apprenant = new ArrayCollection();
        $this->briefPromo = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * @return Collection|Apprenant[]
     */
    public function getApprenant(): Collection
    {
        return $this->apprenant;
    }

    public function addApprenant(Apprenant $apprenant): self
    {
        if (!$this->apprenant->contains($apprenant)) {
            $this->apprenant[] = $apprenant;
            $apprenant->setBriefAprennant($this);
        }

        return $this;
    }

    public function removeApprenant(Apprenant $apprenant): self
    {
        if ($this->apprenant->contains($apprenant)) {
            $this->apprenant->removeElement($apprenant);
            // set the owning side to null (unless already changed)
            if ($apprenant->getBriefAprennant() === $this) {
                $apprenant->setBriefAprennant(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|BriefPromo[]
     */
    public function getBriefPromo(): Collection
    {
        return $this->briefPromo;
    }

    public function addBriefPromo(BriefPromo $briefPromo): self
    {
        if (!$this->briefPromo->contains($briefPromo)) {
            $this->briefPromo[] = $briefPromo;
            $briefPromo->setBriefAprennant($this);
        }

        return $this;
    }

    public function removeBriefPromo(BriefPromo $briefPromo): self
    {
        if ($this->briefPromo->contains($briefPromo)) {
            $this->briefPromo->removeElement($briefPromo);
            // set the owning side to null (unless already changed)
            if ($briefPromo->getBriefAprennant() === $this) {
                $briefPromo->setBriefAprennant(null);
            }
        }

        return $this;
    }
}
