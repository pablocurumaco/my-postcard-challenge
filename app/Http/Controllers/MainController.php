<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Http;
 
class MainController extends Controller
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // 
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            // make request for cards
            $response = Http::get('https://appmsds-6aa0.kxcdn.com/content.php?lang=de&json=1&search_text=berlin&currencyiso=EUR');

            $res_decode = json_decode($response->body());
            // only take the first 25
            $content = array_slice($res_decode->content, 0, 25);
            
            // prepare the array that I will pass to the view
            $data = array();
            foreach ($content as $key => $product) {
                // make another request to get the new price
                $response_price = Http::post('https://www.mypostcard.com/mobile/product_prices.php?json=1&type=get_postcard_products&currencyiso=EUR&store_id=' . $product->id);
                $data_price = json_decode($response_price->body());

                // the envelope price
                $product_options = $data_price->products[0]->product_options;
                $envelope_price = $product_options->Envelope->price;
                // and my new price
                $new_price = $envelope_price + $product->price;

                // get more names for different prices
                $options = array();
                foreach ($product_options as $option) {
                    if ($option->name != 'Greetcard_Envelope') {
                        array_push($options, $option->name);
                    }
                }

                $my_product = array(
                    'id' => $product->id,
                    'thumb_url' => $product->thumb_url,
                    'title' => $product->title,
                    'price_greendcard' => $product->price,
                    'price_envelope' => $envelope_price,
                    'price' => $new_price,
                    'pricing_options' => $options,
                );
                array_push($data, $my_product);
            }

        } catch (\Throwable $th) {
            dd($th);
        }
        return view('main-table', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    public function getPrice(Request $request)
    {
        try {
            $id = $request->get('id');
            $price_name = $request->get('price_name');

            $response_price = Http::post('https://www.mypostcard.com/mobile/product_prices.php?json=1&type=get_postcard_products&currencyiso=EUR&store_id=' . $id);
            $data_price = json_decode($response_price->body());

            $product_options = $data_price->products[0]->product_options;
            $envelope_price = $product_options->Envelope->price;

            $my_new_price = 0;
            foreach ($product_options as $option) {
                if ($option->name == $price_name) {
                    $my_new_price = $option->price;
                }
            }

            $my_new_price += $envelope_price;

        } catch (\Throwable $th) {
            throw $th;
        }

        return json_encode([
            'new_price' => '€' . number_format($my_new_price, 2)
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
