<?php

namespace App\Controller;

use App\Entity\AssignedJob;
use App\Entity\Job;
use App\Repository\AssignedJobRepository;
use App\Repository\AuditorRepository;
use App\Repository\JobRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use OpenApi\Attributes as OA;

class AssignedJobController extends AbstractController
{
    private ValidatorInterface $validator;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    /**
     * Assign job to auditor by job ID and auditor name with the date format {j.M.Y}, ie. 01.04.2024
     *
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @param JobRepository $jobRepository
     * @param AuditorRepository $auditorRepository
     * @return Response
     * @throws ORMException
     */
    #[Route('/api/assign-job', name: 'assign_job', methods: ['POST'])]
    #[OA\RequestBody(
        required: true,
        content: [
            new OA\MediaType(
                mediaType: 'application/x-www-form-urlencoded',
                schema: new OA\Schema(
                    required: ['auditor_name', 'job_id', 'date'],
                    properties: [
                        new OA\Property(
                            property: 'auditor_name',
                            type: 'string'
                        ),
                        new OA\Property(
                            property: 'job_id',
                            type: 'int'
                        ),
                        new OA\Property(
                            property: 'date',
                            description: 'Date should be in format ie. 01.04.2024',
                            type: 'string'
                        )
                    ],
                    type: 'object'
                )
            )
        ]
    )]
    public function assignJob(Request $request, EntityManagerInterface $manager, JobRepository $jobRepository, AuditorRepository $auditorRepository): Response
    {
        $data = $request->request->all();
        $auditor = $auditorRepository->findOneBy(['name' => $data['auditor_name']]);
        if (empty($auditor)) {
            return $this->json([
                'message' => 'Auditor not found!'
            ], 404);
        }

        $assignedJob = new AssignedJob();
        $assignedJob->setJob($manager->getReference(Job::class, $data['job_id']));
        $assignedJob->setAuditor($auditor);
        $assignedJob->setDate(\DateTime::createFromFormat('j.m.Y', $data['date']));

        $errors = $this->validator->validate($assignedJob);
        if (count($errors) > 0) {
            return $this->json(['errors' => $errors], 400);
        }

        try {
            $manager->persist($assignedJob);
            $manager->flush();

            return $this->json([
                'message' => 'Job assigned successfully!'
            ], status: 201);
        } catch (ORMException $exception) {
            return $this->json([
                'message' => 'Job could not be assigned: ' . $exception->getMessage(),
            ], status: 500);
        }
    }

    /**
     * Get jobs assigned to the {auditor} by name
     *
     * @param $auditor
     * @param AuditorRepository $auditorRepository
     * @param AssignedJobRepository $assignedJobRepository
     * @return Response
     */
    #[Route('/api/assigned-jobs/{auditor}', name: 'find_assigned_jobs_by_auditor', methods: ['GET'])]
    public function getAssignedJob($auditor, AuditorRepository $auditorRepository, AssignedJobRepository $assignedJobRepository): Response
    {
        $auditor = $auditorRepository->findOneBy(['name' => $auditor]);
        if (empty($auditor)) {
            return $this->json([
                'message' => 'Auditor not found!'
            ], 404);
        }

        $assignedJobs = $assignedJobRepository->findBy(['auditor' => $auditor]);
        if (empty($assignedJobs)) {
            return $this->json([
                'message' => 'No jobs were assigned to this auditor!'
            ], 404);
        }

        return $this->json($assignedJobs);
    }

    /**
     * Add job assessment to job found by job ID and auditor name
     *
     * @param Request $request
     * @param AssignedJobRepository $assignedJobRepository
     * @param AuditorRepository $auditorRepository
     * @param JobRepository $jobRepository
     * @param EntityManagerInterface $manager
     * @return Response
     */
    #[Route('/api/job-assessment', name: 'assess_assigned_job', methods: ['POST'])]
    #[OA\RequestBody(
        required: true,
        content: [
            new OA\MediaType(
                mediaType: 'application/x-www-form-urlencoded',
                schema: new OA\Schema(
                    required: ['auditor_name', 'job_id', 'assessment', 'completion_status'],
                    properties: [
                        new OA\Property(
                            property: 'auditor_name',
                            type: 'string'
                        ),
                        new OA\Property(
                            property: 'job_id',
                            type: 'int'
                        ),
                        new OA\Property(
                            property: 'assessment',
                            type: 'string'
                        ),
                        new OA\Property(
                            property: 'completion_status',
                            description: 'Value should be 0 or 1',
                            type: 'int',
                        )
                    ],
                    type: 'object'
                )
            )
        ]
    )]
    public function updateAssignedJob(Request $request, AssignedJobRepository $assignedJobRepository, AuditorRepository $auditorRepository, JobRepository $jobRepository, EntityManagerInterface $manager): Response
    {
        $data = $request->request->all();
        $auditor = $auditorRepository->findOneBy(['name' => $data['auditor_name']]);
        if (empty($auditor)) {
            return $this->json([
                'message' => 'Auditor not found!'
            ], 404);
        }

        $job = $jobRepository->findOneBy(['job_id' => $data['job_id']]);
        if (empty($job)) {
            return $this->json([
                'message' => 'Job not found!'
            ], 404);
        }


        $assignedJob = $assignedJobRepository->findOneBy(['job' => $job, 'auditor' => $auditor]);
        if (empty($assignedJob)) {
            return $this->json([
                'message' => 'Job was not assigned to this auditor!'
            ], 404);
        }

        $assignedJob->setAssessment($data['assessment']);
        $assignedJob->setCompletionStatus((int)$data['completion_status']);

        $errors = $this->validator->validate($assignedJob);
        if (count($errors) > 0) {
            return $this->json(['errors' => $errors], 400);
        }

        try {
            $manager->persist($assignedJob);
            $manager->flush();

            return $this->json([
                'message' => 'Job assessment updated successfully!'
            ]);
        } catch (ORMException $exception) {
            return $this->json([
                'message' => 'Job assessment could not be completed: ' . $exception->getMessage(),
            ], status: 500);
        }
    }
}
