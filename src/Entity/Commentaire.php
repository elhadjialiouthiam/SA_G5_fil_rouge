<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\CommentaireRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=CommentaireRepository::class)
 * @ORM\Entity(repositoryClass=LivrablesAprennantRepository::class)
 * @ApiResource(
 * )
 */
class Commentaire
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"commentaire:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"commentaire:read"})
     */
    private $contenu;

    /**
     * @ORM\Column(type="date")
     * @Groups({"commentaire:read"})
     */
    private $date;

    /**
     * @ORM\ManyToOne(targetEntity=ApprenantLivrablepratielle::class, inversedBy="commentaire")
     */
    private $apprenantLivrablepratielle;

    /**
     * @ORM\ManyToOne(targetEntity=Formateur::class, inversedBy="commentaires")
     * @Groups({"commentaire:read"})
     */
    private $formateur;

    /**
     * @ORM\Column(type="blob")
     */
    private $PieceJointe;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContenu(): ?string
    {
        return $this->contenu;
    }

    public function setContenu(string $contenu): self
    {
        $this->contenu = $contenu;

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

    public function getApprenantLivrablepratielle(): ?ApprenantLivrablepratielle
    {
        return $this->apprenantLivrablepratielle;
    }

    public function setApprenantLivrablepratielle(?ApprenantLivrablepratielle $apprenantLivrablepratielle): self
    {
        $this->apprenantLivrablepratielle = $apprenantLivrablepratielle;

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

    public function getPieceJointe()
    {
        return $this->PieceJointe;
    }

    public function setPieceJointe($PieceJointe): self
    {
        $this->PieceJointe = $PieceJointe;

        return $this;
    }
}
