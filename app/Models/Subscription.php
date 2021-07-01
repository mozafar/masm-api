<?php

namespace App\Models;

use App\Services\Callback\CallbackAttributes;
use App\Services\Callback\SendCallback;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class Subscription extends Pivot implements CallbackAttributes
{
    use HasFactory, SendCallback;

    protected $table = 'subscriptions';
    public $incrementing = true;
    public $timestamps = true;
    protected $guarded = [];

    protected static function booted()
    {
        static::saving(self::sendCallback());
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function getAppId(): string
    {
        return $this->app_id;
    }

    public function getDeviceId(): string
    {
        return $this->device_id;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

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
