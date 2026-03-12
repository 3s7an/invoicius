<?php

namespace App\Http\Controllers\Api;

use App\Contracts\AutomatizationServiceInterface;
use App\Http\Controllers\Controller;
use App\Http\Traits\VerifiesN8nRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AutomatizationController extends Controller
{
    use VerifiesN8nRequests;

    public function __construct(
        private readonly AutomatizationServiceInterface $automatizationService,
    ) {
    }

    public function process(Request $request): JsonResponse
    {
        $this->verifyN8nRequest($request);

        $results = $this->automatizationService->processDueAutomatizations();

        return response()->json([
            'processed' => count($results),
            'results' => $results,
        ]);
    }
}
