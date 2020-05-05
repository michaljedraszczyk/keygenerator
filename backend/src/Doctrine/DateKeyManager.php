<?php

namespace App\Doctrine;

use App\Entity\DateKey;
use Doctrine\ORM\EntityManagerInterface;

class DateKeyManager
{
    /**
     * @var EntityManagerInterface|null
     */
    private $entityManager;

    /**
     * @required
     */
    public function setEntityManager(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getAll()
    {
        return $this->entityManager->getRepository(DateKey::class)->getAll();
    }

    /**
     * @throws \Doctrine\DBAL\ConnectionException
     */
    public function insertCollectionForPeriod(\DatePeriod $period, string $templateKey): bool
    {
        $this->entityManager->getConnection()->beginTransaction();
        $this->entityManager->getConnection()->setAutoCommit(false);

        try {
            foreach ($period as $date) {
                $this->entityManager->getConnection()->insert(
                    'date_key',
                    [
                        'date'          => $date->format('Y-m-d h:i:s'),
                        'generated_key' => $this->generateKeyForDates($date, $templateKey),
                    ]
                );

                $this->entityManager->getConnection()->commit();
            }

            $this->entityManager->clear();

        } catch (\Exception $exception) {
            $this->entityManager->getConnection()->rollback();

            throw $exception;
        }


        return true;
    }

    private function generateKeyForDates(\DateTimeInterface $date, string $templateKey): string
    {
        $month = $date->format('m');
        $day = $date->format('d');
        $year = $date->format('Y');

        $prefix = $month.$year[0].$year[1];
        $suffix = $day.$year[2].$year[3];

        return $prefix.$templateKey.$suffix;
    }
}
