@extends('layout')
@section('content')
<div class="d-flex vh-100">
    <div class="d-flex align-items-center justify-content-center">
        <div class="text-center col-12 d-flex align-items-center justify-content-center">
            <div class="w-75 text-secondary">
                <h1 class="fs-3">{{ $message }}</h1>
                <div class="mt-5">
                    @if ($ruta !== null)
                        <a href="/" class="fs-3">
                            <i class="fa-solid fa-right-to-bracket"></i> {{ $ruta }}
                        </a>
                    @else
                        <i class="fa-solid fa-link-slash fs-1"></i>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection