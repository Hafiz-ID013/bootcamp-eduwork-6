@extends('template.layout')

@section('title', 'Products')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Products</h2>

    <a href="{{ route('products.create') }}" class="btn btn-primary">
        + Add Product
    </a>
</div>

<div class="card shadow-sm">
    <div class="card-body p-0">

        <table class="table table-bordered table-hover align-middle text-center mb-0">
            <thead class="table-dark">
                <tr>
                    <th width="50">ID</th>
                    <th width="110">Image</th>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Description</th>
                    <th width="70">Stock</th>
                    <th width="120">Price</th>
                    <th width="80">Clicks</th>
                    <th width="200">Action</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($products as $product)
                    <tr>
                        <td>{{ $product->id }}</td>

                        <td>
                            @if ($product->image)
                                <img
                                    src="{{ asset('storage/'.$product->image) }}"
                                    class="img-fluid rounded"
                                    style="height:70px; width:100px; object-fit:contain;"
                                >
                            @else
                                —
                            @endif
                        </td>

                        <td class="fw-semibold">
                            {{ $product->name }}
                        </td>

                        <td>
                            {{ $product->category->name ?? '-' }}
                        </td>

                        <td class="text-start">
                            {{ Str::limit($product->description, 50) }}
                        </td>

                        <td>
                            {{ $product->stock }}
                        </td>

                        <td>
                            Rp {{ number_format($product->price, 0, ',', '.') }}
                        </td>


                        <td>
                            <span class="badge bg-info">
                                {{ $product->click_count }}
                            </span>
                        </td>

                        <td class="text-nowrap">

                            <a
                                href="{{ route('products.show', $product->id) }}"
                                class="btn btn-sm btn-info text-white"
                            >
                                View
                            </a>

                            <a
                                href="{{ route('products.edit', $product->id) }}"
                                class="btn btn-sm btn-warning"
                            >
                                Edit
                            </a>

                            <form
                                action="{{ route('products.destroy', $product->id) }}"
                                method="POST"
                                class="d-inline"
                                onsubmit="return confirm('Delete this product?')"
                            >
                                @csrf
                                @method('DELETE')

                                <button class="btn btn-sm btn-danger">
                                    Delete
                                </button>
                            </form>

                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="py-4">
                            No products found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

    </div>

    <div class="card-footer bg-white">
        {{ $products->links() }}
    </div>
</div>

@endsection
