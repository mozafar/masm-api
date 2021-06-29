## Services
All services are availabe in `app/Services` and contains:
### MarketAPI:
This service developed to mock stores API and can easily changed to a real API client using config file available in its folder. to make request you can use `MarketAPI` facade:
```php
use App\Models\Subscription;
use App\Services\MarketAPI\MarketAPI;

$subscription = Subscription::find(1);
$status = MarketAPI::forSubscription($this->subscription)->checkSubscription();
```
### Callback:
This module utilize laravel notifications system to send web request when a subscription status changes. it can bind to a model using `SendCallback` trait like this:
```php
use App\Services\Callback\CallbackAttributes;
use App\Services\Callback\SendCallback;

class Subscription extends Pivot implements CallbackAttributes
{
    use SendCallback;
    
    protected static function booted()
    {
        static::saving(self::sendCallback());
    }
``` 
on every change to status of subscription a notification will be sent to specified url and also notifications can be queued and run. notifications will be retried in case of failure.

### Worker
Worker module contains two jobs which can add subscription checks to queue. when `AddSubscriptionsJob` dispached it adds all active subscriptions to queue. but beacuase of large number of records it uses a cursor to reduce memory usage and also includes jobs chunking to prevent high number of jobs add at the same time:
```php
Subscription::active()
    ->cursor()
    ->map(fn (Subscription $subscription) => $this->createCheckSubscriptionJob($subscription))
    ->filter()
    ->chunk(1000)
    ->each(function (LazyCollection $jobs) use ($batch) {
        $batch->add($jobs);
    });
```
as soon as fist chuck added to queue `CheckSubscriptionJob` starts for each subscription and checks the status using ‍`MarketAPI`. to speed up adding in invoking jobs `redis`. every job in case of failure retried twice.

## DB
- DB schema in SQL fromat is available in `database/db.sql`.
- Model factories added for all models
## Installation

```sh
git clone https://github.com/mozafar/masm-api.git masm-api
cd masm-api
composer install
```

## Test
```sh
php artisan test
```