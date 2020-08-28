<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\LivrablesPartielsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=LivrablesPartielsRepository::class)
 * @ApiResource(
 *  collectionOperations={
 *     "get_competences_by_apprenant"={
 *         "method"="GET",
 *         "controller"=LivrablesPartielsController::class,
 *         "access_control"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR'))",
 *         "route_name"="show_competences_by_apprenant"
 *     },
 *     "get_competences_by_apprenant_id"={
 *         "method"="GET",
 *         "controller"=LivrablesPartielsController::class,
 *         "access_control"="(is_granted('ROLE_APPRENANT') or is_granted('ROLE_ADMIN'))",
 *         "route_name"="show_competences_by_apprenant_id"
 *     },
 *     "get_statistiques_by_apprenant_id"={
 *         "method"="GET",
 *         "controller"=LivrablesPartielsController::class,
 *         "access_control"="(is_granted('ROLE_APPRENANT') or is_granted('ROLE_ADMIN'))",
 *         "route_name"="show_statistiques_by_apprenant_id"
 *     },
 *     "get_statistiques_by_competences"={
 *         "method"="GET",
 *         "controller"=LivrablesPartielsController::class,
 *         "access_control"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM'))",
 *         "route_name"="show_statistiques_by_competences"
 *     },
 *     "get_commentaires_by_LivrablesPartiels"={
 *         "method"="GET",
 *         "controller"=LivrablesPartielsController::class,
 *         "access_control"="(is_granted('ROLE_FORMATEUR') or is_granted('ROLE_APPRENANT) or is_granted('ROLE_ADMIN'))",
 *         "route_name"="get_commentaires_by_livrablePartiel"
 *     },
 *     "post_commentaire_by_formateur"={
 *         "method"="POST",
 *         "access_control"="(is_granted('ROLE_FORMATEUR') or is_granted('ROLE_ADMIN'))",
 *         "path"="/formateurs/livrablespartiels/{id_livrable}/commentaires",
 *         "path"="/apprenant/livrablespartiels/{id_livrable}/commentaires",
 *     }, 
 *     "post_commentaire_by_apprenant"={
 *         "method"="POST",
 *         "controller"=LivrablesPartielsController::class,
 *         "path"="/apprenant/livrablespartiels/id/commentaires",
 *         "access_control"="(is_granted('ROLE_APPRENANT') or is_granted('ROLE_ADMIN'))",
 *     },
 *  },
 *  itemOperations={
 *      "add_livrable_partiel_by_formateur"={
 *         "method"="PUT",
 *         "controller"=LivrablesPartielsController::class,
 *         "access_control"="(is_granted('ROLE_FORMATEUR') or is_granted('ROLE_ADMIN'))",
 *         "route_name"="put_livrable_partiel_by_formateur"
 *     },
 * 
 *     "add_statut_by_apprenant"={
 *         "method"="PUT",
 *         "controller"=LivrablesPartielsController::class,
 *         "access_control"="(is_granted('ROLE_FORMATEUR') or is_granted('ROLE_ADMIN'))",
 *         "route_name"="put_statut_by_apprenant"
 *     },
 *  },
 * )
 */
class LivrablesPartiels
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
     * @ORM\Column(type="string", length=255)
     */
    private $lien;

    /**
     * @ORM\Column(type="blob")
     */
    private $fichier;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="date")
     */
    private $dateLivraison;

    /**
     * @ORM\ManyToOne(targetEntity=BriefPromo::class, inversedBy="livrablesPartiels")
     */
    private $briefPromo;

    /**
     * @ORM\ManyToMany(targetEntity=LivrableAttendu::class, mappedBy="livrablesPartiels")
     */
    private $livrableAttendus;

    /**
     * @ORM\OneToMany(targetEntity=ApprenantLivrablepratielle::class, mappedBy="livrablesPartiels")
     * @Groups({"commentaire:read"})
     */
    private $apprenantLivrablepratielles;

    /**
     * @ORM\Column(type="boolean")
     */
    private $deleted=false;

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

    public function getDeleted(): ?bool
    {
        return $this->deleted;
    }

    public function setDeleted(bool $deleted): self
    {
        $this->deleted = $deleted;

        return $this;
    }
}
