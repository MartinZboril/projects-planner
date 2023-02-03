<?php

namespace App\Services;

use Exception;
use App\Models\{ClientFile, File, MilestoneFile, ProjectFile, TaskFile, TicketFile};
use Illuminate\Http\UploadedFile;
use Illuminate\Support\ValidatedInput;
use Illuminate\Support\Facades\Storage;

class FileService
{
    /**
     * Upload file to storage and database.
     */
    public function upload(UploadedFile $uploadedFile, String $collection): File
    {
        $name = $uploadedFile->hashName();
        $path = $collection . '/' . $name;

        Storage::disk(config('app.uploads.disk'))->put($collection, $uploadedFile);

        $file = new File;
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
     * Upload with specified relations.
     */
    public function uploadWithRelations(ValidatedInput $inputs, Array $uploadedFiles): void
    {
        foreach ($uploadedFiles as $uploadedFile) {
            $this->saveRelation($inputs->type, $inputs->parent_id, $uploadedFile);
        }
    }

    /**
     * Remove file by record id.
     */
    public function removeFile(int $fileId): void
    {
        unlink(public_path('storage/' . File::find($fileId)->path));
        File::destroy($fileId);   
    }

    /**
     * Save relation for file.
     */
    protected function saveRelation(string $type, int $parentId, UploadedFile $uploadedFile): void
    {
        switch ($type) {
            case 'client':
                ClientFile::create([
                    'client_id' => $parentId,
                    'file_id' => $this->upload($uploadedFile, 'clients/files')->id
                ]);
                break;
            case 'project':
                ProjectFile::create([
                    'project_id' => $parentId,
                    'file_id' => $this->upload($uploadedFile, 'projects/files')->id
                ]);
                break;
            case 'milestone':
                MilestoneFile::create([
                    'milestone_id' => $parentId,
                    'file_id' => $this->upload($uploadedFile, 'milestones/files')->id
                ]);
                break;
            case 'task':
                TaskFile::create([
                    'task_id' => $parentId,
                    'file_id' => $this->upload($uploadedFile, 'tasks/files')->id
                ]);
                break;
            case 'ticket':
                TicketFile::create([
                    'ticket_id' => $parentId,
                    'file_id' => $this->upload($uploadedFile, 'tickets/files')->id
                ]);
                break;
            default:
                throw new Exception('For the sent type was not found relationship to save!');
                break;
        }
    }
}