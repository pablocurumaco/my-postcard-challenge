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
        Blade::directive('datetime', function ($expression) {
            return "<?php echo ($expression)->format('m/d/Y H:i'); ?>";
        });
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
                $envelope_price = $data_price->products[0]->product_options->Envelope->price;
                // and my new price
                $new_price = $envelope_price + $product->price;

                $my_product = array(
                    'thumb_url' => $product->thumb_url,
                    'title' => $product->title,
                    'price_greendcard' => $product->price,
                    'price_envelope' => $envelope_price,
                    'price' => $new_price,
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
