<?php

namespace Database\Seeders;

use App\Models\DoctorFileLesson;
use App\Models\DoctorFileLessonMaterial;
use App\Models\DoctorFileSeries;
use App\Models\DoctorFileVolume;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TestDoctorFileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $series = new DoctorFileSeries();
        $series->series_uuid = Str::orderedUuid();
        $series->title = '자료 박사';
        $series->sort = 0;
        $series->is_whale = false;
        $series->save();

        // Create a volume new instance
        $volume = new DoctorFileVolume();
        $volume->series_uuid = $series->series_uuid;
        $volume->volume_uuid = Str::orderedUuid();
        $volume->title = 'Volume 1';
        $volume->sort = 1;
        $volume->is_published = false;
        $volume->is_whale = false;
        $volume->save();

        // Create a lesson new instance
        $lesson = new DoctorFileLesson();
        $lesson->series_uuid = $series->series_uuid;
        $lesson->volume_uuid = $volume->volume_uuid;
        $lesson->lesson_uuid = Str::orderedUuid();
        $lesson->title = 'Lesson 1';
        $lesson->sort = 1;
        $lesson->is_whale = false;
        $lesson->save();

        $material = new DoctorFileLessonMaterial();
        $material->lesson_uuid = $lesson->lesson_uuid;
        $material->material_uuid = Str::orderedUuid();
        $material->save();
    }
}
