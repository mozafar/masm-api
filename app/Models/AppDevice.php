<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Support\Str;

class AppDevice extends Pivot
{
    use HasFactory;
    
    public $incrementing = true;
    public $timestamps = false;
    protected $guarded = [];

    public function createToken()
    {
        $plainTextToken = Str::random(40);
        $this->token = hash('sha256', $plainTextToken);
        $this->save();
        return $this->id.'|'.$plainTextToken;
    }

    public function app()
    {
        return $this->belongsTo(App::class);
    }

    public function device()
    {
        return $this->belongsTo(Device::class);
    }

    public static function findByToken($token)
    {
        if (strpos($token, '|') === false) {
            return static::where('token', hash('sha256', $token))->first();
        }

        [$id, $token] = explode('|', $token, 2);

        if ($instance = static::find($id)) {
            return hash_equals($instance->token, hash('sha256', $token)) ? $instance : null;
        }

        return null;
    }

    public static function findByTokenOrFail($token)
    {
        $device = static::findByToken($token);
        if (is_null($device)) {
            throw new ModelNotFoundException();
        }

        return $device;
    }
}
