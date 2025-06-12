<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class TenderCreateDto
{
    #[Assert\NotBlank]
    public string $externalCode;

    #[Assert\NotBlank]
    public string $number;

    #[Assert\NotBlank]
    public string $status;

    #[Assert\NotBlank]
    public string $title;

    #[Assert\NotBlank]
    #[Assert\DateTime(format: "Y-m-d H:i:s", message: "Дата должна быть в формате 'Y-m-d H:i:s'")]
    public string $updatedAt;
}