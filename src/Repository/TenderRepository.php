<?php

namespace App\Repository;

use App\Entity\Tender;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;


class TenderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tender::class);
    }

    public function filters(?string $name, ?string $date): array
    {
        $qb = $this->createQueryBuilder('t');

        if ($name) {
            $qb->andWhere('t.title LIKE :name')
                ->setParameter('name', '%' . $name . '%');
        }

        if ($date) {
            $dateObj = \DateTime::createFromFormat('Y-m-d', $date);
            if ($dateObj) {
                $startDay = (clone $dateObj)->setTime(0, 0, 0);
                $endDay = (clone $dateObj)->setTime(23, 59, 59);

                $qb->andWhere('t.updatedAt BETWEEN :start AND :end')
                    ->setParameter('start', $startDay)
                    ->setParameter('end', $endDay);
            }
        }

        return $qb->getQuery()->getResult();
    }
}
