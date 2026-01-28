@extends('template.layout')

@section('title', 'Dashboard')

@section('content')

<h2 class="mb-4">Dashboard</h2>

{{-- SUMMARY CARDS --}}
<div class="row mb-4">

    <div class="col-md-3">
        <div class="card shadow-sm">
            <div class="card-body">
                <h6>Products</h6>
                <h3>{{ $totalProducts }}</h3>
                <small>Total products available</small>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card shadow-sm">
            <div class="card-body">
                <h6>Product Clicks</h6>
                <h3 class="text-success">{{ $totalClicks }}</h3>
                <small>Total product clicks</small>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card shadow-sm">
            <div class="card-body">
                <h6>Categories</h6>
                <h3 class="text-warning">{{ $totalCategories }}</h3>
                <small>Total product categories</small>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card shadow-sm">
            <div class="card-body">
                <h6>Users</h6>
                <h3>{{ $totalUsers }}</h3>
                <small>Total registered users</small>
            </div>
        </div>
    </div>

</div>

{{-- CHART --}}
<div class="card mt-4 p-4">
    <h5 class="mb-3">Transactions in Last 7 Days</h5>

    <canvas id="dashboardChart" height="120"></canvas>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
const ctx = document.getElementById('dashboardChart');

new Chart(ctx, {
    type: 'line',
    data: {
        labels: @json($chartLabels),
        datasets: [
            {
                label: 'Transactions',
                data: @json($chartTransactions),
                borderWidth: 3,
                tension: 0.4,
                fill: true
            },
            {
                label: 'Nominal (Rp)',
                data: @json($chartRevenue),
                borderWidth: 3,
                tension: 0.4,
                fill: true
            }
        ]
    }
});
</script>
@endpush



@endsection
