<?php

namespace App\Http\Controllers\Apps;

use App\Models\Cart;
use Inertia\Inertia;
use App\Models\Product;
use App\Models\Customer;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Transactions;
use Exception;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function index()
    {
        $carts = Cart::with('product')->where('cashier_id',
        auth()->user()->id)->latest()->get();

        $customers = Customer::latest()->get();

        //return inertia
        return Inertia::render('Apps/Transactions/Index',
        [
            'carts' => $carts,
            'carts_total' => $carts->sum('price'),
            'customers' => $customers
        ]);
    }

    public function searchProduct(Request $request)
    {
        //find product by barcode
        $product = Product::where('barcode', $request->barcode)
        ->first();

        if($product)
        {
            return response()->json([
                'success' => true,
                'data' => $product
            ]);
        }

        return response()->json([
            'success' => false,
            'data' => null
        ]);
    }

    //addToCart
    public function addToCart(Request $request)
    {
        //cek stok produk
        $product_stocks = Product::whereId($request->product_id)->first()->stock;

        if($product_stocks < $request->qty)
        {
            return redirect()->back()->with('error', 'Stok Produk Habis!');
        }

        $cart = Cart::with('product')
        ->where('product_id', $request->product_id)
        ->where('cashier_id', auth()->user()->id)
        ->first();
        /**
         * cek cart, jika sudah ada
         */
        if($cart)
        {
            $cart->increment('qty', $request->qty);

            $cart->price = $cart->product->sell_price * $cart->qty;

            $cart->save();
        }
        else{
            //insert cart
            Cart::create([
                'cashier_id' => auth()->user()->id,
                'product_id' => $request->product_id,
                'qty' => $request->qty,
                'price' => $request->sell_price * $request->qty
            ]);
        }

        return redirect()->route('apps.transaction.index')
        ->with('success', 'Product Added Succesfully');
    }

    public function destroyCart(Request $request)
    {
        //find cart by id
        $cart = Cart::with('product')
        ->whereId($request->cart_id)
        ->first();

        $cart->delete();

        return redirect()->back()->with('success', 'Product Removed Successfully');
    }

    public function store(Request $request)
    {
        /**
         * Generate no invoice
         */
        $length = 10;
        $random = '';
        for($i=0; $i < $length; $i++)
        {
            $random .= rand(0,1) ? rand(0,9) : chr(rand(ord('a'), ord('z')));
        }

        $invoice = 'TRX-'.Str::upper($random);
        // DB::beginTransaction();

        // try {
            # code...
            //insert transaction
            $transaction = Transactions::create([
                'cashier_id' => auth()->user()->id,
                'customer_id' => $request->customer_id,
                'invoice' => $invoice,
                'cash' => $request->cash,
                'change' => $request->change,
                'discount' => $request->discount,
                'grand_total' => $request->grand_total
            ]);

            $carts = Cart::where('cashier_id', auth()->user()->id)->get();

            //insert transaction detail
            foreach($carts as $cart)
            {
                $transaction->details()->create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $cart->product_id,
                    'qty' => $cart->qty,
                    'price' => $cart->price
                ]);

                //get price
                $total_buy_price = $cart->product->buy_price * $cart->qty;
                $total_sell_price = $cart->product->sell_price * $cart->qty;

                //get profits
                $profits = $total_sell_price - $total_buy_price;

                //insert to profits
                $transaction->profits()->create([
                    'transaction_id' => $transaction->id,
                    'total' => $profits
                ]);

                //update stock product
                $product = Product::find($cart->product_id);
                $product->stock = $product->stock - $cart->qty;
                $product->save();
            }
            // DB::commit();
        // } catch (Exception $e) {
        //     # code...
        //     DB::rollback();
        //     return redirect()->back()->with('error', 'Terjadi Kesalahan!');
        // }

        //delete cart
        Cart::where('cashier_id', auth()->user()->id)->delete();
        // dd("Cek");

        return response()->json([
            'success' => true,
            'data' => $transaction
        ]);
    }


    /**
     * print function to nota
     */
    public function print(Request $request)
    {
        $transaction = Transactions::with(['details.product', 'cashier', 'customer'])
        ->where('invoice', $request->invoice)->firstOrFail();
        // dd($transaction);

        return view('print.nota', compact('transaction'));
    }
}
