<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Calculation;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Calculation
 */
final class CalculationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => (string) $this->id,
            'expr' => [
                'raw' => (string) $this->expr,
                'ast' => $this->expr,
            ],
            'result' => $this->result,
        ];
    }
}
