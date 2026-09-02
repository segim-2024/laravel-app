<?php

namespace App\Http\Requests;

use App\DTOs\LibraryMemberLookupDTO;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;

/**
 * 회원 자격증명은 HTTP Basic 인증 헤더로 전달받는다.
 *
 * 쿼리스트링을 쓰면 nginx access log 에 평문으로 적재되므로 사용하지 않는다.
 */
class ShowLibraryMemberRequest extends FormRequest
{
    /**
     * Basic 인증 헤더로 계정이 전달되었는지 확인한다.
     */
    public function authorize(): bool
    {
        $account = $this->getUser();

        return is_string($account) && $account !== '';
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [];
    }

    public function toDTO(): LibraryMemberLookupDTO
    {
        return LibraryMemberLookupDTO::createFromRequest($this);
    }

    /**
     * 자격증명 누락은 인가 실패(403)가 아니라 인증 실패(401)로 응답한다.
     */
    protected function failedAuthorization(): void
    {
        throw new HttpResponseException(
            response()->json([
                'error' => 'Unauthorized',
                'code' => 'CREDENTIALS_REQUIRED',
            ], Response::HTTP_UNAUTHORIZED)
        );
    }
}
