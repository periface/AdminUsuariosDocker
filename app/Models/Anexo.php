<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Anexo extends Model
{

    use HasFactory;
    protected $table = 'anexos';
    public int $id;
    public string $fileName;
    public string $filePath;
    public string $fileType;
    public string $fileSize;
    public string $fileExtension;
    public string $fileDescription;
    public string $fileStatus;
    public int $evaluacionResultId;
    protected $fillable = [
        'fileName',
        'filePath',
        'fileType',
        'fileSize',
        'fileExtension',
        'fileDescription',
        'fileStatus',
        'evaluacionResultId'
    ];
}
