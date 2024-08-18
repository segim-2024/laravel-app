<?php

namespace App\Http\Requests;

use App\DTOs\UpdateDoctorFileVolumePosterDTO;
use App\Models\DoctorFileVolume;
use App\Services\Interfaces\DoctorFileVolumeServiceInterface;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UpdateDoctorFileVolumePosterRequest extends FormRequest
{
    public DoctorFileVolume $volume;

    public function __construct(
        protected DoctorFileVolumeServiceInterface $service,
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

        $volume = $this->service->find($this->route('uuid'));
        if (! $volume) {
            throw new NotFoundHttpException("리소스가 존재하지 않습니다.");
        }

        $this->volume = $volume;
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
            'poster' => ['required', 'mimes:jpg,jpeg,png,webp', 'max:4048'],
        ];
    }

    public function toDTO(): UpdateDoctorFileVolumePosterDTO
    {
        return UpdateDoctorFileVolumePosterDTO::createFromRequest($this);
    }
}
