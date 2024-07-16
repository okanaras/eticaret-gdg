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

    public function jsonException(Throwable $th, array $data = [], int $statusCode = 500)
    {
        Log::error('Alinan Hata: ' . $th->getMessage(), [$th->getTraceAsString()]);

        return response()
            ->json()
            ->setData($data)
            ->setStatusCode($statusCode)
            ->setCharset('utf-8')
            ->header('Content-Type', 'application.json')
            ->setEncodingOptions(JSON_UNESCAPED_UNICODE);
    }
}