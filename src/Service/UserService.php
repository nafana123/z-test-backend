<?php

namespace App\Service;

use App\Dto\UserRegisterDto;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserService
{
    private EntityManagerInterface $em;
    private UserPasswordHasherInterface $passwordHasher;
    private ValidatorInterface $validator;
    private UserRepository $userRepository;
    public function __construct(
        EntityManagerInterface $em,
        UserPasswordHasherInterface $passwordHasher,
        ValidatorInterface $validator,
        UserRepository $userRepository
    )
    {
        $this->em = $em;
        $this->passwordHasher = $passwordHasher;
        $this->validator = $validator;
        $this->userRepository = $userRepository;
    }
    public function registerUser(UserRegisterDto $dto): array
    {
        $errors = $this->validator->validate($dto);
        $errorMessages = [];

        foreach ($errors as $error) {
            $errorMessages[$error->getPropertyPath()] = $error->getMessage();
        }

        if ($this->userRepository->findOneBy(['email' => $dto->email])) {
            $errorMessages['email'] = 'Пользователь с таким email уже зарегистрирован';
        }

        if (!empty($errorMessages)) {
            return ['errors' => $errorMessages];
        }

        $user = new User();
        $user->setEmail($dto->email);
        $user->setLogin($dto->login);
        $hashedPassword = $this->passwordHasher->hashPassword($user, $dto->password);
        $user->setPassword($hashedPassword);

        $this->em->persist($user);
        $this->em->flush();

        return ['user' => $user];
    }
}
