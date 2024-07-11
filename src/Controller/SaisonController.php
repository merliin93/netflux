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
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;

class SaisonController extends AbstractController
{
    #[Route('/api/saison', name: 'saison', methods: ['GET'])]
    public function getSaisonList(SaisonRepository $saisonRepository, SerializerInterface $serializer): JsonResponse
    {
        $saisonList = $saisonRepository->findAll();

        // Normaliser les saisons pour éviter les références circulaires
        $data = $serializer->normalize($saisonList, null, [
            AbstractObjectNormalizer::ATTRIBUTES => [
                'id',
                'numeroSaison',
                'content_id_id' => [
                    'id',
                    'titre'
                ],
            ],
        ]);

        return new JsonResponse($data, Response::HTTP_OK);
    }

    #[Route('/api/saison/{id}', name: 'detailSaison', methods: ['GET'])]
    public function getDetailSaison(int $id, SerializerInterface $serializer, SaisonRepository $saisonRepository): JsonResponse
    {
        $saisonList = $saisonRepository->find($id);

        // Normaliser les saisons pour éviter les références circulaires
        $data = $serializer->normalize($saisonList, null, [
            AbstractObjectNormalizer::ATTRIBUTES => [
                'id',
                'numero_saison',
                'content_id' => [
                    'id',
                    'titre'
                ],
                'episode_id' => [
                    'id',
                    'numero_episode',
                    'titre_episode',
                    'duree'
                ]
            ],
        ]);

        return new JsonResponse($data, Response::HTTP_OK);
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
