<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE)]
final class BanWord extends Constraint
{
    public function __construct(
        public string $message = 'This contains the ban word {{ banWord }}',
        public array $banWords = ['spam', 'viagra'],

    ) {
        parent::__construct([], null, null);
    }
}
