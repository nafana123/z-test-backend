<?php

namespace App\Service;

use App\Entity\Tender;
use App\Repository\TenderRepository;
use Doctrine\ORM\EntityManagerInterface;

class TenderService
{
    private EntityManagerInterface $em;
    private TenderRepository $tenderRepository;

    public function __construct(EntityManagerInterface $em, TenderRepository $tenderRepository)
    {
        $this->em = $em;
        $this->tenderRepository = $tenderRepository;
    }

    public function createTender($data): Tender
    {
        $updatedAt = \DateTime::createFromFormat('Y-m-d H:i:s', $data['updatedAt']);

        $tender = new Tender();
        $tender->setExternalCode($data['externalCode']);
        $tender->setNumber($data['number']);
        $tender->setStatus($data['status']);
        $tender->setTitle($data['title']);
        $tender->setUpdatedAt($updatedAt);

        $this->em->persist($tender);
        $this->em->flush();

        return $tender;
    }

    public function getTenders(?string $name, ?string $date): array
    {
        return $this->tenderRepository->filters($name, $date);
    }
}