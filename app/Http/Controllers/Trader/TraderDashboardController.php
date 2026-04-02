<?php

namespace App\Http\Controllers\Trader;

use App\Http\Controllers\Controller;
use App\Models\Trader;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TraderDashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::guard('trader')->user();
        if (! $user) {
            return redirect()->route('trader.login.form');
        }
        $trader = $user instanceof Trader
            ? $user
            : Trader::where('user_id', $user->id)->firstOrFail();
        if ($trader->status !== Trader::STATUS_APPROVED) {
            return redirect()->route('trader.login.form')->with('error', 'حسابك غير مفعل للتاجر');
        }

        return redirect()->route('dashboard.vendor.index');
    }
}
