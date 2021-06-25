<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class App extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $guarded = [];

    public function devices()
    {
        return $this->belongsToMany(Device::class)->using(Subscription::class);
    }

    public function os()
    {
        return $this->belongsTo(OS::class);
    }
}
