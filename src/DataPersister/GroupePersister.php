<?php
namespace App\DataPersister;

use App\Entity\Groupe;
use App\Repository\GroupeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints\DateTime;
use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;

final class GroupePersister implements ContextAwareDataPersisterInterface
{
    private $manager;
    private $repository;
    
    public function __construct(EntityManagerInterface $manager, GroupeRepository $repository){
        $this->manager = $manager;
        $this->repository = $repository;
    }

    public function supports($data, array $context = []): bool
    {
        return $data instanceof Groupe;
    }

    public function persist($data, array $context = [])
    {
        $groupWithSameName = $this->repository->findBy([
            "nom" => $data->getNom()
        ]);
        if(count($groupWithSameName)){
            return new Response("Un groupe avec le même nom existe déjà");
        }
        $data->setDatecreation(new \DateTime());

        $this->manager->persist($data);
        $this->manager->flush();
    }

    public function remove($data, array $context = [])
    {
       
    }
}