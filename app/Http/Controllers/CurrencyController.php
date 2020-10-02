<?php

namespace App\Http\Controllers;

use App\Http\Requests\CurrencyRatesRequest;
use Illuminate\Support\Facades\Http;

class CurrencyController extends Controller
{
    /**
     * @param CurrencyRatesRequest $request
     * @return object
     */
    public function rates(CurrencyRatesRequest $request): object
    {
        $symbols = ['USD', 'GBP', 'EUR'];
        $currency_index = array_search($request->base, $symbols);
        if ($currency_index !== false) {
            unset($symbols[$currency_index]);
        }

        $symbols = implode(',', $symbols);
        $query = 'https://api.exchangeratesapi.io/latest?base=' . $request->base . '&symbols=' . $symbols;
        $response = Http::get($query);
        return response()->json($response->json(), $response->status());
    }
}
