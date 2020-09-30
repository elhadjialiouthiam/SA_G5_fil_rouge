<?php

namespace App\DataPersister;

use App\Entity\CM;
use App\Entity\User;
use App\Entity\Apprenant;
use App\Service\ArchiveService;
use App\Repository\UserRepository;
use App\Repository\ProfilRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

final class UserPersister implements ContextAwareDataPersisterInterface
{
    private $passwordEncoder;
    private $manager;
    private $profilRepo;
    private $archiveService;
    private $userRepo;
    public function __construct(UserPasswordEncoderInterface $passwordEncoder, EntityManagerInterface $manager, ProfilRepository $profilRepo, ArchiveService $archiveService, UserRepository $userRepo){
        $this->manager = $manager;
        $this->passwordEncoder = $passwordEncoder;
        $this->profilRepo = $profilRepo;
        $this->archiveService = $archiveService;
        $this->userRepo = $userRepo;
    }


    public function supports($data, array $context = []): bool
    {
        return $data instanceof User;
    }

    public function persist($data, array $context = [])
    {
            $this->manager->persist($data);
            $this->manager->flush();
        return new JsonResponse("Opération réussie",Response::HTTP_CREATED,[],true);
    }

    public function remove($data, array $context = [])
    {
        return $this->archiveService->archive($data);
    }
}