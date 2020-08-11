<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ApprenantRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *     normalizationContext={"groups"={"user:read"}},
 *     itemOperations={
 *          "get_student"={
 *              "method"="GET",
 *              "path"="/apprenants/{id}",
 *              "requirements"={"id"="\d+"},
 *              "defaults"={"id"=null},
 *              "security"="(is_granted('ROLE_ADMIN'))",
 *              "security_message"="Vous n'avez pas access à cette Ressource"
 *          },
 *          "set_student"={
 *              "method"="PUT",
 *              "path"="/apprenants/{id}",
 *              "requirements"={"id"="\d+"},
 *              "security"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR')) or is_granted('ROLE_CM') or is_granted('ROLE_APPRENANT'))",
 *              "security_message"="Vous n'avez pas access à cette Ressource"
 *          }
 *     },
 *     collectionOperations={
 *          "add_student"={
 *              "method"="POST",
 *              "path"="/apprenants",
 *              "security"="is_granted('ROLE_ADMIN')",
 *              "security_message"="Vous n'avez pas access à cette Ressource"
 *          },
 *          "get_students"={
 *              "method"="GET",
 *              "path"="/apprenants",
 *              "security"="is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR)",
 *              "security_message"="Vous n'avez pas access à cette Ressource",
 *
 *          },
 *     }
 * )
 * @ORM\Entity(repositoryClass=ApprenantRepository::class)
 * 
 */
class Apprenant extends User
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"promos:read","group:read","promoFormateurApprenant:read_all","promo_groupe_apprenants:read","promo_ref_formateurs_apprenants:read"})
     */
    private $id;

    /**
     * @ORM\ManyToMany(targetEntity=Groupe::class, mappedBy="apprenant")
     */
    private $groupes;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isConnected;

    /**
     * @ORM\ManyToOne(targetEntity=Promos::class, inversedBy="apprenants")
     */
    private $promos;

    public function __construct()
    {
        $this->groupes = new ArrayCollection();
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

    public function addGroupe(Groupe $groupe): self
    {
        if (!$this->groupes->contains($groupe)) {
            $this->groupes[] = $groupe;
            $groupe->addApprenant($this);
        }

        return $this;
    }

    public function removeGroupe(Groupe $groupe): self
    {
        if ($this->groupes->contains($groupe)) {
            $this->groupes->removeElement($groupe);
            $groupe->removeApprenant($this);
        }

        return $this;
    }

    public function getIsConnected(): ?bool
    {
        return $this->isConnected;
    }

    public function setIsConnected(bool $isConnected): self
    {
        $this->isConnected = $isConnected;

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
}
