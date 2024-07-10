<?php

namespace App\Controller;

use App\Entity\Episode;
use App\Repository\SaisonRepository;
use App\Repository\ContentRepository;
use App\Repository\EpisodeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;

class EpisodeController extends AbstractController
{
    #[Route('/api/episode', name: 'episode', methods: ['GET'])]
    public function getEpisodeList(EpisodeRepository $episodeRepository, SerializerInterface $serializer): JsonResponse
    {
        $episodeList = $episodeRepository->findAll();

        // Serializer with specified attributes to avoid circular reference
        $data = $serializer->normalize($episodeList, null, [
            AbstractObjectNormalizer::ATTRIBUTES => [
                'id',
                'numero_episode',
                'titre_episode',
                'duree',
                'content_id' => [
                    'id',
                    'titre'
                ],
                'saison' => [
                    'id',
                    'numero_saison'
                ],
            ],
        ]);

        return new JsonResponse($data, Response::HTTP_OK);
    }

    #[Route('/api/episode/{id}', name: 'detailEpisode', methods: ['GET'])]
    public function getDetailEpisode(int $id, SerializerInterface $serializer, EpisodeRepository $episodeRepository): JsonResponse
    {
        $episode = $episodeRepository->find($id);
        if ($episode) {
            $jsonEpisode = $serializer->serialize($episode, 'json');
            return new JsonResponse($jsonEpisode, Response::HTTP_OK, [], true);
        }
        return new JsonResponse(null, Response::HTTP_NOT_FOUND);
    }

    #[Route('/api/createEpisode', name: "createEpisode", methods: ['POST'])]
    public function createEpisode(Request $request, SerializerInterface $serializer, ContentRepository $contentRepository, SaisonRepository $saisonRepository ,EntityManagerInterface $em, UrlGeneratorInterface $urlGenerator): JsonResponse
    {
        $array_request = json_decode($request->getContent(), true);
        $content = $contentRepository->findOneById($array_request['content_id_id']);
        $saison = $saisonRepository->findOneById($array_request['saison_id']);
    
        $episode = new Episode();
        $episode->setContentId($content);
        $episode->setNumeroEpisode($array_request['numero_episode']);
        $episode->setTitreEpisode($array_request['titre_episode']);
        $episode->setDuree($array_request['duree']);
        $episode->setSaison($saison);   
    
        $em->persist($episode);
        $em->flush();
    
        $jsonEpisode = $serializer->serialize($episode, 'json', ['groups' => 'getContent']); 
        return new JsonResponse($jsonEpisode, Response::HTTP_CREATED, [], true);
    }
}
