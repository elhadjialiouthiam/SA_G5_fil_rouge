<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\CommentairesGeneraleRepository;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *  collectionOperations={
 *      "getCommentaires"={
 *          "method"="GET",
 *          "route_name"="getCommentaires"
 * },
 *      "getCommentsOfAStudent"={
 *          "method"="GET",
 *          "route_name"="getCommentsOfAStudent"
 * },
 *      "studentSendComment"={
 *          "method"="POST",
 *          "route_name"="studentSendComment"
 * },
 *      "userSendComment"={
 *          "method"="POST",
 *          "route_name"="userSendComment"
 * }
 * },
 * itemOperations={
 *  "get"
 * }
 * )
 * @ORM\Entity(repositoryClass=CommentairesGeneraleRepository::class)
 */
class CommentairesGenerale
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
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\Column(type="blob", nullable=true)
     */
    private $pj;

    /**
     * @ORM\ManyToOne(targetEntity=ChatGeneral::class, inversedBy="commentairesGenerales")
     * @ORM\JoinColumn(nullable=false)
     */
    private $chatgeneral;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="commentairesGenerales")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

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

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getPj()
    {
        return $this->pj;
    }

    public function setPj($pj): self
    {
        $this->pj = $pj;

        return $this;
    }

    public function getChatgeneral(): ?ChatGeneral
    {
        return $this->chatgeneral;
    }

    public function setChatgeneral(?ChatGeneral $chatgeneral): self
    {
        $this->chatgeneral = $chatgeneral;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
