
@extends('template.layout')

@section('title', 'Add Category')

@section('content')

<h2 class="mb-4">Add Category</h2>

<form action="{{ route('categories.store') }}" method="POST">
    @csrf

    <div class="mb-3">
        <label class="form-label">Category Name</label>
        <input
            type="text"
            name="name"
            class="form-control"
            required
        >
    </div>

    <button class="btn btn-primary">
        Save
    </button>

    <a href="{{ route('categories.index') }}" class="btn btn-secondary">
        Back
    </a>

</form>

@endsection
