@extends('admin.layouts.master')

@section('title', 'Document Preview')

@section('content')
    <div class="space-y-6">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-semibold uppercase">Document Preview</h2>
                <p class="text-sm text-white-dark break-all">{{ $name }}</p>
            </div>
            <a href="{{ url()->previous() }}" class="btn btn-secondary">Back</a>
        </div>

        <div class="panel">
            @if (in_array($extension, ['jpg', 'jpeg', 'png']))
                <div class="rounded-lg bg-black/10 p-4">
                    <img src="{{ $fileUrl }}" alt="{{ $name }}" class="max-h-[75vh] w-full object-contain">
                </div>
            @elseif ($extension === 'pdf')
                <div class="rounded-lg bg-black/10 p-4">
                    <div class="overflow-hidden rounded-lg border border-slate-300 bg-white">
                        <iframe
                            src="{{ $fileUrl }}"
                            class="w-full h-[75vh] border-0"
                            title="{{ $name }}"
                        ></iframe>
                    </div>
                    <div class="mt-4 text-sm text-white-dark">If the PDF does not render above, open or download it below.</div>
                    <div class="mt-4 flex items-center justify-center gap-3">
                        <a href="{{ $fileUrl }}" target="_blank" class="btn btn-outline-primary">Open PDF</a>
                        <a href="{{ $downloadUrl }}" class="btn btn-primary">Download</a>
                    </div>
                </div>
            @else
                <div class="rounded-lg bg-black/10 p-6 text-center">
                    <p class="text-base font-semibold">Inline preview is not available for this file type.</p>
                    <p class="mt-2 text-sm text-white-dark">Supported inline preview: PDF, JPG, JPEG, PNG.</p>
                    <div class="mt-4 flex items-center justify-center gap-3">
                        <a href="{{ $fileUrl }}" target="_blank" class="btn btn-outline-primary">Open File</a>
                        <a href="{{ $downloadUrl }}" class="btn btn-primary">Download</a>
                    </div>
                </div>
            @endif

            <div class="mt-4 flex justify-end">
                <a href="{{ $downloadUrl }}" class="btn btn-outline-primary">
                    Download
                </a>
            </div>
        </div>
    </div>
@endsection
