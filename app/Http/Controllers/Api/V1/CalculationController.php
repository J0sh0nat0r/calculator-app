<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreCalculationRequest;
use App\Http\Resources\CalculationResource;
use App\Math\Contracts\Calculator;
use App\Models\Calculation;
use Illuminate\Foundation\Http\Middleware\HandlePrecognitiveRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

final class CalculationController extends Controller implements HasMiddleware
{
    /**
     * Get the middleware that should be assigned to the controller.
     *
     * @return Middleware[]
     */
    public static function middleware(): array
    {
        return [
            new Middleware(HandlePrecognitiveRequests::class, only: ['store']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $perPage = $request->clampedInteger('per_page', 1, 100, 25);

        return CalculationResource::collection(
            Calculation::latest('id')->cursorPaginate($perPage)
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCalculationRequest $request, Calculator $calculator): JsonResponse
    {
        $calculation = new Calculation($request->validated());

        $calculation->result = $calculator->evaluate($calculation->expr);

        $calculation->saveOrFail();

        return (new CalculationResource($calculation))->response()->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Calculation $calculation): CalculationResource
    {
        return new CalculationResource($calculation);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Calculation $calculation): Response
    {
        $calculation->deleteOrFail();

        return response(status: 204);
    }
}
