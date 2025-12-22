<?php

namespace App\Models\Interfaces;

interface ProductInterface
{
    public function getId(): int;

    public function getName(): string;

    public function getPaymentDay(): string;

    public function getPrice(): int;
}