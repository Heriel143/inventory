<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SaleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call([
            CustomerSeeder::class,
            ProductSeeder::class,
        ]);

        $customers = \App\Models\Customer::all();
        $products = \App\Models\Product::all();

        $customers->each(function ($customer) use ($products) {
            $sales = \App\Models\Sale::factory()->count(5)->make();
            $customer->sales()->saveMany($sales);

            $sales->each(function ($sale) use ($products) {
                $salesProducts = \App\Models\SalesProduct::factory()->count(5)->make();

                // Calculate subtotal and set other values before saving
                $salesProducts->each(function ($salesProduct) use ($products) {
                    $product = $products->random();
                    $salesProduct->product_id = $product->id;
                    $salesProduct->price = $product->price;
                    $salesProduct->quantity = rand(1, 5);
                    $salesProduct->subtotal = $salesProduct->quantity * $product->price; // Calculate subtotal before save
                });

                // Save the salesProducts with the subtotal field set
                $sale->salesProducts()->saveMany($salesProducts);

                // Calculate total amount and save sale
                $sale->total_amount = $salesProducts->sum('subtotal');
                $sale->invoice_number = $sale->id + 1000; // Ensure invoice number is set correctly
                $sale->save();
            });
        });
    }
}
