<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Repository\ApprenantLivrablepratielleRepository;

/**
 * @ORM\Entity(repositoryClass=ApprenantLivrablepratielleRepository::class)
 */
class ApprenantLivrablepratielle
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"apprenantlivable:read"})
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"apprenantlivable:read"})
     */
    private $etat;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"apprenantlivable:read"})
     */
    private $delai;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"apprenantlivable:read"})
     */
    private $dateRendu;

    /**
     * @ORM\ManyToOne(targetEntity=LivrablesPartiels::class, inversedBy="apprenantLivrablepratielles")
     * @Groups({"apprenantlivable:read"})
     */
    private $livrablePartielle;

    /**
     * @ORM\ManyToOne(targetEntity=Apprenant::class, inversedBy="apprenantLivrablepratielles")
     */
    private $appranant;

    /**
     * @ORM\OneToMany(targetEntity=Commentaire::class, mappedBy="apprenantLivrablepratielle")
     */
    private $commentaire;

    public function __construct()
    {
        $this->commentaire = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDelai(): ?\DateTimeInterface
    {
        return $this->delai;
    }

    public function setDelai(\DateTimeInterface $delai): self
    {
        $this->delai = $delai;

        return $this;
    }

    public function getDateRendu(): ?\DateTimeInterface
    {
        return $this->dateRendu;
    }

    public function setDateRendu(\DateTimeInterface $dateRendu): self
    {
        $this->dateRendu = $dateRendu;

        return $this;
    }

    public function getLivrablePartielle(): ?LivrablesPartiels
    {
        return $this->livrablePartielle;
    }

    public function setLivrablePartielle(?LivrablesPartiels $livrablePartielle): self
    {
        $this->livrablePartielle = $livrablePartielle;

        return $this;
    }

    public function getAppranant(): ?Apprenant
    {
        return $this->appranant;
    }

    public function setAppranant(?Apprenant $appranant): self
    {
        $this->appranant = $appranant;

        return $this;
    }

    /**
     * @return Collection|Commentaire[]
     */
    public function getCommentaire(): Collection
    {
        return $this->commentaire;
    }

    public function addCommentaire(Commentaire $commentaire): self
    {
        if (!$this->commentaire->contains($commentaire)) {
            $this->commentaire[] = $commentaire;
            $commentaire->setApprenantLivrablepratielle($this);
        }

        return $this;
    }

    public function removeCommentaire(Commentaire $commentaire): self
    {
        if ($this->commentaire->contains($commentaire)) {
            $this->commentaire->removeElement($commentaire);
            // set the owning side to null (unless already changed)
            if ($commentaire->getApprenantLivrablepratielle() === $this) {
                $commentaire->setApprenantLivrablepratielle(null);
            }
        }

        return $this;
    }
}
