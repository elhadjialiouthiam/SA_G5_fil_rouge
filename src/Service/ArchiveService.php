<?php
namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

class ArchiveService
{
    private $manager;
    private $serializer;
    public function __construct(EntityManagerInterface $manager, SerializerInterface $serializer){
        $this->manager = $manager;
        $this->serializer = $serializer;
    }
    
    public function archive($object){
        $object->setEtat('archive');
        $this->manager->flush();
        return new Response("Ressource archivée avec succes");
    }

    //function permettant de liste que les contenus non archivés
    public function show($repo){
        $elementNoArchives=$repo->findBy([
            "etat" => null
        ]);
        
        $elementNoArchives = $this->serializer->serialize($elementNoArchives,'json',[AbstractNormalizer::IGNORED_ATTRIBUTES => ['avatar']]);
        // dd($elementNoArchives);
        return new JsonResponse($elementNoArchives,Response::HTTP_OK,[],true);
    }
}