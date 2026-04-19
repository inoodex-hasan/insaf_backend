@extends('admin.layouts.master')

@section('title', 'Add Commission')

@section('content')
    <div class="panel">
        <div class="mb-5 flex items-center justify-between">
            <h5 class="text-lg font-semibold dark:text-white-light">Add Commission</h5>
            <a href="{{ route('admin.commissions.index') }}" class="btn btn-outline-danger">Back</a>
        </div>

        <form action="{{ route('admin.commissions.store') }}" method="POST" class="space-y-4">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                {{-- Application --}}
                <div class="form-group">
                    <label for="application_id">Application <span class="text-red-500">*</span></label>
                    <select name="application_id" id="application_id" class="form-select" required>
                        <option value="">Select Application</option>
                        @foreach(\App\Models\Application::with('student')->latest()->get() as $app)
                            <option value="{{ $app->id }}" {{ old('application_id') == $app->id ? 'selected' : '' }}>
                                {{ $app->application_id }} - {{ $app->student->full_name ?? 'No Student' }}
                            </option>
                        @endforeach
                    </select>
                    @error('application_id')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Employee --}}
                <div class="form-group">
                    <label for="user_id">Commission To <span class="text-red-500">*</span></label>
                    <select name="user_id" id="user_id" class="form-select" required>
                        <option value="">Select Employee</option>
                        @foreach(\App\Models\User::orderBy('name')->get() as $user)
                            <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }} ({{ $user->email }})
                            </option>
                        @endforeach
                    </select>
                    @error('user_id')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Amount --}}
                <div class="form-group">
                    <label for="amount">Commission Amount (BDT) <span class="text-red-500">*</span></label>
                    <input type="number" name="amount" id="amount" step="0.01" min="0"
                        class="form-input" value="{{ old('amount', 0) }}" required />
                    @error('amount')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Status --}}
                <div class="form-group">
                    <label for="status">Status <span class="text-red-500">*</span></label>
                    <select name="status" id="status" class="form-select" required>
                        <option value="pending" {{ old('status', 'pending') === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="paid" {{ old('status') === 'paid' ? 'selected' : '' }}>Paid</option>
                    </select>
                    @error('status')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Notes --}}
                <div class="form-group md:col-span-2">
                    <label for="notes">Notes</label>
                    <textarea name="notes" id="notes" class="form-input" rows="3"
                        placeholder="Optional notes...">{{ old('notes') }}</textarea>
                    @error('notes')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="mt-6 flex justify-end gap-4">
                <a href="{{ route('admin.commissions.index') }}" class="btn btn-outline-danger">Cancel</a>
                <button type="submit" class="btn btn-primary">Save Commission</button>
            </div>
        </form>
    </div>
@endsection
