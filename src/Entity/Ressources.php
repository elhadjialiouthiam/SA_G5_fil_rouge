<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\RessourcesRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=RessourcesRepository::class)
 * @ApiResource()
 */
class Ressources
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"brief:read"})
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"brief:read"})
     */
    private $lien;

    /**
     * @ORM\Column(type="blob", nullable=true)
     * @Groups({"brief:read"})
     */
    private $fichier;

    /**
     * @ORM\ManyToOne(targetEntity=Briefs::class, inversedBy="ressources")
     */
    private $briefs;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getBriefs(): ?Briefs
    {
        return $this->briefs;
    }

    public function setBriefs(?Briefs $briefs): self
    {
        $this->briefs = $briefs;

        return $this;
    }
}
