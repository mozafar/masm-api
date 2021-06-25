<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeviceRegisterRequest;
use App\Models\AppDevice;
use App\Models\Device;
use App\Services\MarketAPI\MarketAPI;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DeviceController extends Controller
{
    public function register(DeviceRegisterRequest $request)
    {
        $device = Device::create($request->validated());
        $appDevice = AppDevice::create([
            'device_id' => $device->id,
            'app_id' => $request->input('app_id')
        ]);

        return response()->json([
            'token' => $appDevice->createToken()
        ]);
    }

    public function checkSubscription(Request $request)
    {
        $subscription = AppDevice::findByTokenOrFail($request->input('token'));

        return response()->json([
            'status' => $subscription->status
        ]);
    }

    public function purchase(Request $request)
    {
        $appDevice = AppDevice::findByTokenOrFail($request->input('token'));
        $verifyResult = MarketAPI::forApp($appDevice->app)->verifyReceipt($request->input('receipt'));
        if ($verifyResult) {
            $appDevice->receipt = $request->input('receipt');
            $appDevice->expires_at = $verifyResult;
            $appDevice->status = 'active';
            $appDevice->save();
            return response()->json([
                $verifyResult
            ]);
        }

        return response()->json([], 500);
    }
}
