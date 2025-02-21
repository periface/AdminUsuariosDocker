<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserArea extends Model
{
    //
    use HasFactory;

    public $userId;
    public $areaId;

    protected $table = 'user_area';
    protected $fillable = ['userId', 'areaId','rolId'];

    public function usuarios(){
        return $this->belongsTo(User::class, 'userId');
    }

    public function areas(){
        return $this->belongsTo(Area::class, 'areaId');
    }
}
