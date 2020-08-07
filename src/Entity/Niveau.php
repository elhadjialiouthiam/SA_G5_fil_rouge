<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\NiveauRepository;
use ApiPlatform\Core\Annotation\ApiResource;

/**
 * @ORM\Entity(repositoryClass=NiveauRepository::class)
 * @ApiResource()
 */
class Niveau
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
    private $critereEvaluation;

    /**
     * @ORM\ManyToOne(targetEntity=Competence::class, inversedBy="niveaux")
     * @ORM\JoinColumn(nullable=false)
     */
    private $competence;

    /**
     * @ORM\Column(type="text")
     */
    private $groupActions;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCritereEvaluation(): ?string
    {
        return $this->critereEvaluation;
    }

    public function setCritereEvaluation(string $critereEvaluation): self
    {
        $this->critereEvaluation = $critereEvaluation;

        return $this;
    }

    public function getCompetence(): ?Competence
    {
        return $this->competence;
    }

    public function setCompetence(?Competence $competence): self
    {
        $this->competence = $competence;

        return $this;
    }

    public function getGroupActions(): ?string
    {
        return $this->groupActions;
    }

    public function setGroupActions(string $groupActions): self
    {
        $this->groupActions = $groupActions;

        return $this;
    }
}
