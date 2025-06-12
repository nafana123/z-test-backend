<?php

namespace App\Controller;

use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Dto\UserRegisterDto;

#[Route('/api/auth')]
class AuthController extends AbstractController
{
    private UserService $userService;

    public function __construct(UserService $userService)
    {
       $this->userService = $userService;
    }

    #[Route('/register', methods: ['POST'])]
    public function register(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $dto = new UserRegisterDto();
        $dto->email = $data['email'] ?? null;
        $dto->login = $data['login'] ?? null;
        $dto->password = $data['password'] ?? null;

        $result = $this->userService->registerUser($dto);

        if (isset($result['errors'])) {
            return $this->json(['errors' => $result['errors']], 400);
        }

        return $this->json(['message' => 'Пользователь успешно зарегистрирован'], 201);
    }

    #[Route("/login")]
    public function index(): Response
    {

    }
}