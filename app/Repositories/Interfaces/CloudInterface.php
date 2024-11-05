<?php

namespace App\Repositories\Interfaces;

interface CloudInterface
{

    public function insertCloud($image, $folder);
    public function deleteCloud($image);
}
