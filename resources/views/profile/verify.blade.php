@extends('layouts.template')

@section('header')
<h1 class="h3 mb-0 text-gray-800">Verify Mobile</h1>
@endsection

@section('content')
<div class="card">
    <div class="card-body">

        @php($method = 'POST')

        <form method="POST">
            <input type="hidden" name="_method" value="{{ $method }}">
            @csrf

            @if(isset($_GET['action']) && $_GET['action'] == 'code')

            <div class="form-group">
                <label>Please enter code</label>
                <input type="text" name="code" id="code" class="form-control bg-light" 
                value="{{ old('code') }}">
                @error('code')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            @else

            <div class="form-group">
                <label>Phone</label>
                <input type="text" name="phone" id="phone" class="form-control bg-light" 
                value="{{ old('phone', $user->phone) }}">
                @error('phone')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            @endif

            <div class="form-group">
                <button type="reset" class="btn btn-info">
                    Reset
                </button>
                <button type="submit" class="btn btn-primary">
                    Submit
                </button>
            </div>

        </form>

    </div>
</div>
@endsection