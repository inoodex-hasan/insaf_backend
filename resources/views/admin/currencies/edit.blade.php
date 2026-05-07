@extends('admin.layouts.master')

@section('title', 'Edit Currency')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Edit Currency: {{ $currency->name }}</h2>
        <a href="{{ route('admin.currencies.index') }}" class="btn btn-secondary gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                <line x1="19" y1="12" x2="5" y2="12"></line>
                <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
            Back to List
        </a>
    </div>

    <div class="panel mt-6">
        <form action="{{ route('admin.currencies.update', $currency->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">

                <div class="form-group">
                    <label>Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-input" value="{{ old('name', $currency->name) }}" required placeholder="e.g. US Dollar">
                    @error('name')
                        <span class="text-danger text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Code (3 Digits) <span class="text-danger">*</span></label>
                    <input type="text" name="code" class="form-input" value="{{ old('code', $currency->code) }}" required placeholder="e.g. USD" maxlength="3">
                    @error('code')
                        <span class="text-danger text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Symbol <span class="text-danger">*</span></label>
                    <input type="text" name="symbol" class="form-input" value="{{ old('symbol', $currency->symbol) }}" required placeholder="e.g. $">
                    @error('symbol')
                        <span class="text-danger text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Exchange Rate <span class="text-danger">*</span></label>
                    <input type="number" step="0.00000001" name="exchange_rate" class="form-input" value="{{ old('exchange_rate', $currency->exchange_rate) }}" required>
                    @error('exchange_rate')
                        <span class="text-danger text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Status <span class="text-danger">*</span></label>
                    <select name="is_active" class="form-select" required>
                        <option value="1" {{ old('is_active', $currency->is_active) == 1 ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ old('is_active', $currency->is_active) == 0 ? 'selected' : '' }}>Inactive</option>
                    </select>
                    @error('is_active')
                        <span class="text-danger text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- <div class="form-group">
                    <label>Is Default?</label>
                    <select name="is_default" class="form-select">
                        <option value="0" {{ old('is_default', $currency->is_default) == 0 ? 'selected' : '' }}>No</option>
                        <option value="1" {{ old('is_default', $currency->is_default) == 1 ? 'selected' : '' }}>Yes</option>
                    </select>
                    @error('is_default')
                        <span class="text-danger text-sm">{{ $message }}</span>
                    @enderror
                </div> -->

            </div>

            <div class="mt-8 flex justify-end gap-4">
                <button type="reset" class="btn btn-outline-danger">Reset</button>
                <button type="submit" class="btn btn-primary px-10">Update Currency</button>
            </div>
        </form>
    </div>
@endsection
