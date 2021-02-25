@extends('layouts.app')
@section('page-title', 'Checkout Page')

@section('script')
    <script src="{{ asset('js/payment.js') }}" defer></script>
    <script src="http://code.jquery.com/jquery-3.2.1.min.js" crossorigin="anonymous"></script>
    <script src="https://js.braintreegateway.com/web/dropin/1.26.1/js/dropin.min.js"></script>
@endsection

@section('content')
    <div id="app">
        <div class="container mt-5">
            <div class="row">
                {{-- Sezione form Cliente --}}
                <div class="col-5 pt-4 pb-4 border rounded">
                    <h2>Inserisci qui i tuoi dati</h2>
                    <form action="" method="post">
                        @csrf
                        {{-- Nome Cliente --}}
                        <div class="form-group">
                            <label>Nome</label>
                            <input type="text" name="customer_name" class="form-control" placeholder="Inserisci il tuo Nome" value="{{old('customer_name')}}" required>
                        </div>
                        {{-- Cognome Cliente --}}
                        <div class="form-group">
                            <label>Cognome</label>
                            <input type="text" name="customer_surname" class="form-control" placeholder="Inserisci il tuo Cognome" value="{{old('customer_surname')}}" required>
                        </div>
                        {{-- e-mail Cliente --}}
                        <div class="form-group">
                            <label>E-mail</label>
                            <input type="text" name="customer_email" class="form-control" placeholder="Inserisci la tua e-mail" value="{{old('customer_email')}}" required>
                        </div>
                        {{-- Indirizzo di consegna --}}
                        <div class="form-group">
                            <label>Indirizzo di consegna</label>
                            <input type="text" name="delivery_address" class="form-control" placeholder="Inserisci l'indirizzo di consegna" value="{{old('delivery_address')}}" required>
                        </div>
                        {{-- <div class="form-group">
                        <button type="submit" class="btn btn-success">Crea Piatto</button>
                    </div> --}}
                </form>
            </div>

            {{-- Sezione riepilogo Ordine --}}
            <div class="col-5 offset-2 pb-4">
                <div class="card">

                    <div class="card-header">
                        <h2>
                            Riepilogo Ordine
                        </h2>
                    </div>

                    {{-- Carrello --}}
                    <div class="card-body">

                        <h5>Ristorante:</h5>
                        <p>
                            {{$restaurant->name}} - #<span  id="restaurant-id">{{$restaurant->id}}</span>
                        </p>

                        {{-- Lista elementi del carrello --}}
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between align-items-center border-0 px-0 pb-0" v-for="cartItem in cart.contents">
                                @{{ cartItem.name }}
                                <a class="btn btn-sm btn-primary" @click="decrease(cartItem.id)">-</a>
                                <span>x@{{ cartItem.quantity }}</span>
                                <a class="btn btn-sm btn-primary" @click="add(cartItem)">+</a>
                                <span>@{{ cartItem.unit_price }} €</span>
                            </li>
                            {{-- Totale Carrello --}}
                            <li class="list-group-item d-flex justify-content-between align-items-center border-0 px-0 mb-3">
                                <div>
                                    <strong>Totale</strong>
                                    <strong>
                                        <p class="mb-0">(IVA inclusa)</p>
                                    </strong>
                                </div>
                                <span><strong>@{{ cart.subtotal }} €</strong></span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        {{-- Sezione di pagamento --}}
        <div class="container mt-5 mb-5 ">
            <div class="row">
                {{-- Braintree --}}
                <div class="col-12 shadow">

                    <div class="wrapper">
                        <div class="checkout container">

                            <form method="post" id="payment-form" action="{{route("guest.pay")}}">
                                @csrf
                                <section>
                                    <label for="amount">
                                        <span class="input-label">Amount</span>
                                        <div class="input-wrapper amount-wrapper">
                                            <input id="amount" name="amount" type="tel" min="1" placeholder="Amount" :value="cart.subtotal">
                                        </div>
                                    </label>

                                    <div class="bt-drop-in-wrapper">
                                        <div id="bt-dropin"></div>
                                    </div>
                                </section>

                                <span id="token" hidden>{{$token}}</span>
                                <input id="nonce" name="payment_method_nonce" type="hidden" />
                                <button class="button" type="submit">
                                    <span>Paga e ordina</span>
                                </button>
                            </form>

                        </div>
                    </div>


                </div>
            </div>
        </div>

        <footer>
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="footer-container-top">

                            <div class="card" style="width: 15rem;">
                                <div class="card-body">
                                    <h5 class="card-title">Scopri Deliveboo</h5>
                                    <h6 class="card-subtitle mb-2 text-muted">Chi siamo</h6>
                                    <h6 class="card-subtitle mb-2 text-muted">Pressroom</h6>
                                    <h6 class="card-subtitle mb-2 text-muted">Il nostro blog</h6>
                                    <h6 class="card-subtitle mb-2 text-muted">Programmazione</h6>
                                    <h6 class="card-subtitle mb-2 text-muted">Lavora con noi</h6>
                                </div>
                            </div>

                            <div class="card" style="width: 15rem;">
                                <div class="card-body">
                                    <h5 class="card-title">Note legali</h5>
                                    <h6 class="card-subtitle mb-2 text-muted">Termini e condizioni</h6>
                                    <h6 class="card-subtitle mb-2 text-muted">Informativa sulla privacy</h6>
                                    <h6 class="card-subtitle mb-2 text-muted">Cookies</h6>
                                </div>
                            </div>

                            <div class="card" style="width: 15rem;">
                                <div class="card-body">
                                    <h5 class="card-title">Aiuto</h5>
                                    <h6 class="card-subtitle mb-2 text-muted">Contatti</h6>
                                    <h6 class="card-subtitle mb-2 text-muted">FAQ</h6>
                                    <h6 class="card-subtitle mb-2 text-muted">Tipi di cucina</h6>
                                    <h6 class="card-subtitle mb-2 text-muted">Mappa del sito</h6>

                                </div>
                            </div>

                            <div class="card" style="width: 15rem;">
                                <div class="card-body">
                                    <h5 class="card-title">Scarica l'App</h5>


                                </div>
                            </div>

                        </div>

                        <div class="footer-container-bottom">
                            <small>© 2021 Deliveboo</small>
                            <div class="links">
                                <a href=""><i class="fab fa-twitter"></i></a>
                                <a href=""><i class="fab fa-facebook"></i></a>
                                <a href=""><i class="fab fa-instagram"></i></a>

                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </footer>


    </div>
</div>

{{-- Script braintree --}}


@endsection
