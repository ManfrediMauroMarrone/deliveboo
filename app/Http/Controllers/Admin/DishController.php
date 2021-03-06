<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Dish;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Restaurant;
use Illuminate\Support\Str;

class DishController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user_id = Auth::user()->id;
        $restaurants = Restaurant::where('user_id', $user_id)->get();

        // $dishes = Dish::where('restaurant_id', $restaurant->id)->get();
        $data = [
            // 'dishes' => $dishes
            'restaurants' => $restaurants
        ];
        return view('admin.dishes.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.dishes.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request -> validate([
            'name' => 'required|max:30',
            'restaurant_id' =>'required|exists:restaurants,id',
            'ingredients' => 'nullable|string|max:1000',
            'description' => 'nullable|string|max:1000',
            'unit_price' => 'required|numeric|between:00.01,999.99',
            'img_file' => 'nullable|mimes:jpg,jpeg,png,bmp|max:512',
            'visible' => 'required|boolean'
        ]);

        // Verifica id utente che invia richiesta di salvataggio piatto
        $user_id = Auth::user()->id;
        $restaurant_id_selected = $request->input("restaurant_id");
        $selected_restaurant_user = Restaurant::where("id",$restaurant_id_selected)->value('user_id');
        if($user_id != $selected_restaurant_user) {
            abort(404);
        }

        $form_data = $request->all();

        if(array_key_exists("img_file", $form_data)){
            $img_path = Storage::put("uploads/dishes_covers", $form_data["img_file"]);
            $form_data["img_cover"] = $img_path;
        }


        $new_dish = new Dish(); // nuovo obj dish

        $new_dish->fill($form_data);

        // dd($new_dish);

        $slug = Str::slug($new_dish->name);
        $slug_base = $slug;

        // verifico che lo slug non esista nel database
        $dish_exist = Dish::where('slug', $slug)->first();
        $contatore = 1;

        // entro nel ciclo while se ho trovato un post con lo stesso $slug
        while($dish_exist) {
            // genero un nuovo slug aggiungendo il contatore alla fine
            $slug = $slug_base . '-' . $contatore;
            $contatore++;
            $dish_exist = Dish::where('slug', $slug)->first();
        }
        // quando esco dal while sono sicuro che lo slug non esiste nel db
        // assegno lo slug al post
        $new_dish->slug = $slug;

        $new_dish->save();

        return redirect()->route('admin.dishes.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        $dish = Dish::where('slug', $slug)->first();
        $user_id = Auth::user()->id;

        if(!$dish) {
            abort(404);
        }
        if($user_id != $dish->restaurant->user->id) {
            // Aggiungere messaggio di errore per modifica piatto di un altro ristoratore
            abort(404);
        }

        $data = [
            'dish' => $dish
        ];
        return view('admin.dishes.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($slug)
    {

        $dish = Dish::where('slug', $slug)->first();
        $user_id = Auth::user()->id;

        if(!$dish) {
            abort(404);
        }
        if($user_id != $dish->restaurant->user->id) {
            // Aggiungere messaggio di errore per modifica piatto di un altro ristoratore
            abort(404);
        }

        $data = [
            'dish' => $dish
        ];
        return view('admin.dishes.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Dish $dish)
    {
        $request -> validate([
            'name' => 'required|max:30',
            'restaurant_id' =>'required|exists:restaurants,id',
            'ingredients' => 'nullable|string|max:1000',
            'description' => 'nullable|string|max:1000',
            'unit_price' => 'required|numeric|between:00.01,999.99',
            'img_file' => 'nullable|mimes:jpg,jpeg,png,bmp|max:512',
            'visible' => 'required|boolean'
        ]);

        $user_id = Auth::user()->id;
        $restaurant_id_selected = $request->input("restaurant_id");
        $selected_restaurant_user = Restaurant::where("id",$restaurant_id_selected)->value('user_id');
        if($user_id != $selected_restaurant_user) {
            abort(404);
        }

        $form_data = $request->all();

        if(array_key_exists("img_file", $form_data)){
            $img_path = Storage::put("uploads/dishes_covers", $form_data["img_file"]);
            $form_data["img_cover"] = $img_path;
        }

        if($form_data["name"] != $dish->name){
            // Ricreo slug
            $slug = Str::slug($form_data["name"]);
            $slug_base = $slug;

            // verifico che lo slug non esista nel database
            $dish_exist = Dish::where('slug', $slug)->first();
            $contatore = 1;

            // entro nel ciclo while se ho trovato un post con lo stesso $slug
            while($dish_exist) {
                // genero un nuovo slug aggiungendo il contatore alla fine
                $slug = $slug_base . '-' . $contatore;
                $contatore++;
                $dish_exist = Dish::where('slug', $slug)->first();
            }
            $dish->slug = $slug;
        }

        $dish->update($form_data);
        return redirect()->route("admin.dishes.index");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Dish $dish)
    {
        $user_id = Auth::user()->id;

        if(!$dish) {
            abort(404);
        }
        if($user_id != $dish->restaurant->user->id) {
            // Aggiungere messaggio di errore per modifica piatto di un altro ristoratore
            abort(404);
        }
        $dish->delete();
        return redirect()->route('admin.dishes.index');
    }
}
