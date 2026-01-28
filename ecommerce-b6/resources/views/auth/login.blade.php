@extends('layouts.guest')

@section('content')
    <div class="container d-flex justify-content-center align-items-center min-vh-100">
        <div class="card shadow p-4" style="width:420px">

            <h4 class="text-center mb-4 fw-bold">E-Commerce</h4>

            <form method="POST" action="{{ route('login.store') }}">
                @csrf

                <div class="mb-3">
                    <input type="email" name="email" class="form-control" placeholder="Email" required autofocus>
                </div>

                <div class="mb-3">
                    <input type="password" name="password" class="form-control" placeholder="Password" required>
                </div>

                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" name="remember">
                    <label class="form-check-label">Remember me</label>
                </div>

                <button class="btn btn-dark w-100 mb-3">
                    Log in
                </button>

                <div class="text-center">
                    <a href="{{ route('register') }}">Create account</a>
                </div>
            </form>

        </div>
    </div>
@endsection
