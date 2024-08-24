<?php

namespace App\Http\Requests;

use App\Models\DoctorFileNotice;
use App\Services\Interfaces\DoctorFileNoticeServiceInterface;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DeleteDoctorFileNoticeRequest extends FormRequest
{
    public bool $isWhale;
    public DoctorFileNotice $notice;

    public function __construct(
        protected DoctorFileNoticeServiceInterface $service
    ) {
        parent::__construct();
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if ($this->user()->mb_level < 10) {
            throw new AccessDeniedHttpException("권한이 없습니다.");
        }

        $referer = $this->headers->get('referer');
        if (str_contains($referer, 'https://epamus.com')) {
            $this->isWhale = false;
        } elseif (str_contains($referer, 'https://englishwhale.com')) {
            $this->isWhale = true;
        } else {
            $this->isWhale = true;
            // throw new AccessDeniedHttpException('허용되지 않는 접근입니다.');
        }

        if (! $this->route('noticeId')) {
            throw new NotFoundHttpException('자료 박사 공지 사항을 찾을 수 없어요.');
        }

        $notice = $this->service->find($this->isWhale, $this->route('noticeId'));
        if (! $notice) {
            throw new NotFoundHttpException('자료 박사 공지 사항을 찾을 수 없어요.');
        }

        $this->notice = $notice;
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            //
        ];
    }
}
