@extends('template.layout')

@section('title', 'Home')

@section('content')

<h2 class="mb-4 text-center">Our Products</h2>

<div class="row">

    @forelse ($products as $product)

        <div class="col-md-3 mb-4">
            <div class="card h-100 shadow-sm">

                @if ($product->image)
                    <img
                        src="{{ asset('storage/'.$product->image) }}"
                        class="card-img-top"
                        style="height:200px; object-fit:cover;"
                    >
                @endif

                <div class="card-body d-flex flex-column">

                    <h5 class="card-title">
                        {{ $product->name }}
                    </h5>

                    <p class="card-text text-muted">
                        {{ Str::limit($product->description, 60) }}
                    </p>

                    <h6 class="text-success mb-3">
                        ${{ number_format($product->price, 2) }}
                    </h6>

                    <a
                        href="{{ route('products.show', $product->id) }}"
                        class="btn btn-primary mt-auto w-100"
                    >
                        View Detail
                    </a>

                </div>
            </div>
        </div>

    @empty
        <div class="col-12">
            <div class="alert alert-info text-center">
                No products available.
            </div>
        </div>
    @endforelse

</div>

<div class="mt-4">
    {{ $products->links() }}
</div>

@endsection
