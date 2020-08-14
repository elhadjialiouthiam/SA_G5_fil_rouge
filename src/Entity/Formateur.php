<?php

namespace App\Entity;

use App\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\FormateurRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass=FormateurRepository::class)
 * @ApiResource(
 * normalizationContext={"groups":{"user:read"}},
 * collectionOperations={
 *      "get",
 *      "show_formateurs"={
 *          "method"="get",
 *          "route_name"="show_formateurs",
 *          "path"="/admin/users/formateurs"
 * },
 *      "add_formateur"={
 *          "method"="post",
 *          "route_name"="add_formateur"
 * }
 * },
 * itemOperations={
 *      "show_one_formateur"={
 *          "method"="get",
 *          "path"="/admin/users/formateurs/{id}",
 *          "security" = "is_granted('ROLE_ADMIN') or is_granted('ROLE_CM') or object == user",
*           "security_message" = "Seul un admin ou un CM ou le detenteur peut modifier ses informations"
 * },
 *      "archiver_formateur"={
 *          "method"="delete",
 *          "path"="/admin/users/formateurs/{id}",
 *          "security" = "is_granted('ROLE_ADMIN')",
 *          "security_message" = "Seuls les admins ont le droit d'acces à ce ressource"
 * },
 *      "update_formateur"={
 *          "method"="put",
 *          "path"="/admin/users/formateurs/{id}",
 *          "security" = "is_granted('ROLE_ADMIN')",
 *          "security_message" = "Seuls les admins ont le droit d'acces à ce ressource"
 * }
 * }
 * )
 */
class Formateur extends User
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\ManyToMany(targetEntity=Groupe::class, mappedBy="formateurs")
     */
    private $groupes;

    /**
     * @ORM\ManyToMany(targetEntity=Promos::class, mappedBy="formateur")
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

    /**
     * @return Collection|Promos[]
     */
    public function getPromos(): Collection
    {
        return $this->promos;
    }

    public function addPromo(Promos $promo): self
    {
        if (!$this->promos->contains($promo)) {
            $this->promos[] = $promo;
            $promo->addFormateur($this);
        }

        return $this;
    }

    public function removePromo(Promos $promo): self
    {
        if ($this->promos->contains($promo)) {
            $this->promos->removeElement($promo);
            $promo->removeFormateur($this);
        }

        return $this;
    }

}
