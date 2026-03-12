<?php

namespace App\Http\Traits;

use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

trait VerifiesN8nRequests
{
    protected function verifyN8nRequest(Request $request): void
    {
        $headerName = config('services.n8n.header_name');
        $expectedToken = config('services.n8n.token');

        if (! $headerName || ! $expectedToken) {
            throw new HttpException(500, 'N8N credentials not configured.');
        }

        $providedToken = (string) $request->header($headerName);

        if (! hash_equals($expectedToken, $providedToken)) {
            throw new HttpException(401, 'Invalid n8n credentials.');
        }
    }
}
