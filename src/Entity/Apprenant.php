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
     */
        private $promos;


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
}
