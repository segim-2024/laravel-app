<?php
namespace App\Repositories\Eloquent;

use App\Models\DoctorEssaySeries;
use App\Repositories\Interfaces\DoctorEssaySeriesRepositoryInterface;
use Illuminate\Support\Collection;

class DoctorEssaySeriesRepository extends BaseRepository implements DoctorEssaySeriesRepositoryInterface
{
    public function __construct(DoctorEssaySeries $model)
    {
        parent::__construct($model);
    }

    /**
     * @inheritDoc
     */
    public function getList(bool $isWhale): Collection
    {
        return DoctorEssaySeries::with([
                'volumes' => function ($query) {
                    $query->where('is_published', '=', true)
                        ->with([
                            'poster',
                            'lessons' => [
                                'materials' => ['file']
                            ]
                        ]);
                }
            ])
            ->where('is_whale', '=', $isWhale)
            ->orderBy('sort')
            ->orderBy('title')
            ->get();
    }

    /**
     * @inheritDoc
     */
    public function find(string $uuid): ?DoctorEssaySeries
    {
        return DoctorEssaySeries::where('series_uuid', '=', $uuid)->first();
    }
}
