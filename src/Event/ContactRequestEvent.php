<?php

namespace App\Event;

use App\DTO\ContactDto;

class ContactRequestEvent
{
    public function __construct(public readonly ContactDto $data) {}
}
