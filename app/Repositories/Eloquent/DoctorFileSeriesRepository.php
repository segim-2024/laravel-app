<?php
namespace App\Repositories\Eloquent;

use App\DTOs\GetListDoctorFileSeriesDTO;
use App\Models\DoctorFileSeries;
use App\Repositories\Interfaces\DoctorFileSeriesRepositoryInterface;
use Illuminate\Support\Collection;

class DoctorFileSeriesRepository extends BaseRepository implements DoctorFileSeriesRepositoryInterface
{
    public function __construct(DoctorFileSeries $model)
    {
        parent::__construct($model);
    }

    /**
     * @inheritDoc
     */
    public function getList(GetListDoctorFileSeriesDTO $DTO): Collection
    {
        return DoctorFileSeries::with([
                'volumes' => function ($query) {
                    $query->where('is_published', '=', true)
                        ->with([
                            'poster',
                            'lessons' => [
                                'zip',
                                'materials' => [
                                    'file'
                                ]
                            ]
                        ]);
                }
            ])
            ->where('is_whale', '=', $DTO->isWhale)
            ->orderBy('sort')
            ->orderBy('title')
            ->get();
    }

    /**
     * @inheritDoc
     */
    public function find(string $uuid): ?DoctorFileSeries
    {
        return DoctorFileSeries::where('series_uuid', '=', $uuid)->first();
    }
}
