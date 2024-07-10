<?php

namespace App\Controller;

use App\Entity\Saison;
use App\Repository\SaisonRepository;
use App\Repository\ContentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SaisonController extends AbstractController
{
    #[Route('/api/saison', name: 'saison', methods: ['GET'])]
    public function getSaisonList(SaisonRepository $saisonRepository, SerializerInterface $serializer): JsonResponse
    {
        $saisonList = $saisonRepository->findAll();
        $jsonSaisonList = $serializer->serialize($saisonList, 'json');
        return new JsonResponse($jsonSaisonList, Response::HTTP_OK, [], true);
    }

    #[Route('/api/saison/{id}', name: 'detailSaison', methods: ['GET'])]
    public function getDetailSaison(int $id, SerializerInterface $serializer, SaisonRepository $saisonRepository): JsonResponse
    {
        $saison = $saisonRepository->find($id);
        if ($saison) {
            $jsonSaison = $serializer->serialize($saison, 'json');
            return new JsonResponse($jsonSaison, Response::HTTP_OK, [], true);
        }
        return new JsonResponse(null, Response::HTTP_NOT_FOUND);
    }

    #[Route('/api/createSaison', name:"createSaison", methods: ['POST'])]
    public function createSaison(Request $request, ContentRepository $contentRepository ,SerializerInterface $serializer, EntityManagerInterface $em, UrlGeneratorInterface $urlGenerator): JsonResponse 
    {
        $array_request = json_decode($request->getContent(), true);
        $content = $contentRepository->findOneById($array_request['content_id_id']);
    
        $saison = new Saison();
        $saison->setContentId($content);
        $saison->setNumeroSaison($array_request['numero_saison']);
    
        $em->persist($saison);
        $em->flush();
    
        $jsonSaison = $serializer->serialize($saison, 'json', ['groups' => 'getContent']); 
        return new JsonResponse($jsonSaison, Response::HTTP_CREATED, [], true);
    }
}
