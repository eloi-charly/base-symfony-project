<?php

namespace App\DTO;

class CreateRecipeDTO
{
    public function __construct(
        public readonly string $title,
        public readonly string $slug,
        public readonly string $content,
        public readonly int $duration,
    ) {}
}
