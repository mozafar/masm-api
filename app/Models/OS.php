<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OS extends Model
{
    use HasFactory;

    protected $table = 'oses';
    public $timestamps = false;

    public function devices()
    {
        return $this->hasMany(Device::class);
    }

    public function apps()
    {
        return $this->hasMany(App::class);
    }
}
