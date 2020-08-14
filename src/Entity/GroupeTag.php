<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\GroupeTagRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=GroupeTagRepository::class)
* @ApiResource(
 *        attributes={
 *      "input_formats"={"json"={"application/ld+json", "application/json"}},
 *      "output_formats"={"json"={"application/ld+json", "application/json"}}
 *     },
 *       collectionOperations={
 *          "GET"={
 *              "method"="GET",
 *              "path"="/admin/groupeTags",
 *              "security"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR'))",
 *              "security_message"="Vous n'avez pas access à cette Ressource",
 *              "normalization_context"={"groups"={"Groupe_tag:read"}}
 *          },
 *          "addGroupeTag"={
 *              "method"="POST",
 *              "path"="/admin/groupeTags",
 *              "security"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR'))",
 *              "security_message"="Vous n'avez pas access à cette Ressource",
 *          }
 *     },
 *      itemOperations={
 *          "get"={
 *              "method"="GET",
 *              "path"="admin/groupeTags/{id}",
 *              "defaults"={"id"=null},
 *              "requirements"={"id"="\d+"},
 *              "security"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR'))",
 *              "security_message"="Vous n'avez pas access à cette Ressource",
 *              "normalization_context"={"groups"={"Groupe_tag:read"}}
 *          },
 *          "editTag"={
 *              "method"="PUT",
 *              "path"="/admin/groupeTags/{id}",
 *              "defaults"={"id"=null},
 *              "requirements"={"id"="\d+"},
 *              "security"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR'))",
 *              "security_message"="Vous n'avez pas access à cette Ressource",
 *          },
 *          "get_tags_id_groupeTag"={
 *              "method"="GET",
 *              "path"="/admin/groupeTags/{id}/tags",
 *              "requirements"={"id"="\d+"},
 *              "route_name"="show_tags_id_groupeTag",
 *              "access_control"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR'))",
 *          },
 *          "add_tag_in_groupetag"={
 *              "method"="PUT",
 *              "path"="/admin/groupeTag/{id}",
 *              "requirements"={"id"="\d+"},
 *              "route_name"="add_tag_in_groupetags",
 *              "access_control"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR'))",
 *          },
 *          "delete_tag_in_groupetag"={
 *              "method"="DELETE",
 *              "path"="/admin/groupeTags/{id}/tags/{id_tag}",
 *              "requirements"={"id"="\d+"},
 *              "route_name"="delete_tag_in_groupetag",
 *              "access_control"="(is_granted('ROLE_ADMIN'))",
 *          },
 *      }
 * 
 * )  
 */
class GroupeTag
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"tag:read","Groupe_tag:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"tag:read","Groupe_tag:read"})
     */
    private $libelle;

    /**
     * @ORM\ManyToMany(targetEntity=Tag::class, inversedBy="groupeTags")
     * @Groups({"Groupe_tag:read", "tagsInGrpeTag:read"})
     */
    private $tags;

    public function __construct()
    {
        $this->tags = new ArrayCollection();
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

    /**
     * @return Collection|Tag[]
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Tag $tag): self
    {
        if (!$this->tags->contains($tag)) {
            $this->tags[] = $tag;
        }

        return $this;
    }

    public function removeTag(Tag $tag): self
    {
        if ($this->tags->contains($tag)) {
            $this->tags->removeElement($tag);
        }

        return $this;
    }
}
