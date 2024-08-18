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
            'title'       => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'color_code'  => ['required', 'string', 'max:255'],
            'file'        => ['required', 'mimes:pdf,pptx,ppt,doc,docx', 'max:10120'],
        ];
    }

    public function toDTO(): CreateDoctorFileLessonMaterialDTO
    {
        return CreateDoctorFileLessonMaterialDTO::createFromRequest($this);
    }
}
