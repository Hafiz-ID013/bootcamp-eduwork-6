@extends('template.layout')

@section('title', $product->name)

@section('content')

<div class="row">

    <div class="col-md-5">
        <img src="{{ asset('storage/'.$product->image) }}"
             class="img-fluid rounded shadow">
    </div>

    <div class="col-md-7">
        <h2>{{ $product->name }}</h2>

        <h4 class="text-success mb-3">
            Rp {{ number_format($product->price, 0, ',', '.') }}
        </h4>


        <p>{{ $product->description }}</p>

        <p class="text-muted">
            Category: {{ $product->category->name ?? '-' }}
        </p>

        <p class="small">
            👁 Viewed {{ $product->click_count }} times
        </p>

        <form action="{{ route('cart.add') }}" method="POST">
            @csrf

            <input type="hidden" name="product_id" value="{{ $product->id }}">

            <div class="mb-3">
                <label>Quantity</label>
                <input
                    type="number"
                    name="quantity"
                    value="1"
                    min="1"
                    class="form-control"
                    style="width:120px"
                >
            </div>

            <button class="btn btn-primary">
                Add to Cart
            </button>
        </form>


        <a href="{{ route('home') }}" class="btn btn-secondary mt-3">
            Back
        </a>
    </div>

</div>

@endsection
