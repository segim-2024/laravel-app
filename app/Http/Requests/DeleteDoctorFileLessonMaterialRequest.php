<?php

namespace App\Http\Requests;

use App\Models\DoctorFileLessonMaterial;
use App\Services\Interfaces\DoctorFileLessonMaterialServiceInterface;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DeleteDoctorFileLessonMaterialRequest extends FormRequest
{
    public DoctorFileLessonMaterial $material;

    public function __construct(
        protected DoctorFileLessonMaterialServiceInterface $service,
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

        $material = $this->service->findByUUID($this->route('uuid'));
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
            //
        ];
    }
}
