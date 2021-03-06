<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Restaurant;


class HomeController extends Controller
{
    /**
    * Create a new controller instance.
    *
    * @return void
    */
    /*     public function __construct()
    {
    $this->middleware('auth');
} */

/**
* Show the application dashboard.
*
* @return \Illuminate\Contracts\Support\Renderable
*/
public function index()
{
    return view('guest.welcome');
}

public function show($slug)
{
    $restaurant = Restaurant::where('slug', $slug)->first();
    if ($restaurant) {
        $data = [
            'restaurant' => $restaurant
        ];
        return view('guest.show', $data);
    }
    abort(404);
}



}
