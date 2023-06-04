<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use App\Models\File;
use Illuminate\Database\Eloquent\Model;

class FileService
{
    /**
     * Upload file to storage and database.
     */
    public function handleUpload(UploadedFile $uploadedFile, String $collection, Model $model=null): File
    {
        $name = $uploadedFile->hashName();
        $path = $collection . '/' . $name;

        Storage::disk(config('app.uploads.disk'))->put($collection, $uploadedFile);

        $file = new File;
        
        if ($model ?? false) {
            $file->fileable_id = $model->id;
            $file->fileable_type = $model::class;                
        }

        $file->name = $name;
        $file->file_name = $uploadedFile->getClientOriginalName();
        $file->mime_type = $uploadedFile->getClientMimeType();
        $file->path = $path;
        $file->disk = config('app.uploads.disk');
        $file->file_hash = md5(
                storage_path(
                    path: $path,
                ),
            );
        $file->collection = $collection;
        $file->size = $uploadedFile->getSize();
        $file->save();

        return $file;
    }

    /**
     * Remove file by record id.
     */
    public function handleRemoveFile(int $fileId): void
    {
        unlink(public_path('storage/' . File::find($fileId)->path));
        File::destroy($fileId);   
    }
}