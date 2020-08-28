<?php

namespace App\Controller;

use App\Entity\GroupeTag;
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

class GroupeTagController extends AbstractController
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
     *     path="/api/admin/groupeTags",
     *     methods={"POST"},
     *     name="addGroupeTag"
     * )
     */
    public function addGroupeTag(Request $request, TagRepository $tagRepo, GroupeTagRepository $groupeTagRepo)
    {
        $groupeTag = new GroupeTag();
        if(!($this->isGranted("EDIT",$groupeTag)))
            return $this->json(["message" => "Vous n'avez pas access à cette Ressource"],Response::HTTP_FORBIDDEN);
        $groupeTagJson = $request->getContent();
        $groupeTagTab = $this->serializer->decode($groupeTagJson,"json");
        $Tags = isset($groupeTagTab['tags']) ? $groupeTagTab['tags'] : [];
        // dd($Tags);
        $groupeTagTab['tags']=[];
        $groupeTagObj = $this->serializer->denormalize($groupeTagTab,"App\Entity\GroupeTag");
        if(count($Tags)){
            foreach ($Tags as $tag) {
                if (isset($tag["id"])) {
                    $tagId = $tag["id"];
                    $TagFind = $tagRepo->findOneBy(["id"=>$tagId]);
                    if (!$TagFind) {
                        return $this->json(["message" => "Ce Tag n'existe pas."],Response::HTTP_NOT_FOUND);
                    }
                    $groupeTagObj->addTag($TagFind);
                }
                else {
                    $newTag = $this->serializer->denormalize($tag,"App\Entity\Tag");
                    $error = $this->validator->validate($newTag);
                    if(count($error))
                    {
                        return $this->json($error,Response::HTTP_BAD_REQUEST);
                    }
                    $this->manager->persist($newTag);
                    $groupeTagObj->addTag($newTag);
                }
                
            }
        }else {
            return $this->json(["message" => "Le tag est obligatoire."],Response::HTTP_NOT_FOUND);
        }
        $this->manager->persist($groupeTagObj);
        $this->manager->flush();
        return $this->json($groupeTagObj,Response::HTTP_CREATED);
    }
    /**
     * @Route(
     *     name="show_tags_id_groupeTag",
     *     path="/api/admin/groupeTags/{id}/tags",
     *     methods={"GET"},
     *     defaults={
     *         "_api_resource_class"=GroupeTag::class,
     *         "_api_collection_operation_name"="get_tags_id_groupeTag"
     *     }
     * )
     */

    public function getTagsInGroupesTags(int $id, GroupeTagRepository $groupeTagRepo)
    {
        $groupeTag = $groupeTagRepo->find($id);

        $groupeTagJson = $this->serializer->serialize($groupeTag, 'json',["groups"=>["tagsInGrpeTag:read"]]);
        return new JsonResponse($groupeTagJson, Response::HTTP_OK, [], true);
    }

    /**
     * @Route(
     *     name="add_tag_in_groupetags",
     *     path="/api/admin/groupeTag/{id}",
     *     methods={"PUT"},
     *     defaults={
     *         "_api_resource_class"=GroupeTag::class,
     *         "_api_collection_operation_name"="add_tag_in_groupetag"
     *     }
     * )
     */
    
    public function AddTagInGroupe_tag($id,Request $request,TagRepository $tagRepo, GroupeTagRepository $groupeTagRepo)
    {
        // dd("ghs");
        // $promo = new Promos();
        $GroupeTag = $groupeTagRepo->findOneBy([
            "id"=>$id
        ]);
        // dd($promo);
        if(!$GroupeTag)
            return $this->json(["message" => "Ce groupe de tag n'existe pas."],Response::HTTP_NOT_FOUND);
        $TagJson = $request->getContent();
        $TagTab = $this->serializer->decode($TagJson,"json");
        $Tags = isset($TagTab["id"]) ? $TagTab["id"] : [];
        $TagObj = $this->serializer->denormalize($TagTab,"App\Entity\GroupeTag");
        $Tag = $tagRepo->findOneBy([
            "id"=> $Tags
        ]);
        $IsIn = false;
        if ($Tag) {
            $GroupeTag->addTag($Tag);
            $this->manager->flush();
            return $this->json("ajouter");
        }
        return $this->json(["message" => "Ce formateur n'existe pas."],Response::HTTP_NOT_FOUND);
        
    }
    /**
     * @Route(
     *     name="delete_tag_in_groupetag",
     *     path="/api/admin/groupeTags/{id}/tags/{id_tag}",
     *     methods={"DELETE"},
     *     defaults={
     *         "_api_resource_class"=GroupeTag::class,
     *         "_api_collection_operation_name"="delete_tag_in_groupetag"
     *     }
     * )
     */
    public function DeletetagInGroupeTAg($id,$id_tag,Request $request, TagRepository $tagRepo, GroupeTagRepository $groupeTagRepo){
        $groupeTag = $groupeTagRepo->findOneBy([
            "id"=>$id
        ]);
        if(!$groupeTag)
            return $this->json(["message" => "Ce groupe de tag n'existe pas."],Response::HTTP_NOT_FOUND);
        $tag = $tagRepo->findOneBy([
            "id"=> $id_tag
        ]);
        if(!$tag)
            return $this->json(["message" => "Ce tag n'existe pas."],Response::HTTP_NOT_FOUND);

        $data = $groupeTag->removeTag($tag);
        $this->manager->flush();
        return $this->json(["message" => "Suppression avec succes."],Response::HTTP_OK);
    }

}