@extends('layout.app')

@section('content')
    <div class="d-flex align-items-center justify-content-center" style="height: 100vh">
        <form action="{{ route('orders.store') }}" method="POST" class="form d-flex flex-column gap-4">
            @csrf
            <!-- Profile block -->
            <div class="profile d-flex align-items-center gap-3">
                <input type="hidden" name="profile_id" value="{{ $profile->id }}">
                <img src="{{ asset($profile->profile_pic_url) }}" class="img-thumbnail" alt="Profile">
                <a href="{{ $profile->url }}" target="_blank">{{ $profile->full_name ?? $profile->username }}</a>
            </div>

            @php
                /** @var $services */
                $defaultService = optional($services)->first();
            @endphp

            <!-- Services radio -->
            <div class="services">
                @foreach($services as $service)
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="service_id"
                               id="service{{ $service->service }}" value="{{ $service->service }}"
                               @if(optional($defaultService)->service === $service->service) checked @endif required>
                        <label class="form-check-label" for="service{{ $service->service }}">
                            {{ $service->name }} ({{ $service->rate }}руб. за 1000)
                        </label>
                    </div>
                @endforeach
            </div>

            <div class="d-flex gap-3 align-items-start">
                <!-- Quantity field -->
                <div class="quantity flex-grow-1">
                    <input type="number" name="quantity" class="form-control @error('quantity') is-invalid @enderror" value="{{ old('quantity', 1000) }}"
                           min="{{ optional($defaultService)->min }}" max="{{ optional($defaultService)->max }}"
                           required>
                    @error('quantity')
                        <span class="invalid-feedback" role="alert">
                            {{ $message }}
                        </span>
                    @enderror
                </div>

                <!-- Submit button -->
                <button type="submit" class="btn btn-dark">Заказать</button>
            </div>
        </form>
    </div>

    <script>
        // Save services on front to be able change min/max for quantity field
        window.services = JSON.parse('{!! json_encode($services->toArray(), JSON_THROW_ON_ERROR) !!}');

        // Quantity field
        const quantity = document.querySelector('.quantity input[type=number]');

        // Update quantity min/max on service change
        document
            .querySelectorAll('.services input[type=radio]')
            .forEach((element) => {
                element.addEventListener('change', (event) => {
                    const id = Number.parseInt(event.target.value, 10),
                        service = _.find(window.services, {service: id});

                    quantity.min = service.min;
                    quantity.max = service.max;
                });
            });
    </script>
@endsection
