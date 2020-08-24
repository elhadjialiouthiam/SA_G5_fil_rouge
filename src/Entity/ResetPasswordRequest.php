<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ResetPasswordRequestRepository;
use Symfony\Component\Serializer\Annotation\Groups;
use SymfonyCasts\Bundle\ResetPassword\Model\ResetPasswordRequestTrait;
use SymfonyCasts\Bundle\ResetPassword\Model\ResetPasswordRequestInterface;

/**
 * @ApiResource(
 *      collectionOperations={
 *      "notJoin"={
 *          "path"="/reset_password_requests",
 *          "method"="get",
 *          "normalization_context"={"groups"="reset:read"}
 * },
 *          "relanceTout"={
 *              "method"="get",
 *              "route_name"="relanceTout"
 * },
 * },
 *      itemOperations={
 *          "relanceUneInvitation"={
 *              "method"="get",
 *              "route_name"="relanceUneInvitation"
 * }
 * }
 * )
 * @ORM\Entity(repositoryClass=ResetPasswordRequestRepository::class)
 */
class ResetPasswordRequest implements ResetPasswordRequestInterface
{
    use ResetPasswordRequestTrait;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"reset:read"})
     */
    private $user;

    public function __construct(object $user, \DateTimeInterface $expiresAt, string $selector, string $hashedToken)
    {
        $this->user = $user;
        $this->initialize($expiresAt, $selector, $hashedToken);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): object
    {
        return $this->user;
    }
}
