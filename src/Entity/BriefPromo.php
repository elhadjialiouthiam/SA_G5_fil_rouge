<?php

namespace App\Entity;

use App\Repository\BriefPromoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BriefPromoRepository::class)
 */
class BriefPromo
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Briefs::class, inversedBy="briefPromos")
     */
    private $briefs;

    /**
     * @ORM\ManyToOne(targetEntity=Promos::class, inversedBy="briefPromos")
     */
    private $promo;

    /**
     * @ORM\OneToMany(targetEntity=LivrablesPartiels::class, mappedBy="briefPromo")
     */
    private $livrablePartiels;

    /**
     * @ORM\ManyToOne(targetEntity=BriefAprennant::class, inversedBy="briefPromo")
     */
    private $briefAprennant;

    public function __construct()
    {
        $this->livrablePartiels = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBriefs(): ?Briefs
    {
        return $this->briefs;
    }

    public function setBriefs(?Briefs $briefs): self
    {
        $this->briefs = $briefs;

        return $this;
    }

    public function getPromo(): ?Promos
    {
        return $this->promo;
    }

    public function setPromo(?Promos $promo): self
    {
        $this->promo = $promo;

        return $this;
    }

    /**
     * @return Collection|LivrablesPartiels[]
     */
    public function getLivrablePartiels(): Collection
    {
        return $this->livrablePartiels;
    }

    public function addLivrablePartiel(LivrablesPartiels $livrablePartiel): self
    {
        if (!$this->livrablePartiels->contains($livrablePartiel)) {
            $this->livrablePartiels[] = $livrablePartiel;
            $livrablePartiel->setBriefPromo($this);
        }

        return $this;
    }

    public function removeLivrablePartiel(LivrablesPartiels $livrablePartiel): self
    {
        if ($this->livrablePartiels->contains($livrablePartiel)) {
            $this->livrablePartiels->removeElement($livrablePartiel);
            // set the owning side to null (unless already changed)
            if ($livrablePartiel->getBriefPromo() === $this) {
                $livrablePartiel->setBriefPromo(null);
            }
        }

        return $this;
    }

    public function getBriefAprennant(): ?BriefAprennant
    {
        return $this->briefAprennant;
    }

    public function setBriefAprennant(?BriefAprennant $briefAprennant): self
    {
        $this->briefAprennant = $briefAprennant;

        return $this;
    }
}