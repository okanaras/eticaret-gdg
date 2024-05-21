<?php

namespace App\Traits;

use Illuminate\Http\RedirectResponse;
use Throwable;
use Illuminate\Support\Facades\Log;

trait GdgException
{
    public function exception(Throwable $th, string $route, string $errorDescription = 'Hata alindi'): RedirectResponse
    {
        toast($errorDescription, 'error');

        if ($th->getCode() == 400) {
            return redirect()
                ->back()
                ->withErrors([
                    'slug' => $th->getMessage()
                ])->withInput();
        }

        Log::error('Alinan Hata: ' . $th->getMessage(), [$th->getTraceAsString()]);
        return redirect()->route($route);
    }
}