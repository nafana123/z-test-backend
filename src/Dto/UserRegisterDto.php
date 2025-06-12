<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class UserRegisterDto
{
    #[Assert\NotBlank(message: "Email не должен быть пустым")]
    #[Assert\Email(message: "Некорректный email")]
    public ?string $email = null;

    #[Assert\NotBlank(message: "Логин не должен быть пустым")]
    public ?string $login = null;

    #[Assert\NotBlank(message: "Пароль не должен быть пустым")]
    #[Assert\Length(min: 6, minMessage: "Пароль должен содержать минимум 6 символов")]
    public ?string $password = null;
}