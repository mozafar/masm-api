<?php

namespace App\Models;

use App\Services\Callback\CallbackSubject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class App extends Model implements CallbackSubject
{
    use HasFactory, Notifiable;

    public $timestamps = false;
    protected $guarded = [];

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function devices()
    {
        return $this->belongsToMany(Device::class)->using(Subscription::class);
    }

    public function os()
    {
        return $this->belongsTo(OS::class);
    }

    public function callbackUrl(): string
    {
        return $this->callback_url;
    }
}
