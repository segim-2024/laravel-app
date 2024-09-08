<?php

namespace App\Http\Requests;

use App\DTOs\UpdateDoctorFileLessonMaterialDTO;
use App\Models\DoctorFileLessonMaterial;
use App\Services\Interfaces\DoctorFileLessonMaterialServiceInterface;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UpdateDoctorFileLessonMaterialRequest extends FormRequest
{
    public DoctorFileLessonMaterial $material;
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(DoctorFileLessonMaterialServiceInterface $service): bool
    {
        if (! $this->route('uuid')) {
            throw new NotFoundHttpException("파라미터가 존재하지 않습니다.");
        }

        $material = $service->findByUUID($this->route('uuid'));
        if (! $material) {
            throw new NotFoundHttpException("리소스가 존재하지 않습니다.");
        }

        $this->material = $material;
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
            'file'        => ['required', 'mimes:pdf,pptx,ppt,doc,docx,xlsx,csv,png,jpeg,webp,jpg,gif,mp4', 'max:30720'],
        ];
    }


    public function toDTO(): UpdateDoctorFileLessonMaterialDTO
    {
        return UpdateDoctorFileLessonMaterialDTO::createFromRequest($this);
    }
}
