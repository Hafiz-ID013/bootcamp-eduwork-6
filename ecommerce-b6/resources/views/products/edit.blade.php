@extends('template.layout')

@section('title', 'Edit Product')

@section('content')

<h2 class="mb-4">Edit Product</h2>

<form action="{{ route('products.update', $product) }}"
      method="POST"
      enctype="multipart/form-data">

    @csrf
    @method('PUT')

    <div class="mb-3">
        <label class="form-label">Product Name</label>
        <input
            type="text"
            name="name"
            class="form-control"
            value="{{ old('name', $product->name) }}"
            required
        >
    </div>

    <div class="mb-3">
        <label class="form-label">Price</label>
        <input
            type="number"
            name="price"
            step="0.01"
            class="form-control"
            value="{{ old('price', $product->price) }}"
            required
        >
    </div>

    {{-- ✅ CURRENT IMAGE PREVIEW --}}
    @if ($product->image)
        <div class="mb-3">
            <label class="form-label d-block">Current Image</label>
            <img
                src="{{ asset('storage/' . $product->image) }}"
                style="max-width:200px;height:auto;border-radius:8px"
                class="mb-2 border"
                alt="Product image"
            >
        </div>
    @endif

    <div class="mb-3">
        <label class="form-label">Change Image</label>
        <input
            type="file"
            name="image"
            class="form-control"
        >
    </div>

    <div class="mb-3">
        <label class="form-label">Description</label>
        <textarea
            name="description"
            class="form-control"
            rows="4"
        >{{ old('description', $product->description) }}</textarea>
    </div>

    <button class="btn btn-primary">Update</button>
    <a href="{{ route('products.index') }}" class="btn btn-secondary">Back</a>

</form>

@endsection
