<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Device extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $guarded = [];

    public function createToken()
    {
        $plainTextToken = Str::random(40);
        $this->token = hash('sha256', $plainTextToken);
        $this->save();
        return $this->id.'|'.$plainTextToken;
    }

    public function apps()
    {
        return $this->belongsToMany(App::class)->using(Subscription::class);;
    }
}
