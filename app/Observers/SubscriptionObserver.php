<?php

namespace App\Observers;

use App\Models\Subscription;

class SubscriptionObserver
{
    public function saving(Subscription $subscription)
    {
        $dirtyColumns = $subscription->getDirty();
        if (in_array('status', $dirtyColumns)) {

        }
    }
}
