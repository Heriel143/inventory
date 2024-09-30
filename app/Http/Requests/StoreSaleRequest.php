<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSaleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules()
    {
        return [
            // Validate the main sale details
            'customer_id' => 'required|integer|exists:customers,id',

            // Validate the sales_products array
            'sales_products' => 'required|array|min:1',
            'sales_products.*.product_id' => 'required|integer|exists:products,id',
            'sales_products.*.quantity' => 'required|integer|min:1',
            'sales_products.*.price' => 'required|numeric|min:0',

        ];
    }

    public function messages()
    {
        return [
            'id.required' => 'The sale ID is required.',
            'invoice_number.required' => 'Invoice number is required.',
            'customer_id.exists' => 'The customer ID must exist in the database.',
            'total_amount.required' => 'The total amount is required.',

            'sales_products.required' => 'At least one product is required in the sale.',
            'sales_products.*.product_id.exists' => 'Each product ID must exist in the database.',
            'sales_products.*.quantity.min' => 'The quantity of each product must be at least 1.',
            'sales_products.*.price.min' => 'The price must be a positive value.',
            'sales_products.*.subtotal.min' => 'The subtotal must be a positive value.',
        ];
    }
}
