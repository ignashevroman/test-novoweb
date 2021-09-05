@extends('layout.app')

@section('content')
    <div class="d-flex align-items-center justify-content-center" style="height: 100vh">
        <form action="{{ route('orders.create') }}" method="GET" class="form">
            <div class="form-group">
                <input class="form-control @error('url') is-invalid @enderror" name="url" placeholder="Instagram profile URL"
                       required type="text">
                @error('url')
                    <div class="invalid-feedback" role="alert">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </form>
    </div>
@endsection
