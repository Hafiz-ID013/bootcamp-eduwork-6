@extends('template.layout')

@section('title', 'Products')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Products</h2>

    <a href="{{ route('products.create') }}" class="btn btn-primary">
        Add Product
    </a>
</div>

<div class="row g-4">

@foreach ($products as $product)
    <div class="col-md-4">

        <div class="card h-100 shadow-sm d-flex flex-column">

            {{-- IMAGE --}}
            <div class="d-flex align-items-center justify-content-center bg-white"
                 style="height:240px; overflow:hidden;">

                @if ($product->image && file_exists(public_path('storage/' . $product->image)))
                    <img
                        src="{{ asset('storage/' . $product->image) }}"
                        style="max-height:100%; max-width:100%; object-fit:contain;"
                        alt="{{ $product->name }}"
                    >
                @else
                    <span class="text-muted">No Image</span>
                @endif

            </div>

            {{-- BODY --}}
            <div class="card-body d-flex flex-column">

                <h5 class="card-title mb-1">
                    {{ $product->name }}
                </h5>

                <small class="text-muted mb-2">
                    Category: {{ $product->category->name ?? 'Uncategorized' }}
                </small>

                <p class="fw-semibold mb-1">
                    Rp {{ number_format($product->price, 0, ',', '.') }}
                </p>

                <p class="text-muted small mb-3">
                    {{ $product->description }}
                </p>

                <div class="mt-auto d-flex gap-2">
                    <a href="{{ route('products.edit', $product) }}"
                       class="btn btn-sm btn-outline-primary">
                        Edit
                    </a>

                    <form action="{{ route('products.destroy', $product) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger">
                            Delete
                        </button>
                    </form>
                </div>

            </div>

        </div>

    </div>
@endforeach

</div>

<div class="mt-4 d-flex justify-content-center">
    {{ $products->links() }}
</div>

@endsection
