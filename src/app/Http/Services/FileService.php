<?php

namespace App\Http\Services;

use Uuid;
use Illuminate\Http\Request;

class FileService
{

    public function save_file(Request $request, String $file_type, String $path): String
    {
        if($request->hasFile($file_type)){
            $uuid = Uuid::generate(4)->string;
            $image = $uuid.'-'.$request[$file_type]->getClientOriginalName();
            $request[$file_type]->storeAs($path,$image);
            return $image;
        }

        return null;
    }

    public function delete_file(String $path): void
    {
        if(file_exists(storage_path($path))){
            unlink(storage_path($path));
        }
    }

}
