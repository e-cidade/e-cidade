<?php

namespace App\Services\DTO\Patrimonial;

class StatusReturnDto
{
    public bool $status = true;
    public string $message = '';
    public array $errors = [];
}
