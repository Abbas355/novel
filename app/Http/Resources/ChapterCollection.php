<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ChapterCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return $this->collection->map(function ($chapter) {
            return new ChapterResource($chapter);
        })->toArray();
    }
}