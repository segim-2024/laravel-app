<?php

namespace App\Http\Requests;

use App\Models\DoctorFileSeries;
use App\Services\Interfaces\DoctorFileSeriesServiceInterface;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DeleteDoctorFileSeriesRequest extends FormRequest
{
    public DoctorFileSeries $series;

    public function __construct(
        protected DoctorFileSeriesServiceInterface $service,
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

        $series = $this->service->find($this->route('uuid'));
        if (! $series) {
            throw new NotFoundHttpException("리소스가 존재하지 않습니다.");
        }

        $this->series = $series;
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
