<?php

namespace App\Models\Interfaces;

interface CardInterface
{
    public function getId(): int;

    public function getMemberId(): string;

    public function getName(): string;

    public function getNumber(): string;

    public function getKey(): string;

    public function delete(): ?bool;
}