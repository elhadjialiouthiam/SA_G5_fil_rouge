<?php

namespace App\Entity;

use App\Entity\User;
use App\Entity\Promos;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\FormateurRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

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
     * @Groups({"briefOfGroup:read"})
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

    /**
     * @ORM\OneToMany(targetEntity=Briefs::class, mappedBy="formateur")
     */
    private $briefs;

    /**
     * @ORM\OneToMany(targetEntity=Commentaire::class, mappedBy="formateur")
     */
    private $commentaires;

    public function __construct()
    {
        $this->briefs = new ArrayCollection();
        $this->commentaires = new ArrayCollection();
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

    /**
     * @return Collection|Briefs[]
     */
    public function getBriefs(): Collection
    {
        return $this->briefs;
    }

    public function addBrief(Briefs $brief): self
    {
        if (!$this->briefs->contains($brief)) {
            $this->briefs[] = $brief;
            $brief->setFormateur($this);
        }

        return $this;
    }

    public function removeBrief(Briefs $brief): self
    {
        if ($this->briefs->contains($brief)) {
            $this->briefs->removeElement($brief);
            // set the owning side to null (unless already changed)
            if ($brief->getFormateur() === $this) {
                $brief->setFormateur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Commentaire[]
     */
    public function getCommentaires(): Collection
    {
        return $this->commentaires;
    }

    public function addCommentaire(Commentaire $commentaire): self
    {
        if (!$this->commentaires->contains($commentaire)) {
            $this->commentaires[] = $commentaire;
            $commentaire->setFormateur($this);
        }

        return $this;
    }

    public function removeCommentaire(Commentaire $commentaire): self
    {
        if ($this->commentaires->contains($commentaire)) {
            $this->commentaires->removeElement($commentaire);
            // set the owning side to null (unless already changed)
            if ($commentaire->getFormateur() === $this) {
                $commentaire->setFormateur(null);
            }
        }

        return $this;
    }

}
