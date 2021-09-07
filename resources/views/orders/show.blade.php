@extends('layout.app')

@section('content')
    <div class="d-flex flex-column align-items-center justify-content-center" style="height: 100vh">
        <div class="mb-5 text-center">
            <h1>Заказ</h1>
            <h3>{{ $order->id }}</h3>
        </div>

        <div class="order d-flex align-items-center gap-5">
            <div class="profile d-flex align-items-center gap-3">
                <img src="{{ asset($order->profile->profile_pic_url) }}" class="img-thumbnail" alt="Profile">
                <a href="{{ $order->profile->url }}" target="_blank">{{ $order->profile->full_name ?? $order->profile->username }}</a>
            </div>

            <div>
                {{ $order->quantity_of_completed }} из {{ $order->quantity }}
            </div>

            <div>
                {{ $order->charge }} руб.
            </div>
        </div>
    </div>
@endsection
