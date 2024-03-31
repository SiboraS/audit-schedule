<?php

namespace App\Controller;

use App\Entity\Job;
use App\Repository\JobRepository;
use Doctrine\ORM\Exception\ORMException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use OpenApi\Attributes as OA;


class JobController extends AbstractController
{
    private ValidatorInterface $validator;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    /**
     * Create a new job
     *
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    #[Route('/api/new-job', name: 'create_job', methods: ['POST'])]
    #[OA\RequestBody(
        required: true,
        content: [
            new OA\MediaType(
                mediaType: 'application/x-www-form-urlencoded',
                schema: new OA\Schema(
                    required: ['description'],
                    properties: [
                        new OA\Property(
                            property: 'description',
                            type: 'string'
                        )
                    ],
                    type: 'object'
                )
            )
        ]
    )]
    public function createJob(Request $request, EntityManagerInterface  $manager): Response
    {
        $data = $request->request->all();

        $job = new Job();
        $job->setDescription($data['description']);

        $errors = $this->validator->validate($job);
        if (count($errors) > 0) {
            return $this->json(['errors' => $errors], 400);
        }

        try {
            $manager->persist($job);
            $manager->flush();

            return $this->json([
                'message' => 'Job created successfully!'
            ], status: 201);
        } catch (ORMException $exception) {
            return $this->json([
                'message' => 'Job could not be created: ' . $exception->getMessage(),
            ], status: 500);
        }
    }

    /**
     * Get all jobs
     *
     * @param JobRepository $jobRepository
     * @return Response
     */
    #[Route('/api/jobs', name: 'all_jobs', methods: ['GET'])]
    public function getAllJobs(JobRepository $jobRepository): Response
    {
        $encoders = [new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers, $encoders);

        $allJobs = $jobRepository->findAll();
        if (empty($allJobs)) {
            return $this->json([
                'message' => 'Jobs not found!'
            ], 404);
        }

        $data = $serializer->serialize($allJobs, 'json');

        return $this->json($data, 200);
    }

    /**
     * Get available jobs
     *
     * @param JobRepository $jobRepository
     * @return Response
     */
    #[Route('/api/available-jobs', name: 'available_jobs', methods: ['GET'])]
    public function getAvailableJobs(JobRepository $jobRepository): Response
    {
        $encoders = [new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers, $encoders);

        $allJobs = $jobRepository->getUnassignedJobs();
        if (empty($allJobs)) {
            return $this->json([
                'message' => 'Available jobs not found!'
            ], 404);
        }

        $data = $serializer->serialize($allJobs, 'json');

        return $this->json($data);
    }
}
