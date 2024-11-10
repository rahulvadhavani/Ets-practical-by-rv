<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

class Upload extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $appends = ['file_link'];

    public function getFileLinkAttribute()
    {
        $link  =  $this->file_path ?  asset('storage/' . $this->file_path) : asset('dist/img/default-150x150.png');
        return $link;
    }

    public static function saveUpload($file, $uploadable, $usage)
    {
        $path = $file->store('uploads', 'public');
        Upload::create([
            'file_name' => $file->getClientOriginalName(),
            'file_path' => $path,
            'file_type' => $file->getMimeType(),
            'uploadable_id' => $uploadable->id,
            'uploadable_type' => get_class($uploadable),
            'file_usage' => $usage,
        ]);
    }

    public static function deleteUpload($files)
    {
        if (empty($files)) {
            return false;
        }
        $files = $files instanceof Collection ? $files : [$files];
        $ids =  [];
        foreach ($files as $key => $file) {
            $ids[] = $file->id;
            $filePath = $file?->file_path ?? null;


            if ($filePath) {
                if (Storage::disk('public')->exists($filePath)) {
                    Storage::disk('public')->delete($filePath);
                }
            }
        }
        if (!empty($ids)) {
            self::whereIn('id', $ids)->delete();
        }
    }
}
