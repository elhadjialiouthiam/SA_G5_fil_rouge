<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ChatGeneralRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=ChatGeneralRepository::class)
 */
class ChatGeneral
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
    private $libelle;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\Column(type="blob", nullable=true)
     */
    private $PJ;


    /**
     * @ORM\OneToMany(targetEntity=CommentairesGenerale::class, mappedBy="chatgeneral")
     */
    private $commentairesGenerales;

    /**
     * @ORM\OneToOne(targetEntity=Promos::class, mappedBy="chatgeneral", cascade={"persist", "remove"})
     */
    private $promos;

    public function __construct()
    {
        $this->commentairesGenerales = new ArrayCollection();
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

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getPJ()
    {
        return $this->PJ;
    }

    public function setPJ($PJ): self
    {
        $this->PJ = $PJ;

        return $this;
    }

 

    /**
     * @return Collection|CommentairesGenerale[]
     */
    public function getCommentairesGenerales(): Collection
    {
        return $this->commentairesGenerales;
    }

    public function addCommentairesGenerale(CommentairesGenerale $commentairesGenerale): self
    {
        if (!$this->commentairesGenerales->contains($commentairesGenerale)) {
            $this->commentairesGenerales[] = $commentairesGenerale;
            $commentairesGenerale->setChatgeneral($this);
        }

        return $this;
    }

    public function removeCommentairesGenerale(CommentairesGenerale $commentairesGenerale): self
    {
        if ($this->commentairesGenerales->contains($commentairesGenerale)) {
            $this->commentairesGenerales->removeElement($commentairesGenerale);
            // set the owning side to null (unless already changed)
            if ($commentairesGenerale->getChatgeneral() === $this) {
                $commentairesGenerale->setChatgeneral(null);
            }
        }

        return $this;
    }

    public function getPromos(): ?Promos
    {
        return $this->promos;
    }

    public function setPromos(?Promos $promos): self
    {
        $this->promos = $promos;

        // set (or unset) the owning side of the relation if necessary
        $newChatgeneral = null === $promos ? null : $this;
        if ($promos->getChatgeneral() !== $newChatgeneral) {
            $promos->setChatgeneral($newChatgeneral);
        }

        return $this;
    }

}
