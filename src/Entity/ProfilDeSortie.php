<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ProfilDeSortieRepository;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 * denormalizationContext={"groups":{"ps:write"}},
 *   collectionOperations={
 *      "addProfiDeSortie"={
 *          "method"="post",
 *          "path"="api/admin/profilsorties",
 *          "security" = "is_granted('ROLE_FORMATEUR')",
 *           "security_message" = "Accès non autorisé"
 * },
 *      "show_profil_sortie" = {
 *          "method"="GET",
 *          "route_name"="show_profil_sortie"
 * },
 *      "getStudentsByPs"={
 *          "method"="GET",
 *          "route_name"="getStudentsByPs"
 * }
 * },
 *    itemOperations={
 * "get",
 *          "getStudentsOfAPs"={
 *              "method"="get",
 *              "route_name"="getStudentsOfAPs"
 * },
 *          "update_ps"={
 *              "method"="put",
 *              "path"="/admin/profilsorties/{id}",
 *              "security" = "is_granted('ROLE_FORMATEUR')",
 *              "security_message" = "Accès non autorisé"
 * }
 * }
 * )
 * @ORM\Entity(repositoryClass=ProfilDeSortieRepository::class)
 */
class ProfilDeSortie
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"ps:read", "ps:write"})
     */
    private $libelle;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $etat;

    /**
     * @ORM\OneToMany(targetEntity=Apprenant::class, mappedBy="profilDeSortie")
     * @Groups({"ps:read", "ps:write"})
     */
    private $apprenants;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->apprenants = new ArrayCollection();
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

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(?string $etat): self
    {
        $this->etat = $etat;

        return $this;
    }

    /**
     * @return Collection|Apprenant[]
     */
    public function getApprenants(): Collection
    {
        return $this->apprenants;
    }

    public function addApprenant(Apprenant $apprenant): self
    {
        if (!$this->apprenants->contains($apprenant)) {
            $this->apprenants[] = $apprenant;
            $apprenant->setProfilDeSortie($this);
        }

        return $this;
    }

    public function removeApprenant(Apprenant $apprenant): self
    {
        if ($this->apprenants->contains($apprenant)) {
            $this->apprenants->removeElement($apprenant);
            // set the owning side to null (unless already changed)
            if ($apprenant->getProfilDeSortie() === $this) {
                $apprenant->setProfilDeSortie(null);
            }
        }

        return $this;
    }
}
