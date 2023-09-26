<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\CSVImportManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


#[Route('/employee', name: 'employee_')]
class EmployeeController extends AbstractController
{
    #[Route('', name: 'app_employee_index', methods: ['GET'])]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Get filters from the request
        $filters = $this->getFiltersFromRequest($request);

        // Fetch paginated employee data from the repository
        $employees = $entityManager->getRepository(User::class)->getPaginatedData($filters);

        // Count all employees (for pagination)
        $totalEmployees = $entityManager->getRepository(User::class)->count([]);

        // Prepare response data
        $responseData = [
            'employees' => $employees,
            'totalItems' => $totalEmployees,
            'pageSize' => $filters['pageSize'],
            'pageIndex' => $filters['pageIndex'],
        ];

        return $this->json($responseData, Response::HTTP_OK, [], ['groups' => 'employee']);
    }

    private function getFiltersFromRequest(Request $request): array
    {
        // Parse and sanitize filter parameters from the request
        $pageSize = (int)$request->query->get('pageSize', 10);
        $pageIndex = (int)$request->query->get('pageIndex', 1);

        // Ensure positive values for pageSize and pageIndex
        $pageSize = max(1, $pageSize);
        $pageIndex = max(1, $pageIndex);

        return [
            'pageSize' => $pageSize,
            'pageIndex' => $pageIndex,
        ];
    }


    #[Route('', name: 'app_employee_import', methods: ['POST'])]
    public function import(CSVImportManager $CSVImportManager, Request $request): Response
    {
        ini_set('memory_limit', '2048M');
        $uploadedFiles = $request->files->all();
        foreach ($uploadedFiles as $uploadedFile) {
            // Check if $uploadedFile is indeed an uploaded file
            if ($uploadedFile instanceof UploadedFile) {

                // Check if the uploaded file is a CSV
                if ($uploadedFile->getClientOriginalExtension() !== 'csv') {
                    return $this->json(['message' => 'Invalid file format. Only CSV files are allowed.'], Response::HTTP_BAD_REQUEST);
                }

                $filePath = $uploadedFile->getPathname();
                $chunkSize = 1024 * 1024; // 1MB chunk size
                $batchSize = 50; // Adjust the batch size as needed


                // Call the CSV import manager to handle the import
                $importResult = $CSVImportManager->importCSV($filePath, $chunkSize, $batchSize);

                if ($importResult === false) {
                    return $this->json(['message' => 'Failed to import CSV.'], Response::HTTP_INTERNAL_SERVER_ERROR);
                }
            }
        }
        // Return a response indicating success
        return $this->json(['message' => 'CSV file uploaded and processed successfully.']);
    }

    #[Route('/{id}', name: 'app_employee_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->json($user, 200, [], ['groups' => 'employee']);
    }

    #[Route('/{id}', name: 'app_employee_delete', methods: ['DELETE'])]
    public function delete(EntityManagerInterface $entityManager, User $user): Response
    {
        $entityManager->remove($user);
        $entityManager->flush();

        return $this->json(['message' => 'Employee deleted successfully'], 204);
    }
}