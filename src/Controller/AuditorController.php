<?php

namespace App\Controller;

use App\Entity\Auditor;
use App\Repository\AuditorRepository;
use App\Repository\LocationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use OpenApi\Attributes as OA;

class AuditorController extends AbstractController
{
    private ValidatorInterface $validator;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    /**
     * Create a new auditor
     *
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @param LocationRepository $locationRepository
     * @return Response
     */
    #[Route('/api/new-auditor', name: 'create_auditor', methods: ['POST'])]
    #[OA\RequestBody(
        required: true,
        content: [
            new OA\MediaType(
                mediaType: 'application/x-www-form-urlencoded',
                schema: new OA\Schema(
                    required: ['name', 'location'],
                    properties: [
                        new OA\Property(
                            property: 'name',
                            type: 'string'
                        ),
                        new OA\Property(
                            property: 'location',
                            type: 'string'
                        )
                    ],
                    type: 'object'
                )
            )
        ]
    )]
    public function createAuditor(Request $request, EntityManagerInterface  $manager, LocationRepository $locationRepository): Response
    {
        $data = $request->request->all();

        $auditor = new Auditor();
        $auditor->setName($data['name']);

        $location = $locationRepository->findOneBy(['name' => $data['location']]);
        if (empty($location)) {
            return $this->json([
                'message' => 'Location not found!'
            ], 404);
        }
        $auditor->setLocation($location);

        $errors = $this->validator->validate($auditor);
        if (count($errors) > 0) {
            return $this->json(['errors' => $errors], 400);
        }

        try {
            $manager->persist($auditor);
            $manager->flush();

            return $this->json([
                'message' => 'Auditor created successfully!'
            ], status: 201);
        } catch (ORMException $exception) {
            return $this->json([
                'message' => 'Auditor could not be created: ' . $exception->getMessage(),
            ], status: 500);
        }
    }

    /**
     * Get all auditors
     *
     * @param AuditorRepository $auditorRepository
     * @return Response
     */
    #[Route('/api/auditors', name: 'all_auditors', methods: ['GET'])]
    public function getAllAuditors(AuditorRepository $auditorRepository): Response
    {
        $encoders = [new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers, $encoders);

        $allAuditors = $auditorRepository->findAll();
        if (empty($allAuditors)) {
            return $this->json([
                'message' => 'Auditors not found!'
            ], 404);
        }

        $data = $serializer->serialize($allAuditors, 'json');

        return $this->json($data, 200);
    }
}
