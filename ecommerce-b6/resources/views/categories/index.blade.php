@extends('template.layout')

@section('title', 'Categories')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Categories</h2>

    <a href="{{ route('categories.create') }}" class="btn btn-primary">
        + Add Category
    </a>
</div>

<div class="card shadow-sm">
    <div class="card-body p-0">

        <table class="table table-bordered table-striped mb-0">
            <thead class="table-dark">
                <tr>
                    <th width="80">ID</th>
                    <th>Name</th>
                    <th width="180">Total Products</th>
                    <th width="180">Action</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($categories as $category)
                    <tr>
                        <td>{{ $category->id }}</td>

                        <td>{{ $category->name }}</td>

                        <td>
                            {{ $category->products_count }}
                        </td>

                        <td>
                            <a
                                href="{{ route('categories.edit', $category->id) }}"
                                class="btn btn-sm btn-warning"
                            >
                                Edit
                            </a>

                            <form
                                action="{{ route('categories.destroy', $category->id) }}"
                                method="POST"
                                class="d-inline"
                                onsubmit="return confirm('Delete this category?')"
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
                        <td colspan="4" class="text-center py-4">
                            No categories found.
                        </td>
                    </tr>
                @endforelse
            </tbody>

        </table>

    </div>
</div>

@endsection
