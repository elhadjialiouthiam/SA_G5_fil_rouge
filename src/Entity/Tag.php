<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\TagRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=TagRepository::class)
 * @ApiResource(
 *  denormalizationContext={"groups":{"user:write"}},
 *       collectionOperations={
 *          "GET"={
 *              "method"="GET",
 *              "path"="/admin/tags",
 *              "security"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR'))",
 *              "security_message"="Vous n'avez pas access à cette Ressource",
 *              "normalization_context"={"groups"={"tag:read"}}
 *          },
 *          "addTag"={
 *              "method"="POST",
 *              "path"="/admin/tags",
 *              "security_message"="Vous n'avez pas access à cette Ressource",
 *          }
 *     },
 *      itemOperations={
 *          "get"={
 *              "method"="GET",
 *              "path"="admin/tags/{id}",
 *              "defaults"={"id"=null},
 *              "requirements"={"id"="\d+"},
 *              "security"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR'))",
 *              "security_message"="Vous n'avez pas access à cette Ressource",
 *              "normalization_context"={"groups"={"tag:read"}}
 *          },
 *          "editTag"={
 *              "method"="PUT",
 *              "path"="admin/tags/{id}",
 *              "defaults"={"id"=null},
 *              "requirements"={"id"="\d+"},
 *              "security"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR'))",
 *              "security_message"="Vous n'avez pas access à cette Ressource",
 *          }
 *      }
 * 
 * )
 */
class Tag
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"tagsInGrpeTag:read","tag:read","Groupe_tag:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"tagsInGrpeTag:read","tag:read","Groupe_tag:read"})
     * @Assert\NotBlank(
     *      message="Le libelle est obligatoire"
     * )
     * @Groups({"user:write"})
     */
    private $libelle;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"tagsInGrpeTag:read","tag:read","Groupe_tag:read"})
     * @Assert\NotBlank(
     *      message="Le descriptif est obligatoire"
     * )
     * @Groups({"user:write"})
     */
    private $descriptif;

    /**
     * @ORM\ManyToMany(targetEntity=GroupeTag::class, mappedBy="tags")
     * @Groups({"tag:read", "user:write"})
     */
    private $groupeTags;

    /**
     * @ORM\ManyToMany(targetEntity=Briefs::class, mappedBy="tags")
     */
    private $briefs;

    public function __construct()
    {
        $this->groupeTags = new ArrayCollection();
        $this->briefs = new ArrayCollection();
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

    public function getDescriptif(): ?string
    {
        return $this->descriptif;
    }

    public function setDescriptif(string $descriptif): self
    {
        $this->descriptif = $descriptif;

        return $this;
    }

    /**
     * @return Collection|GroupeTag[]
     */
    public function getGroupeTags(): Collection
    {
        return $this->groupeTags;
    }

    public function addGroupeTag(GroupeTag $groupeTag): self
    {
        if (!$this->groupeTags->contains($groupeTag)) {
            $this->groupeTags[] = $groupeTag;
            $groupeTag->addTag($this);
        }

        return $this;
    }

    public function removeGroupeTag(GroupeTag $groupeTag): self
    {
        if ($this->groupeTags->contains($groupeTag)) {
            $this->groupeTags->removeElement($groupeTag);
            $groupeTag->removeTag($this);
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
            $brief->addTag($this);
        }

        return $this;
    }

    public function removeBrief(Briefs $brief): self
    {
        if ($this->briefs->contains($brief)) {
            $this->briefs->removeElement($brief);
            $brief->removeTag($this);
        }

        return $this;
    }
}
