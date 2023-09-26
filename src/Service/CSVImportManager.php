<?php

namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Constraints as Assert;
use App\Validator\Constraints as CustomAssert;

class CSVImportManager
{
    private $entityManager;
    private $validator;

    public function __construct(EntityManagerInterface $entityManager, ValidatorInterface $validator)
    {
        $this->entityManager = $entityManager;
        $this->validator = $validator;
    }

    public function importCSV($csvFilePath, $chunkSize, $batchSize)
    {

        $this->entityManager->getConnection()->getConfiguration()->setSQLLogger(null);

        // Open the CSV file for reading
        $csvFile = fopen($csvFilePath, 'rb');

        if ($csvFile === false) {
            return false; // Failed to open the file
        }

        try {

            // Skip the first line (header)
            fgetcsv($csvFile);
            $employees = [];
            $counter = 0;

            while (!feof($csvFile)) {
                // Read a chunk of data
                // To avoid memory exhaustion
                $chunk = fread($csvFile, $chunkSize);
                $lines = explode("\n", $chunk);

                // Process each line of CSV data
                foreach ($lines as $line) {
                    // Parse CSV line into an array of values
                    $rowData = str_getcsv($line);

                    // Check if the CSV row has the expected number of columns
                    if (count($rowData) === 19) {

                        $data = $this->prepareData($rowData);
                        $errors = $this->validateEmployee($data);
                        if (!$errors) {
                            $employee = $this->createEmployee($data);
                            $employees[] = $employee;
                            $counter++;

                            if ($counter >= $batchSize) {
                                // Persist the batch of employees and reset the counter
                                $this->batchPersist($employees);
                                $counter = 0;
                                unset($employees);
                            }

                        }
                        unset($data);
                        unset($errors);
                        unset($employee);
                    }
                    unset($chunk);
                    unset($lines);
                }
            }

            // Flush any remaining entities and clear the entity manager
            // Persist any remaining employees
            $this->batchPersist($employees);
            $this->entityManager->flush();
            $this->entityManager->clear();
        } catch (\Exception $e) {
            $this->entityManager->rollback();
//            throw $e;
        } finally {
            // Close the CSV file
            fclose($csvFile);
        }

        return true; // Import completed successfully
    }

    private function batchPersist($employees)
    {
        foreach ($employees as $employee) {
            $this->entityManager->persist($employee);
        }

        $this->entityManager->flush();
        $this->entityManager->clear();
        gc_collect_cycles();
    }

    private function createEmployee($data)
    {
        $employee = new User();
        // Set properties based on CSV columns
        $employee->setEmployeeId($data['employeeId']);
        $employee->setNamePrefix($data['namePrefix']);
        $employee->setFirstName($data['firstName']);
        $employee->setMiddleInitial($data['middleInitial']);
        $employee->setLastName($data['lastName']);
        $employee->setGender($data['gender']);
        $employee->setEmail($data['email']);
        $employee->setAge(floatval($data['age']));
        $employee->setJoinedAt($data['joinedAt']);
        $employee->setCompanyAge(intval($data['companyAge']));
        $employee->setPhoneNumber($data['phoneNumber']);
        $employee->setPlaceName($data['placeName']);
        $employee->setCountry($data['country']);
        $employee->setCity($data['city']);
        $employee->setZip($data['zip']);
        $employee->setRegion($data['region']);
        $employee->setUsername($data['username']);
        $employee->setBirthdayDate($data['birthdayDate']);
        $employee->setBirthdayTime($data['birthdayTime']);

        return $employee;
    }

    private function prepareData($rowData): array
    {
        return [
            'employeeId' => $rowData[0] ?? null,
            'namePrefix' => $rowData[1] ?? null,
            'firstName' => $rowData[2] ?? null,
            'middleInitial' => $rowData[3] ?? null,
            'lastName' => $rowData[4] ?? null,
            'gender' => $rowData[5] ?? null,
            'email' => $rowData[6] ?? null,
            'birthdayDate' => \DateTime::createFromFormat("n/j/Y", $rowData[7]) ?: null,
            'birthdayTime' => \DateTime::createFromFormat("h:i:s A", $rowData[8]) ?: null,
            'age' => floatval($rowData[9]) ?? null,
            'joinedAt' => new \DateTime($rowData[10]) ?? null,
            'companyAge' => intval($rowData[11]) ?? null,
            'phoneNumber' => $rowData[12] ?? null,
            'placeName' => $rowData[13] ?? null,
            'country' => $rowData[14] ?? null,
            'city' => $rowData[15] ?? null,
            'zip' => $rowData[16] ?? null,
            'region' => $rowData[17] ?? null,
            'username' => $rowData[18] ?? null,
        ];
    }

    private function validateEmployee($data)
    {
        $fields = [
            'username' => [
                new Assert\NotBlank(),
                new CustomAssert\Username()
            ],
            'employeeId' => [
                new Assert\NotBlank(),
            ],
            'namePrefix' => [],
            'firstName' => [
                new Assert\NotBlank(),
            ],
            'middleInitial' => [],
            'lastName' => [
                new Assert\NotBlank(),
            ],
            'gender' => [
                new Assert\NotBlank(),
                new Assert\Choice(["F", "M"]),
            ],
            'email' => [
                new Assert\NotBlank(),
                new Assert\Email(),
                new CustomAssert\EmailExisting()
            ],
            'birthdayDate' => [
                new Assert\NotBlank(),
                new Assert\NotNull()
            ],
            'birthdayTime' => [
                new Assert\NotBlank(),
            ],
            'age' => [
                new Assert\NotBlank(),
            ],
            'joinedAt' => [
                new Assert\NotBlank(),
            ],
            'companyAge' => [
                new Assert\NotBlank(),
            ],
            'phoneNumber' => [
                new Assert\NotBlank(),
            ],
            'placeName' => [],
            'zip' => [
                new Assert\NotBlank(),
            ],
            'region' => [
                new Assert\NotBlank(),
            ],
            'country' => [
            ],
            'city' => [],
        ];

        $constraints = new Assert\Collection(
            [
                'allowExtraFields' => false,
                'fields' => $fields,
            ]
        );

        $violations = $this->validator->validate($data, $constraints);
        return count($violations) > 0;
    }
}