<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Mail;
use App\Product;
class MailController extends Controller
{
    public function sendmail()
    {
        
        return view('frontend/email/email_template');
    }
    public function post_sendmail(Request $request)
    {
        $getRequest = $request->all();
// $cart = session ()->get('cart');
        $idProduct = session()->get ('carts_session');
        foreach ($idProduct as $key => $value) {
        $product [] = Product::find ($idProduct[$key]['product_id'])->toArray();
        $product[$key]['qty'] = $idProduct[$key]['product_qty'];
        }
        $sum =0; 
        foreach ($product as $key => $value) {
        $sum = $sum+$value['price']*$value['qty'];
        }
        $emailTo = $getRequest ["email"];
        $subject = "Mail order product";
        $name = $request->name;
        echo $name;
        Mail::send('frontend.email.email_template',
        array(
                'product'=>$product,
                'sum' => $sum
        ),
        function ($message) use ($subject, $emailTo,$name){
                $message->from('dongvutiennghia@gmail.com', 'Mail order product : '.$name);
                $message->to($emailTo);
                $message->subject($subject);
        });
        session()->forget('carts_session');
        session()->forget('total_item');

        // return view('frontend.cart.cart');
        // $request = $request->all();
    //     $email = $request->email;
    //     $subject = "asdasd";
    //     Mail::send('frontend.email.email_template',[
    //         'name' => $request->name,
    //         'price' => $request->price
    //     ], function($mail) use ($subject,$email){
    //         $mail->to('dongvutiennghia@gmail.com','mail order product');
    //         $mail->from($email);
    //         $mail->subject($subject);
    //     }
    // );
        // return view('frontend/email/');
    }
}
