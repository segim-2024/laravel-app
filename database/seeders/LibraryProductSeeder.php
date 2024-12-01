<?php

namespace Database\Seeders;

use App\Models\LibraryProduct;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LibraryProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $product = new LibraryProduct();
        $product->name = '라이브러리 10EA';
        $product->price = 10000;
        $product->ticket_provide_qty = 10;
        $product->save();

        $product = new LibraryProduct();
        $product->name = '라이브러리 20EA';
        $product->price = 15000;
        $product->ticket_provide_qty = 20;
        $product->save();

        $product = new LibraryProduct();
        $product->name = '라이브러리 50EA';
        $product->price = 40000;
        $product->ticket_provide_qty = 50;
        $product->save();
    }
}
