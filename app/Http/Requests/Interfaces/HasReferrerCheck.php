<?php

namespace App\Http\Requests\Interfaces;

interface HasReferrerCheck {
    /**
     * @return bool
     */
    public function isWhale(): bool;
}
