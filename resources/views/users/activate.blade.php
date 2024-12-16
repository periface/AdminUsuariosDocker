@extends('layout')
@section('content')
<div class="d-flex vh-100">
    <div class="d-flex align-items-center justify-content-center">
        <div class="text-center col-12 d-flex align-items-center justify-content-center">
            <div class="w-75">
                <h1 class="fs-3">{{ $message }}</h1>
                <div class="mt-4">
                    @if ($ruta !== null)
                        <a href="/">{{ $ruta }}</a>
                    @else
                        <i class="fa-solid fa-link-slash fs-3"></i>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection