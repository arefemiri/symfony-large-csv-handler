<?php

namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

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
        // Open the CSV file for reading
        $csvFile = fopen($csvFilePath, 'rb');

        if ($csvFile === false) {
            return false; // Failed to open the file
        }

        // Skip the first line (header)
        fgetcsv($csvFile);

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

                // Check if the CSV row has the expected number of columns (19 in this case)
                if (count($rowData) === 19) {
                    // Create a DateTime object from the combined date and time string
                    $timeFormat = 'h:i:s A';
                    $birthDateTime = \DateTime::createFromFormat($timeFormat, $rowData[8]);
                    $employee = new User();

                    // Create a DateTime object from the "n/j/Y" date format
                    $birthDate = \DateTime::createFromFormat("n/j/Y", $rowData[7]);

                    if ($birthDate !== false) {
                        // Set the timezone as needed
                        $employee->setBirthdayDate($birthDate);
                    }

                    if ($birthDateTime !== false) {
                        // Set the timezone as needed
                        $employee->setBirthdayTime($birthDateTime);
                    }

                    // Set other properties based on CSV columns
                    // ...

                    // Validate the entity
                    $errors = $this->validator->validate($employee);

                    if (count($errors) === 0) {
                        $this->entityManager->persist($employee);
                        $counter++;

                        if ($counter % $batchSize === 0) {
                            // Flush and clear the entity manager to release memory
                            $this->entityManager->flush();
                            $this->entityManager->clear();
                            gc_collect_cycles();
                        }
                    }
                }
            }
        }

        // Flush any remaining entities and clear the entity manager
        $this->entityManager->flush();
        $this->entityManager->clear();

        // Close the CSV file
        fclose($csvFile);

        return true; // Import completed successfully
    }
}