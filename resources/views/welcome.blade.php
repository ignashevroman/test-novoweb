@extends('layout.app')

@section('content')
    <div class="d-flex align-items-center justify-content-center" style="height: 100vh">
        <form action="{{ route('orders.create') }}" method="GET" class="form">
            <div class="form-group">
                <input class="form-control @error('url') is-invalid @enderror" name="url" placeholder="Instagram profile URL"
                       required type="text" value="{{ old('url') }}">
                @error('url')
                    <span class="invalid-feedback" role="alert">
                        {{ $message }}
                    </span>
                @enderror
            </div>
        </form>
    </div>
@endsection
