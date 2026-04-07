@extends('admin.layouts.master')

@section('title', 'Edit Campaign: ' . $campaign->name)

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Edit Marketing Campaign</h2>
        <a href="{{ route('admin.marketing.campaigns.index') }}" class="btn btn-outline-primary gap-2 text-xs">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M19 12H5M12 19l-7-7 7-7" />
            </svg>
            Back to List
        </a>
    </div>

    <div class="panel mt-6">
        <form action="{{ route('admin.marketing.campaigns.update', $campaign->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label for="name" class="text-sm font-bold">Campaign Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" id="name" value="{{ old('name', $campaign->name) }}" class="form-input"
                        required />
                    @error('name')<span class="text-danger text-xs italic">{{ $message }}</span>@enderror
                </div>
                <div>
                    <label for="boosting_status" class="text-sm font-bold">Boosting Status</label>
                    <select name="boosting_status" id="boosting_status" class="form-select">
                        <option value="off" {{ old('boosting_status', $campaign->boosting_status) == 'off' ? 'selected' : '' }}>OFF</option>
                        <option value="on" {{ old('boosting_status', $campaign->boosting_status) == 'on' ? 'selected' : '' }}>
                            ON</option>
                    </select>
                    @error('boosting_status')<span class="text-danger text-xs italic">{{ $message }}</span>@enderror
                </div>
                <div class="md:col-span-2">
                    <label for="notes" class="text-sm font-bold">Strategy / Notes</label>
                    <textarea name="notes" id="notes" rows="4"
                        class="form-textarea">{{ old('notes', $campaign->notes) }}</textarea>
                    @error('notes')<span class="text-danger text-xs italic">{{ $message }}</span>@enderror
                </div>
            </div>

            <div class="mt-8 flex items-center justify-end gap-4 border-t border-[#ebedf2] pt-6 dark:border-[#1b2e4b]">
                <button type="submit" class="btn btn-primary px-10">Update Campaign</button>
            </div>
        </form>
    </div>
@endsection