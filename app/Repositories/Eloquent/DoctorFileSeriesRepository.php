<?php
namespace App\Repositories\Eloquent;

use App\Models\DoctorFileSeries;
use App\Repositories\Interfaces\DoctorFileSeriesRepositoryInterface;

class DoctorFileSeriesRepository extends BaseRepository implements DoctorFileSeriesRepositoryInterface
{
    public function __construct(DoctorFileSeries $model)
    {
        parent::__construct($model);
    }

    /**
     * @inheritDoc
     */
    public function find(string $uuid): ?DoctorFileSeries
    {
        return DoctorFileSeries::where('series_uuid', '=', $uuid)->first();
    }
}
