<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * 회원은 존재하지만 비밀번호가 일치하지 않는 경우.
 */
class LibraryMemberPasswordMismatchException extends Exception
{
    public function render(): JsonResponse
    {
        return response()->json([
            'error' => 'Forbidden',
            'code' => 'PASSWORD_MISMATCH',
        ], Response::HTTP_FORBIDDEN);
    }
}
