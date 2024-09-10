<?php

namespace App\Http\Requests;

use App\DTOs\CreateDoctorFileLessonMaterialDTO;
use App\Models\DoctorFileLesson;
use App\Services\Interfaces\DoctorFileLessonServiceInterface;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CreateDoctorFileLessonMaterialRequest extends FormRequest
{
    public DoctorFileLesson $lesson;

    public function __construct(
        protected DoctorFileLessonServiceInterface $service,
    )
    {
        parent::__construct();
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if (! $this->route('uuid')) {
            throw new NotFoundHttpException("파라미터가 존재하지 않습니다.");
        }

        $lesson = $this->service->find($this->route('uuid'));
        if (! $lesson) {
            throw new NotFoundHttpException("리소스가 존재하지 않습니다.");
        }

        $this->lesson = $lesson;
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
            'title'       => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:1000'],
            'color_code'  => ['required', 'string', 'max:20'],
            'file'        => ['nullable', 'mimes:pdf,pptx,ppt,doc,docx,xlsx,xls,csv,zip,png,jpeg,webp,jpg,gif,mp4', 'max:30720'],
        ];
    }

    public function toDTO(): CreateDoctorFileLessonMaterialDTO
    {
        return CreateDoctorFileLessonMaterialDTO::createFromRequest($this);
    }
}
