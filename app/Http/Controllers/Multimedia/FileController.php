<?php

namespace App\Http\Controllers\Multimedia;

use App\Http\Controllers\MyBaseController;
use App\Models\File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use League\Glide\Responses\LaravelResponseFactory;
use League\Glide\ServerFactory;

class FileController extends MyBaseController
{

    public function postSaveFile($files)
    {
        foreach ($files as $file) {
            $this->postSaveFiles($file['file'], $file['params']);
        }
    }

  
    public function saveFile($file, $directory)
    {
        $destinationPath = public_path() . $directory;
        $fileOriginalName = $file->getClientOriginalName();
        $extension = pathinfo($fileOriginalName, PATHINFO_EXTENSION);
        $fileName = uniqid() . '_' . $fileOriginalName;
        if ($file->move($destinationPath, $fileName)) {
            return $fileName;
        }
        return '';
    }

    /**
     * @param $file
     * @param $directory
     * @return string
     * @throws \Exception
     */
    public function saveFileAwsS3($file, $directory)
    {
        try {
            $fileOriginalName = $file->getClientOriginalName();
            $extension = pathinfo($fileOriginalName, PATHINFO_EXTENSION);
            if(config('constants.logicFileSystem')=='s3'){
                Storage::disk(config('constants.logicFileSystem'))->put("$directory/$fileOriginalName", file_get_contents($file), 'public');
            }else{
            Storage::disk(config('constants.logicFileSystem'))->put("uploads/$directory/$fileOriginalName", file_get_contents($file), 'public');
            }
            return $fileOriginalName;
        } catch (\Exception $e) {
            Log::error($e->getMessage(), [$file->getClientOriginalName(),]);
            throw $e;
        }
    }

    public function deleteFile($id)
    {
        try {
            $File = File::find($id);
            $folder = "/" . $file->folder;
            $path = $folder . '/' . $file->file_name;

            if (Storage::disk(config('constants.logicFileSystem'))->exists($path)) {
                Storage::disk(config('constants.logicFileSystem'))->delete($path);
            }

            if ($file->delete()) {
                return true;
            } else {
                return false;
            }
        } catch (\Exception $e) {
            return false;
        }
    }

    public function show($path)
    {
//        $server = ServerFactory::create([
        //            'response' => new LaravelResponseFactory(app('request')),
        //            'source' => $filesystem->getDriver(),
        //            'cache' => $filesystem->getDriver(),
        //            'cache_path_prefix' => '.cache',
        //            'base_url' => 'img',
        //        ]);

        $server = ServerFactory::create([
            // Cache filesystem
            'cache' => Storage::disk(config('constants.logicFileSystem'))->getDriver(),
            // Cache filesystem path prefix
            'cache_path_prefix' => '.cache',
            // Source filesystem
            'source' => Storage::disk(config('constants.logicFileSystem'))->getDriver(),
            // Response
            'response' => new LaravelResponseFactory(),
//            'base_url' => 'img',
            // Default image manipulations
            'defaults' => [
                'q' => 80,
            ],
        ]);

        return $server->getFilesResponse($path, request()->all());
    }
}
