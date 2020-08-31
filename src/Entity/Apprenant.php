<?php

namespace App\Entity;

use App\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ApprenantRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=ApprenantRepository::class)
 * @ApiResource(
 * normalizationContext={"groups":{"user:read"}},
 *    collectionOperations={
 *       "apprenant_liste"={
 *         "method"="GET",
*           "route_name"="apprenant_liste"
* },
*      "add_apprenant" = {
*           "method"="POST",
*           "route_name"="add_apprenant"
*}
 * },
 *    itemOperations={
 *       "show_one_apprenant"={
 *          "method"="get",
 *          "path"="/admin/users/apprenants/{id}",
 *          "security" = "is_granted('ROLE_ADMIN') or is_granted('ROLE_CM') or is_granted('ROLE_FORMATEUR') or object == user",
 *           "security_message" = "Seul un admin ou un CM ou le detenteur peut modifier ses informations"
 * },
 *      "update_apprenant"={
 *          "method"="put",
 *          "path"="/admin/users/apprenants/{id}",
 *          "security" = "is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR') or object == user",
 *          "security_message" = "Seul un admin ou le detenteur peut modifier ses informations"
 * },
 *      "archiver_apprenant"={
 *          "method"="delete",
 *          "path"="/admin/users/apprenants/{id}",
 *          "security" = "is_granted('ROLE_ADMIN')",
 *          "security_message" = "Seul un admin archiver un apprenant"
 * }
 * }
 * )
 */
class Apprenant extends User
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"ps:read"})
     */
    protected $id;

    /**
     * @ORM\ManyToMany(targetEntity=Groupe::class, mappedBy="Apprenants")
     */
    private $groupes;

    /**
     * @ORM\ManyToOne(targetEntity=ProfilDeSortie::class, inversedBy="apprenants")
     * @Groups({"user:read"})
     */
    private $profilDeSortie;

    /**
     * @ORM\ManyToOne(targetEntity=Promos::class, inversedBy="apprenants", cascade={"persist"})
     * @Groups({"ApprenantCompetence:read"})
     */
        private $promos;


        /**
         * @ORM\OneToMany(targetEntity=LivrablesAprennant::class, mappedBy="apprenant")
         */
        private $livrablesAprennants;

        /**
         * @ORM\ManyToOne(targetEntity=BriefAprennant::class, inversedBy="apprenant")
         */
        private $briefAprennant;

        /**
         * @ORM\OneToMany(targetEntity=ApprenantLivrablepratielle::class, mappedBy="appranant")
         */
        private $apprenantLivrablepratielles;

        /**
         * @ORM\OneToMany(targetEntity=CompetencesValide::class, mappedBy="apprenant")
         * @Groups({"apprenant_competence:read",})
         */
        private $competencesValides;

        public function __construct()
        {
            $this->livrablesAprennants = new ArrayCollection();
            $this->apprenantLivrablepratielles = new ArrayCollection();
            $this->competencesValides = new ArrayCollection();
        }


    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|Groupe[]
     */
    public function getGroupes(): Collection
    {
        return $this->groupes;
    }



    public function getProfilDeSortie(): ?ProfilDeSortie
    {
        return $this->profilDeSortie;
    }

    public function setProfilDeSortie(?ProfilDeSortie $profilDeSortie): self
    {
        $this->profilDeSortie = $profilDeSortie;

        return $this;
    }

    public function getPromos(): ?Promos
    {
        return $this->promos;
    }

    public function setPromos(?Promos $promos): self
    {
        $this->promos = $promos;

        return $this;
    }





    /**
     * @return Collection|LivrablesAprennant[]
     */
    public function getLivrablesAprennants(): Collection
    {
        return $this->livrablesAprennants;
    }

    public function addLivrablesAprennant(LivrablesAprennant $livrablesAprennant): self
    {
        if (!$this->livrablesAprennants->contains($livrablesAprennant)) {
            $this->livrablesAprennants[] = $livrablesAprennant;
            $livrablesAprennant->setApprenant($this);
        }

        return $this;
    }

    public function removeLivrablesAprennant(LivrablesAprennant $livrablesAprennant): self
    {
        if ($this->livrablesAprennants->contains($livrablesAprennant)) {
            $this->livrablesAprennants->removeElement($livrablesAprennant);
            // set the owning side to null (unless already changed)
            if ($livrablesAprennant->getApprenant() === $this) {
                $livrablesAprennant->setApprenant(null);
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
            $apprenantLivrablepratielle->setAppranant($this);
        }

        return $this;
    }

    public function removeApprenantLivrablepratielle(ApprenantLivrablepratielle $apprenantLivrablepratielle): self
    {
        if ($this->apprenantLivrablepratielles->contains($apprenantLivrablepratielle)) {
            $this->apprenantLivrablepratielles->removeElement($apprenantLivrablepratielle);
            // set the owning side to null (unless already changed)
            if ($apprenantLivrablepratielle->getAppranant() === $this) {
                $apprenantLivrablepratielle->setAppranant(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|CompetencesValide[]
     */
    public function getCompetencesValides(): Collection
    {
        return $this->competencesValides;
    }

    public function addCompetencesValide(CompetencesValide $competencesValide): self
    {
        if (!$this->competencesValides->contains($competencesValide)) {
            $this->competencesValides[] = $competencesValide;
            $competencesValide->setApprenant($this);
        }

        return $this;
    }

    public function removeCompetencesValide(CompetencesValide $competencesValide): self
    {
        if ($this->competencesValides->contains($competencesValide)) {
            $this->competencesValides->removeElement($competencesValide);
            // set the owning side to null (unless already changed)
            if ($competencesValide->getApprenant() === $this) {
                $competencesValide->setApprenant(null);
            }
        }

        return $this;
    }
}
