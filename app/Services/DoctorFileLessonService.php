<?php

namespace App\Services;

use App\Exceptions\CreateZipException;
use App\Models\DoctorFileLesson;
use App\Models\DoctorFileLessonMaterial;
use App\Models\File;
use App\Repositories\Interfaces\DoctorFileLessonRepositoryInterface;
use App\Services\Interfaces\DoctorFileLessonMaterialServiceInterface;
use App\Services\Interfaces\DoctorFileLessonServiceInterface;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use ZipArchive;

class DoctorFileLessonService implements DoctorFileLessonServiceInterface {
    public function __construct(
        protected DoctorFileLessonMaterialServiceInterface $materialService,
        protected DoctorFileLessonRepositoryInterface $repository
    ) {}

    /**
     * @inheritDoc
     */
    public function find(string $uuid): ?DoctorFileLesson
    {
        return $this->repository->find($uuid);
    }

    /**
     * @inheritDoc
     */
    public function delete(DoctorFileLesson $lesson): void
    {
        $lesson->materials->each(fn(DoctorFileLessonMaterial $material) => $this->materialService->delete($material));
        $this->repository->delete($lesson);
    }

    /**
     * @inheritDoc
     */
    public function createZip(DoctorFileLesson $lesson): void
    {
        $files = $lesson->materials->map(fn(DoctorFileLessonMaterial $material) => $material->file);

        // 임시 파일 경로 지정 (로컬 저장소)
        $tempDir = storage_path();
        $localZipPath = $tempDir."/".Str::orderedUuid().'.zip';
        $localFilePaths = collect();
        // Zip 파일 생성
        $zip = new ZipArchive();
        if ($zip->open($localZipPath, ZipArchive::CREATE) !== TRUE) {
            throw new CreateZipException("Zip 파일 만들기에 실패했어요.");
        }

        $files->each(function (File $file) use ($tempDir, $zip, $localFilePaths) {
            // 로컬 임시 경로에 파일 저장
            $localFilePath = $tempDir."/".Str::random(6).Str::uuid().'.'.$file->extension;
            $s3File = Storage::disk('s3')->get($file->path."/".$file->server_name);
            file_put_contents($localFilePath, $s3File);

            // 파일을 Zip에 추가
            $zip->addFile($localFilePath, $file->original_name);
            $localFilePaths->push($localFilePath);
        });

        // Zip 파일을 닫고 저장
        $zip->close();
        // Zip 파일 사이즈 구하기
        $zipSize = filesize($localZipPath);

        // 압축된 파일을 S3에 업로드
        $s3ZipPath = "doctor-file/lessons/{$lesson->lesson_uuid}/{$lesson->lesson_uuid}.zip";
        Storage::disk('s3')->put($s3ZipPath, file_get_contents($localZipPath));
        $pathFull = Storage::drive('s3')->url($s3ZipPath);

        // Zip 파일 삭제
        unlink($localZipPath);
        $localFilePaths->each(fn (string $path) => unlink($path));

        $file = new File();
        $file->uuid = $lesson->lesson_uuid;
        $file->extension = "zip";
        $file->server_name = "{$lesson->lesson_uuid}.zip";
        $file->original_name = $lesson->title.'_'.Carbon::now()->format('YmdHis').'.zip';
        $file->full_path = $pathFull;
        $file->path = "doctor-file/lessons/{$lesson->lesson_uuid}";
        $file->size = $zipSize;
        $file->save();
    }
}
