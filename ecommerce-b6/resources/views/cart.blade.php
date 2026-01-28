@extends('template.layout')

@section('title', 'Cart')

@section('content')

<h2 class="mb-4">Shopping Cart</h2>

@if (session('cart') && count($cart) > 0)

<table class="table table-bordered align-middle">
    <thead class="table-dark">
        <tr>
            <th>Product</th>
            <th width="140">Price</th>
            <th width="100">Qty</th>
            <th width="160">Total</th>
            <th width="80"></th>
        </tr>
    </thead>

    <tbody>
        @php $grandTotal = 0; @endphp

        @foreach ($cart as $item)
            @php
                $total = $item['price'] * $item['quantity'];
                $grandTotal += $total;
            @endphp

            <tr>
                <td>{{ $item['name'] }}</td>

                <td>
                    Rp {{ number_format($item['price'], 0, ',', '.') }}
                </td>

                <td>{{ $item['quantity'] }}</td>

                <td>
                    Rp {{ number_format($total, 0, ',', '.') }}
                </td>

                <td>
                    <form
                        action="{{ route('cart.remove', $item['id']) }}"
                        method="POST"
                    >
                        @csrf
                        @method('DELETE')

                        <button class="btn btn-sm btn-danger">
                            ×
                        </button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>

    <tfoot>
        <tr>
            <th colspan="3" class="text-end">Grand Total</th>
            <th colspan="2">
                <strong>
                    Rp {{ number_format($grandTotal, 0, ',', '.') }}
                </strong>
            </th>
        </tr>
    </tfoot>
</table>

@else
    <div class="alert alert-info">
        Your cart is currently empty.
    </div>
@endif

<a href="{{ route('home') }}" class="btn btn-primary mt-3">
    Continue Shopping
</a>

@endsection
