<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeviceRegisterRequest;
use App\Models\Subscription;
use App\Models\Device;
use App\Services\MarketAPI\MarketAPI;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DeviceController extends Controller
{
    public function register(DeviceRegisterRequest $request)
    {
        $device = Device::create($request->validated());
        $subscription = Subscription::create([
            'device_id' => $device->id,
            'app_id' => $request->input('app_id')
        ]);

        return response()->json([
            'token' => $subscription->createToken()
        ]);
    }

    public function checkSubscription(Request $request)
    {
        $subscription = Subscription::findByTokenOrFail($request->input('token'));
        
        return response()->json([
            'status' => $subscription->status
        ]);
    }

    public function purchase(Request $request)
    {
        $subscription = Subscription::findByTokenOrFail($request->input('token'));
        $verifyResult = MarketAPI::forSubscription($subscription)->verifyReceipt($request->input('receipt'));
        if ($verifyResult) {
            $subscription->receipt = $request->input('receipt');
            $subscription->expires_at = $verifyResult;
            $subscription->status = 'active';
            $subscription->save();
            return response()->json([
                $verifyResult
            ]);
        }

        return response()->json([], 500);
    }
}
