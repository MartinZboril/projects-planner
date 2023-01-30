<?php

namespace App\Services;

use App\Models\File;
use App\Models\TaskFile;
use App\Models\ProjectFile;
use App\Models\MilestoneFile;
use Illuminate\Http\UploadedFile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\ValidatedInput;
use Illuminate\Support\Facades\Storage;
use App\Models\{ClientFile, TicketFile};

class FileService
{
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

    public function uploadWithRelations(ValidatedInput $inputs, Array $uploadedFiles)
    {
        $parentId = $inputs->parent_id;
        $type = $inputs->type;

        foreach ($uploadedFiles as $uploadedFile) {
            if ($type == 'client') {                
                ClientFile::create([
                    'client_id' => $parentId,
                    'file_id' => $this->upload($uploadedFile, 'clients/files')->id
                ]);
            } elseif ($type == 'ticket') {
                TicketFile::create([
                    'ticket_id' => $parentId,
                    'file_id' => $this->upload($uploadedFile, 'tickets/files')->id
                ]);
            } elseif ($type == 'task') {
                TaskFile::create([
                    'task_id' => $parentId,
                    'file_id' => $this->upload($uploadedFile, 'tasks/files')->id
                ]);
            } elseif ($type == 'milestone') {
                MilestoneFile::create([
                    'milestone_id' => $parentId,
                    'file_id' => $this->upload($uploadedFile, 'milestones/files')->id
                ]);
            } elseif ($type == 'project') {
                ProjectFile::create([
                    'project_id' => $parentId,
                    'file_id' => $this->upload($uploadedFile, 'projects/files')->id
                ]);
            }
        }
    }
    
    /**
     * Set up redirect for the action
     */
    public function setUpRedirect($type, $parentId): RedirectResponse
    {
        return redirect()->back();
    }
}