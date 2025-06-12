<?php

namespace App\Controller;

use App\Dto\TenderCreateDto;
use App\Entity\Tender;
use App\Service\TenderService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/tenders')]
class TenderController extends AbstractController
{
    private TenderService $tenderService;

    public function __construct(TenderService $tenderService)
    {
        $this->tenderService = $tenderService;
    }

    #[Route('', methods: ['GET'])]
    public function list(Request $request): JsonResponse
    {
        $name = $request->query->get('name');
        $date = $request->query->get('date');

        if ($date) {
            $dateObj = \DateTime::createFromFormat('Y-m-d', $date);
            $errors = \DateTime::getLastErrors();
            if (!$dateObj || $errors['warning_count'] > 0 || $errors['error_count'] > 0) {
                return $this->json([
                    'error' => 'Некорректный формат даты. Используйте формат Y-m-d'
                ], 400);
            }
        }

        $tenders = $this->tenderService->getTenders($name, $date);

        return $this->json($tenders, 200, [], ['groups' => ['tender:list']]);
    }


    #[Route('/{id}', methods: ['GET'])]
    public function getTender(?Tender $tender): JsonResponse
    {
        if (!$tender) {
            return $this->json(['error' => 'Тендер не найден'], 404);
        }

        return $this->json($tender, 200, [], ['groups' => ['tender:list']]);
    }

    #[Route('/add', methods: ['POST'])]
    public function addTender(Request $request, ValidatorInterface $validator): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        if ($data === null) {
            return $this->json(['error' => 'Невалидный JSON'], 400);
        }

        $dto = new TenderCreateDto();
        $dto->externalCode = $data['externalCode'] ?? '';
        $dto->number = $data['number'] ?? '';
        $dto->status = $data['status'] ?? '';
        $dto->title = $data['title'] ?? '';
        $dto->updatedAt = $data['updatedAt'] ?? '';

        $errors = $validator->validate($dto);
        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[$error->getPropertyPath()] = $error->getMessage();
            }
            return $this->json(['errors' => $errorMessages], 400);
        }

        try {
            $tender = $this->tenderService->createTender((array)$dto);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 400);
        }

        return $this->json($tender, 201, [], ['groups' => ['tender:list']]);
    }
}