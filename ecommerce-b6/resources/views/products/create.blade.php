@extends('template.layout')

@section('title', 'Add Product')

@section('content')

<h2 class="mb-4">Add Product</h2>

<form action="{{ route('products.store') }}"
      method="POST"
      enctype="multipart/form-data">

    @csrf

    {{-- Category --}}
    <div class="mb-3">
        <label class="form-label">Category</label>
        <select name="category_id" class="form-select" required>
            <option value="">Select Category</option>

            @foreach ($categories as $category)
                <option value="{{ $category->id }}">
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
    </div>

    {{-- Name --}}
    <div class="mb-3">
        <label class="form-label">Product Name</label>
        <input type="text" name="name" class="form-control" required>
    </div>

    {{-- Price --}}
    <div class="mb-3">
        <label class="form-label">Price</label>
        <input type="number" name="price" class="form-control" required>
    </div>

    {{-- Stock --}}
    <div class="mb-3">
        <label class="form-label">Stock</label>
        <input type="number" name="stock" class="form-control" min="0" required>
    </div>

    {{-- Image --}}
    <div class="mb-3">
        <label class="form-label">Product Image</label>
        <input type="file" name="image" class="form-control">
    </div>

    {{-- Description --}}
    <div class="mb-3">
        <label class="form-label">Description</label>
        <textarea name="description" class="form-control"></textarea>
    </div>

    <button class="btn btn-primary">Save</button>
    <a href="{{ route('products.index') }}" class="btn btn-secondary">Back</a>

</form>

@endsection
