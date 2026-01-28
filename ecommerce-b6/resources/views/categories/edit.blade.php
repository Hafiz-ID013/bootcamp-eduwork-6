@extends('template.layout')

@section('title', 'Edit Category')

@section('content')

<h2 class="mb-4">Edit Category</h2>

<form action="{{ route('categories.update', $category->id) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="mb-3">
        <label class="form-label">Category Name</label>
        <input
            type="text"
            name="name"
            class="form-control"
            value="{{ $category->name }}"
            required
        >
    </div>

    <button class="btn btn-primary">
        Update
    </button>

    <a href="{{ route('categories.index') }}" class="btn btn-secondary">
        Back
    </a>

</form>

@endsection
