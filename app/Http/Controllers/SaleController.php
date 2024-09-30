<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Http\Requests\StoreSaleRequest;
use App\Http\Requests\UpdateSaleRequest;
use App\Http\Resources\SaleResource;
use App\Models\Product;
use App\Models\SalesProduct;

class SaleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sales = Sale::all();
        return SaleResource::collection(Sale::paginate(10));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSaleRequest $request)
    {
        // Validate the request (if using form request validation, this is automatically done)
        $validatedData = $request->validated();

        $lastSale = Sale::latest()->first();
        // Start by creating a new Sale
        $sale = new Sale();
        $sale->invoice_number = $lastSale->id + 1001; // Setting invoice number
        $sale->customer_id = $validatedData['customer_id'];
        $sale->total_amount = 0; // Initial value before calculating the total
        $sale->save();

        // Prepare to track the total amount
        $totalAmount = 0;

        // Loop through each sales product and calculate subtotal, then save
        foreach ($validatedData['sales_products'] as $salesProductData) {
            $product = Product::findOrFail($salesProductData['product_id']);

            $salesProduct = new SalesProduct();
            $salesProduct->sale_id = $sale->id;
            $salesProduct->product_id = $product->id;
            $salesProduct->price = $product->price;
            $salesProduct->quantity = $salesProductData['quantity'];
            $salesProduct->subtotal = $salesProduct->quantity * $product->price; // Calculate subtotal

            // Save the SalesProduct
            $salesProduct->save();

            // Add to the total amount
            $totalAmount += $salesProduct->subtotal;
        }

        // Update the total amount for the sale
        $sale->total_amount = $totalAmount;
        // $sale->invoice_number = $sale->id + 1000;
        $sale->save();

        // Return a success response
        return response()->json([
            'message' => 'Sale created successfully!',
            'sale' => $sale,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Sale $sale)
    {
        if ($sale != null) {

            $sale_products = $sale->salesProducts;
            return response()->json([
                $sale,
                $sale_products
            ]);
        } else {
            return response()->json(
                [
                    'status' => 404,
                    'message' => "Sale not found"
                ]
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Sale $sale)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSaleRequest $request, Sale $sale)
    {
        //
    }

    // getTotal
    public function getTotal($id)

    {
        $sale = Sale::find($id);
        $total = $sale->total_amount;
        return response()->json([
            'total' => $total
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Sale $sale)
    {
        // Delete all related salesProducts
        $sale->salesProducts()->delete();

        // Now delete the sale
        $sale->delete();

        // Return a response
        return response()->json([
            'message' => 'Sale and its products deleted successfully.'
        ], 200);
    }
}
