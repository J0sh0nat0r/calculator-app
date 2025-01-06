<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\V1;

use App\Rules\Expr;
use Illuminate\Foundation\Http\FormRequest;

final class StoreCalculationRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'expr' => ['required', 'string', 'max:32768', new Expr],
        ];
    }
}
