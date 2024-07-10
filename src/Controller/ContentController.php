<?php

namespace App\Controller;

use App\Entity\Content;
use App\Repository\ContentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
Use Symfony\Component\Routing\Annotation\Route;

class ContentController extends AbstractController
{
    #[Route('/api/content', name: 'content', methods: ['GET'])]
    public function getContentList(ContentRepository $contentRepository, SerializerInterface $serializer): JsonResponse
    {
        $contentList = $contentRepository->findAll();
        $jsonContentList = $serializer->serialize($contentList, 'json');
        return new JsonResponse($jsonContentList, Response::HTTP_OK, [], true);
    }

    #[Route('/api/content/{id}', name: 'detailContent', methods: ['GET'])]
    public function getDetailContent(int $id, SerializerInterface $serializer, ContentRepository $contentRepository): JsonResponse
    {
        $content = $contentRepository->find($id);
        if ($content) {
            // Normaliser le contenu pour éviter les références circulaires
            $data = $serializer->normalize($content, null, [
                AbstractObjectNormalizer::ATTRIBUTES => [
                    'id',
                    'titre',
                    'categorie',
                    'video',
                    'Langue',
                    'episodes' => [
                        'id',
                        'numero_episode',
                        'titre_episode',
                        'duree',
                        'saison' => [
                            'id',
                            'numero_saison'
                        ]
                    ]
                ],
            ]);

            return new JsonResponse($data, Response::HTTP_OK);
        }
        return new JsonResponse(null, Response::HTTP_NOT_FOUND);
    }

    #[Route('/api/createContent', name: "createContent", methods: ['POST'])]
    public function createContent(Request $request, SerializerInterface $serializer, EntityManagerInterface $em, UrlGeneratorInterface $urlGenerator): JsonResponse
    {
        $content = $serializer->deserialize($request->getContent(), Content::class, 'json');
        $em->persist($content);
        $em->flush();

        $jsonContent = $serializer->serialize($content, 'json', ['groups' => 'getContent']);
        
        $location = $urlGenerator->generate('detailContent', ['id' => $content->getId()], UrlGeneratorInterface::ABSOLUTE_URL);

        return new JsonResponse($jsonContent, Response::HTTP_CREATED, ["Location" => $location], true);
    }
}
