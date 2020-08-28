<?php

namespace App\Entity;


use App\Entity\User;
use App\Repository\CMRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;

/**
 * @ORM\Entity(repositoryClass=CMRepository::class)
 * @ApiResource(
 *      collectionOperations={
 *          "add_cm"={
 *              "method"="POST",
 *              "route_name"="add_cm"
 * }
 * }
 * )
 */
class CM extends User
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
}
