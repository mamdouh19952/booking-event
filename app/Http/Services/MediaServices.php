<?php

namespace App\Http\Services;

use Illuminate\Http\UploadedFile;

class MediaServices
{


    public function upload($model, $files, string $collection)
    {
        if (!$files) return;

        if ($files instanceof UploadedFile) {
            return $model->addMedia($files)
                ->toMediaCollection($collection);
        }


        $media = [];

        foreach ($files as $file) {
            $media[] = $model->addMedia($file)
                ->toMediaCollection($collection);
        }

        return $media;
    }

    public function deleteMedia($model, $collection = "images")
    {
        if ($model->getMedia($collection)->isNotEmpty()) {
            $model->clearMediaCollection($collection);
        }

        return true;
    }


    public function update($model, $files, string $collection = "images")
    {
        $this->deleteMedia($model, $collection);
        return $this->upload($model, $files, $collection);

    }

       public function deleteMediaItem($model, $mediaId, $collection = "images")
 {
    $mediaItem = $model->getMedia($collection)
        ->where('id', $mediaId)
        ->first();

    if ($mediaItem) {
        $mediaItem->delete();
        return true;
    }

    return false;
}



}




