<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CurrencyService
{
    public const USD_TO_SYP = 117;

    public function preferredCurrency(?User $user = null): string
    {
        $user = $user ?: Auth::user();
        $cur = strtoupper((string) ($user?->currency ?: session('currency') ?: 'USD'));
        return in_array($cur, ['USD', 'SYP'], true) ? $cur : 'USD';
    }

    public function setPreferredCurrency(string $currency, ?User $user = null): void
    {
        $cur = strtoupper(trim($currency));
        if (! in_array($cur, ['USD', 'SYP'], true)) {
            $cur = 'USD';
        }

        session(['currency' => $cur]);

        $user = $user ?: Auth::user();
        if ($user && \Illuminate\Support\Facades\Schema::hasColumn('users', 'currency')) {
            $user->currency = $cur;
            $user->save();
        }
    }

    public function convertUsd(float $amountUsd, string $toCurrency): float
    {
        $to = strtoupper($toCurrency);
        if ($to === 'SYP') {
            return $amountUsd * self::USD_TO_SYP;
        }

        return $amountUsd;
    }

    public function formatUsd(float $amountUsd, ?string $currency = null, ?string $locale = null): string
    {
        $cur = $currency ? strtoupper($currency) : $this->preferredCurrency();
        $amount = $this->convertUsd($amountUsd, $cur);

        if ($cur === 'SYP') {
            $formatted = number_format((float) round($amount), 0, '.', ',');
            return $formatted.' SYP';
        }

        $formatted = number_format((float) $amount, 2, '.', ',');
        return '$'.$formatted;
    }

    public function bootRequestCurrency(Request $request): void
    {
        $queryCur = strtoupper((string) $request->query('currency', ''));
        if (in_array($queryCur, ['USD', 'SYP'], true)) {
            session(['currency' => $queryCur]);
        }
    }
}

