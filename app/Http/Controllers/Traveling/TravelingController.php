<?php

namespace App\Http\Controllers\Traveling;
use App\Models\City\City;
use App\Models\Country\Country;
use App\Models\Reservation\Reservation;

use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Http\Request;
use Redirect;
use Session;

class TravelingController extends Controller
{
    //
    public function about($id)
    {
        $cities = City::select()->orderBy('id','asc')->take(5)
        ->where('country_id',$id)->get();

        $country = Country::find($id);

        $citiesCount = City::select()->where('country_id',$id)->count();

        return view('traveling.about',compact('cities','country','citiesCount'));
    }

    public function makeReservation($id)
    {
       
        $city = City::find($id);


        return view('traveling.reservation',compact('city'));
    }
    public function storeReservation(Request $request, $id)
    {
        $city = City::find($id);

        if($request->check_in_date > date("Y-m-d"))
        {
            $total_price = (int)$city->price * (int)$request->num_guests;
            $storeReservation = Reservation::create([
                "name" => $request->name,
                "phone_number" => $request->phone_number,
                "num_guests" => $request->num_guests,
                "check_in_date" => $request->check_in_date,
                "destination" => $request->destination,
                "price" => $total_price,
                "user_id" => $request->user_id,
            ]);
            if($storeReservation)
            {
                $price = Session::put('price',$city->price * $request->num_guests);
                $newPrice = Session::get($price);
                return Redirect::route('traveling.pay');
            }else
            {
                echo "reservation is not made!";
            }
    
        }else{
            echo "Please select date from future!";
        }

       
        

       // return view('traveling.reservation',compact('city'));
    }
    public function payWithPaypal()
    {
        return view('traveling.pay');
    }
    public function success()
    {
        Session::forget('price');
        return view('traveling.success');
    }
    
}
