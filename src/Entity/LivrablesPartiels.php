<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use App\Repository\LivrablesPartielsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=LivrablesPartielsRepository::class)
 */
class LivrablesPartiels
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"briefOfPromo:read"})
     * @Groups({"apprenantlivable:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"briefOfPromo:read"})
     * @Groups({"apprenantlivable:read"})
     */
    private $libelle;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"briefOfPromo:read"})
     * @Groups({"apprenantlivable:read"})
     */
    private $lien;

    /**
     * @ORM\Column(type="blob")
     * @Groups({"briefOfPromo:read"})
     */
    private $fichier;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"briefOfPromo:read"})
     * @Groups({"apprenantlivable:read"})
     */
    private $createdAt;

    /**
     * @ORM\Column(type="date")
     * @Groups({"briefOfPromo:read"})
     * @Groups({"apprenantlivable:read"})
     */
    private $dateLivraison;

    /**
     * @ORM\ManyToOne(targetEntity=BriefPromo::class, inversedBy="livrablePartiels")
     * @Groups({"apprenantlivable:read"})
     */
    private $briefPromo;

    /**
     * @ORM\ManyToMany(targetEntity=LivrableAttendu::class, mappedBy="livrablesPartiels")
     */
    private $livrableAttendus;

    /**
     * @ORM\OneToMany(targetEntity=ApprenantLivrablepratielle::class, mappedBy="livrablePartielle")
     */
    private $apprenantLivrablepratielles;

    public function __construct()
    {
        $this->livrableAttendus = new ArrayCollection();
        $this->apprenantLivrablepratielles = new ArrayCollection();
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

    public function getLien(): ?string
    {
        return $this->lien;
    }

    public function setLien(string $lien): self
    {
        $this->lien = $lien;

        return $this;
    }

    public function getFichier()
    {
        return $this->fichier;
    }

    public function setFichier($fichier): self
    {
        $this->fichier = $fichier;

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

    public function getDateLivraison(): ?\DateTimeInterface
    {
        return $this->dateLivraison;
    }

    public function setDateLivraison(\DateTimeInterface $dateLivraison): self
    {
        $this->dateLivraison = $dateLivraison;

        return $this;
    }

    public function getBriefPromo(): ?BriefPromo
    {
        return $this->briefPromo;
    }

    public function setBriefPromo(?BriefPromo $briefPromo): self
    {
        $this->briefPromo = $briefPromo;

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
            $livrableAttendu->addLivrablesPartiel($this);
        }

        return $this;
    }

    public function removeLivrableAttendu(LivrableAttendu $livrableAttendu): self
    {
        if ($this->livrableAttendus->contains($livrableAttendu)) {
            $this->livrableAttendus->removeElement($livrableAttendu);
            $livrableAttendu->removeLivrablesPartiel($this);
        }

        return $this;
    }

    /**
     * @return Collection|ApprenantLivrablepratielle[]
     */
    public function getApprenantLivrablepratielles(): Collection
    {
        return $this->apprenantLivrablepratielles;
    }

    public function addApprenantLivrablepratielle(ApprenantLivrablepratielle $apprenantLivrablepratielle): self
    {
        if (!$this->apprenantLivrablepratielles->contains($apprenantLivrablepratielle)) {
            $this->apprenantLivrablepratielles[] = $apprenantLivrablepratielle;
            $apprenantLivrablepratielle->setLivrablePartielle($this);
        }

        return $this;
    }

    public function removeApprenantLivrablepratielle(ApprenantLivrablepratielle $apprenantLivrablepratielle): self
    {
        if ($this->apprenantLivrablepratielles->contains($apprenantLivrablepratielle)) {
            $this->apprenantLivrablepratielles->removeElement($apprenantLivrablepratielle);
            // set the owning side to null (unless already changed)
            if ($apprenantLivrablepratielle->getLivrablePartielle() === $this) {
                $apprenantLivrablepratielle->setLivrablePartielle(null);
            }
        }

        return $this;
    }
}
