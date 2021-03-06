<?php

namespace App\Entity;

use App\Entity\User;
use App\Entity\Promos;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\AdminRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass=AdminRepository::class)
 * @ApiResource(
 *  collectionOperations={
 *      "add_cm"={
 *          "method"="POST",
 *          "route_name"="add_admin"
 * }
 * }
 * )
 */
class Admin extends User
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    protected $id;

    public function getId(): ?int
    {
        return $this->id;
    }

 
     /**
     * @ORM\OneToMany(targetEntity=Promos::class, mappedBy="admin")
     */
    private $promos;

    public function __construct()
    {
        $this->promos = new ArrayCollection();
    }


    /**
     * @return Collection|Promos[]
     */
    public function getPromos(): Promos
    {
        return $this->promos;
    }


    public function addPromo(Promos $promo): self
    {
        if (!$this->promos->contains($promo)) {
            $this->promos[] = $promo;
            $promo->setAdmin($this);
        }

        return $this;
    }

    public function removePromo(Promos $promo): self
    {
        if ($this->promos->contains($promo)) {
            $this->promos->removeElement($promo);
            // set the owning side to null (unless already changed)
            if ($promo->getAdmin() === $this) {
                $promo->setAdmin(null);
            }
        }

        return $this;
    }
}
