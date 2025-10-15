<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'face'            => ['nullable','in:person,company'],
            'customer_name'   => ['required','string','max:255'],
            'customer_phone'  => ['required','string','max:255'],
            'customer_email'  => ['nullable','email','max:255'],

            'delivery'        => ['nullable','in:0,1'],
            'delivery_price'  => ['nullable','numeric','min:0'],
            'city'            => ['nullable','string','max:255'],
            'address'         => ['nullable','string','max:500'],
            'message'         => ['nullable','string','max:2000'],

            'items'                 => ['nullable','array'],
            'items.*.product_id'    => ['nullable','integer','exists:products,id'],
            'items.*.name'          => ['required','string','max:255'],
            'items.*.code'          => ['nullable','string','max:255'],
            'items.*.price'         => ['required','numeric','min:0'],
            'items.*.quantity'      => ['required','integer','min:1'],
            'items.*.montage'       => ['nullable','in:0,1'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $json = $this->input('items_json');
        if (is_string($json) && $json !== '') {
            $decoded = json_decode($json, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                $this->merge(['items' => $decoded]);
            }
        }
    }
}
