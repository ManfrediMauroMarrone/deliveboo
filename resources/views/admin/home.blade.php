@extends('layouts.dashboard')

@section('content')
    <div class="container ">
        <div class="row">
            <div class="col-md-12 d-md-flex justify-content-between flex-wrap">

                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif

                <div class="title my-2">
                    <h1>
                        I miei Ristoranti
                    </h1>
                </div>

                @if(count($restaurants) > 0)
                    @foreach ($restaurants as $restaurant)
                        <div class="card restaurant-dashboard-card w-100 flex-row justify-content-between my-2">
                            <div class="card-body">
                                <h5 class="card-title">{{ $restaurant->name }}</h5>
                                <p class="card-text">Indirizzo: {{ $restaurant->address }}</p>
                                <p class="card-text">P. IVA: {{ $restaurant->piva }}</p>
                                <a class="btn btn-secondary" href="{{ route('admin.restaurants.edit', ['restaurant' => $restaurant->slug]) }}">
                                    Modifica
                                </a>
                            </div>

                            <!-- img di copertirna -->
                            @if($restaurant->img_cover)
                                <div class="restaurant-img-container">
                                    <img class="restaurant-img" src="{{ asset("storage/".$restaurant->img_cover) }}" alt="">
                                </div>
                            @endif
                        </div>
                    @endforeach
                @endif

                {{-- Bottone per creare un nuovo ristorante --}}
                <div>
                    <a class="btn btn-primary mt-3" href="{{ route('admin.restaurants.create') }}">Crea nuovo ristorante</a>
                </div>

            </div>
        </div>
    </div>
@endsection
