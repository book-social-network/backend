<?php

namespace App\Repositories;

use App\Models\Cloud;
use App\Repositories\Interfaces\CloudInterface;

class CloudRepository implements CloudInterface{
    public function insertCloud($image, $folder){
        $imagePath = $image->getRealPath();
        $uploadResponse = Cloudinary::upload($imagePath, [
            'folder' => $folder
        ]);
        return $uploadResponse->getSecurePath();
    }
    public function deleteCloud($image){
        $publicId = pathinfo($image, PATHINFO_FILENAME);
        Cloudinary::delete($publicId);
    }

}
