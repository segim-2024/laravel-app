<?php

namespace App\DTOs;

use App\Http\Requests\GetListDoctorFileSeriesRequest;

class GetListDoctorFileSeriesDTO
{
    public function __construct(
        public bool $isWhale,
    ) {}

    public static function createFromRequest(GetListDoctorFileSeriesRequest $request):self
    {
        return new self(
            $request->isWhale
        );
    }
}
