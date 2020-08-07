<?php
namespace App\DataPersister;

use App\Entity\User;
use App\Service\ArchiveService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;



final class UserPersister implements ContextAwareDataPersisterInterface
{
    private $encoder;
    private $archiveService;
    private $serializer;
    private $manager;
    private $validator;
    // private $request;
    public function __construct(UserPasswordEncoderInterface $encoder, ArchiveService $archiveService, SerializerInterface $serializer, EntityManagerInterface $manager, ValidatorInterface $validator){
        $this->encoder = $encoder;
        $this->archiveService = $archiveService;
        $this->serializer = $serializer;
        $this->manager = $manager;
        $this->validator = $validator;
        // $this->request = $request;
    }


    public function supports($data, array $context = []): bool
    {
        return $data instanceof User;
    }

    public function persist($data, array $context = [])
    {
        // $request = new Request();
        // dd($request->request);
        // $user = $this->serializer->deserialize($request->getContent(),User::class,'json');
        // $errors = $this->validator->validate($user);
        // if(count($errors) > 0){
        //     $errors = $thsi->serializer->serialize($errors,'json');
        //     return new JsonResponse($errors,Response::HTTP_BAD_REQUEST,[],true);
        // }
        // $user->setRoles($user->getRoles());
        // $user->setPassword($this->encoder->encodePassword($user,$user->getPassword()));
        // $this->manager->persist($user);
        // $this->manager->flush();
        // return new JsonResponse("Créé avec success",Response::HTTP_CREATED,[],true);
    }

    public function remove($data, array $context = [])
    {
        return $this->archiveService->archive($data);
      // call your persistence layer to delete $data
    }
}