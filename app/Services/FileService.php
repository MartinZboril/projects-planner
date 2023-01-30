<?php

namespace App\Services;

use App\Models\File;
use Illuminate\Http\UploadedFile;
use App\Models\{Client, ClientFile};
use App\Enums\Routes\ClientRouteEnum;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\ValidatedInput;
use Illuminate\Support\Facades\Storage;

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

        if ($type == 'client') {
            foreach ($uploadedFiles as $uploadedFile) {
                ClientFile::create([
                    'client_id' => $parentId,
                    'file_id' => $this->upload($uploadedFile, 'clients/files')->id
                ]);
            }
        }
    }
    
    /**
     * Set up redirect for the action
     */
    public function setUpRedirect($type, $parentId): RedirectResponse
    {
        switch ($type) {                        
            case 'client':
                $redirectAction = ClientRouteEnum::Files;
                $redirectVars = ['client' => Client::find($parentId)];
                break;  

            default:
                return redirect()->back();
                break;
        }
        
        return (new RouteService)->redirect($redirectAction->value, $redirectVars);
    }
}