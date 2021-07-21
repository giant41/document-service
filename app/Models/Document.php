<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'document_id',
        'name',
        'type',
        'folder_id',
        'is_public',
        'owner_id',
        'share',
        'content_text'
    ];
}
