<?php

namespace App\Controller;

use App\Entity\Tag;
use App\Repository\TagRepository;
use App\Repository\GroupeTagRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TagController extends AbstractController
{

    private $manager,
            $serializer,
            $validator;
    public function __construct(ValidatorInterface $validator,EntityManagerInterface $manager,SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
        $this->manager = $manager;
        $this->validator = $validator;
    }
    /**
     * @Route(
     *     path="/api/admin/tags",
     *     methods={"POST"},
     *     name="addTag"
     * )
     */
    public function addTag(Request $request, TagRepository $tagRepo, GroupeTagRepository $groupeTagRepo)
    {
        $tag = new Tag();
        if(!($this->isGranted("EDIT",$tag)))
            return $this->json(["message" => "Vous n'avez pas access à cette Ressource"],Response::HTTP_FORBIDDEN);
        $tagJson = $request->getContent();
        $tagTab = $this->serializer->decode($tagJson,"json");
        $groupeTags = isset($tagTab['groupeTags']) ? $tagTab['groupeTags'] : [];
        $tagTab['groupeTags']=[];
        $tagObj = $this->serializer->denormalize($tagTab,"App\Entity\Tag");
        if(count($groupeTags)){
            foreach ($groupeTags as $groupeTag) {
                $groupeTagId = isset($groupeTag["id"]) ? $groupeTag["id"] : null;
                $grpTagFind = $groupeTagRepo->findOneBy(["id"=>$groupeTagId]);
                if (!$grpTagFind) {
                    return $this->json(["message" => "Ce groupe de Tag n'existe pas."],Response::HTTP_NOT_FOUND);
                }
                $tagObj->addGroupeTag($grpTagFind);
            }
        }else {
            return $this->json(["message" => "Le groupe de tag est obligatoire."],Response::HTTP_NOT_FOUND);
        }
        $this->manager->persist($tagObj);
        $this->manager->flush();
        return $this->json($tagObj,Response::HTTP_CREATED);
    }

    /**
    * @Route(
    *     path="/api/admin/tag/{id}",
    *     methods={"PUT"},
    *     name="editTag"
    * )
    */
    
    public function editTag($id,Request $request, TagRepository $tagRepo, GroupeTagRepository $groupeTagRepo)
    {
        $tag = $tagRepo->find($id);
        if (!$tag) {
            return new JsonResponse("Ce tag n'existe pas.", Response::HTTP_BAD_REQUEST, [], true);
        }
        $data = $request->getContent();
        $tagTab = $this->serializer->decode($data,"json");
        if (empty($tagTab['libelle'])) {
            return new JsonResponse('Le libelle est obligatoire.', Response::HTTP_BAD_REQUEST, [], true);
        }
        if (empty($tagTab['descriptif'])) {
            return new JsonResponse('Le libelle est obligatoire.', Response::HTTP_BAD_REQUEST, [], true);
        }
        $groupeTags = isset($tagTab['groupeTags']) ? $tagTab['groupeTags'] : [];
        $tagTab['groupeTags']=[];
        $tagObj = $this->serializer->denormalize($tagTab,"App\Entity\Tag");
        $tag->setLibelle($tagTab['libelle']);
        $tag->setDescriptif($tagTab['descriptif']);
        foreach ($tag->getGroupeTags() as $value) {
            $tag->removeGroupeTag($value);
        }
        if(count($groupeTags)){
            foreach ($groupeTags as $groupeTag) {
                $groupeTagId = isset($groupeTag["id"]) ? $groupeTag["id"] : null;
                $grpTagFind = $groupeTagRepo->findOneBy(["id"=>$groupeTagId]);
                if (!$grpTagFind) {
                    return $this->json(["message" => "Ce groupe de Tag n'existe pas."],Response::HTTP_NOT_FOUND);
                }
                $tag->addGroupeTag($grpTagFind);
            }
        }else {
            return $this->json(["message" => "Le groupe de tag est obligatoire."],Response::HTTP_NOT_FOUND);
        }
        $this->manager->persist($tag);
        $this->manager->flush();
        return $this->json("succes",Response::HTTP_CREATED);

    }

}
