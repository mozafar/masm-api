<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeviceRegisterRequest;
use App\Models\Device;
use App\Services\MarketAPI\MarketAPI;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DeviceController extends Controller
{
    public function register(DeviceRegisterRequest $request)
    {
        MarketAPI::driver('google-play')->verifySubscription('123123123');
        $data = array_merge($request->validated(), [
            'token' => hash('sha256', $plainTextToken = Str::random(40))
        ]);
        $device = Device::create($data);

        return response()->json([
            'token' => $device->id . '|' . $plainTextToken
        ]);
    }

    public function checkSubscription(Request $request)
    {
        MarketAPI::driver('google-play')->verifySubscription('123123123');
    }

    public function purchase(Request $request)
    {
        MarketAPI::verifyReciept();
    }
}
