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
                    <div id="pdf-preview-status" class="mb-4 text-sm text-white-dark">Loading PDF preview...</div>
                    <div id="pdf-preview-pages" class="space-y-6"></div>
                    <div id="pdf-preview-fallback" class="hidden rounded-lg bg-white p-6 text-center text-black">
                        <p class="text-base font-semibold">PDF preview could not be rendered.</p>
                        <p class="mt-2 text-sm text-slate-600">You can still open or download the file below.</p>
                        <div class="mt-4 flex items-center justify-center gap-3">
                            <a href="{{ $fileUrl }}" target="_blank" class="btn btn-outline-primary">Open PDF</a>
                            <a href="{{ $downloadUrl }}" class="btn btn-primary">Download</a>
                        </div>
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

@if ($extension === 'pdf')
    @push('scripts')
        <script type="module">
            import * as pdfjsLib from "{{ asset('vendor/pdfjs/pdf.min.js') }}";

            pdfjsLib.GlobalWorkerOptions.workerSrc = "{{ asset('vendor/pdfjs/pdf.worker.min.js') }}";

            const statusEl = document.getElementById('pdf-preview-status');
            const pagesEl = document.getElementById('pdf-preview-pages');
            const fallbackEl = document.getElementById('pdf-preview-fallback');
            const pdfUrl = @json($fileUrl);

            const showFallback = (message) => {
                if (statusEl) {
                    statusEl.textContent = message;
                }

                fallbackEl?.classList.remove('hidden');
            };

            try {
                const loadingTask = pdfjsLib.getDocument(pdfUrl);
                const pdf = await loadingTask.promise;

                if (statusEl) {
                    statusEl.textContent = `Loaded ${pdf.numPages} page(s).`;
                }

                for (let pageNumber = 1; pageNumber <= pdf.numPages; pageNumber++) {
                    const page = await pdf.getPage(pageNumber);
                    const viewport = page.getViewport({ scale: 1.35 });
                    const canvas = document.createElement('canvas');
                    const context = canvas.getContext('2d');

                    canvas.width = viewport.width;
                    canvas.height = viewport.height;
                    canvas.className = 'w-full rounded-lg bg-white shadow';

                    const pageWrapper = document.createElement('div');
                    pageWrapper.className = 'space-y-2';

                    const pageLabel = document.createElement('p');
                    pageLabel.className = 'text-xs font-semibold uppercase tracking-wide text-white-dark';
                    pageLabel.textContent = `Page ${pageNumber}`;

                    pageWrapper.appendChild(pageLabel);
                    pageWrapper.appendChild(canvas);
                    pagesEl?.appendChild(pageWrapper);

                    await page.render({
                        canvasContext: context,
                        viewport,
                    }).promise;
                }
            } catch (error) {
                console.error('PDF preview failed', error);
                showFallback('PDF preview is not available right now.');
            }
        </script>
    @endpush
@endif
