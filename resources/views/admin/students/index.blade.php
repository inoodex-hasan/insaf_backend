@extends('admin.layouts.master')

@section('title', 'Students')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Students</h2>
        @can('*consultant')
            <div class="flex w-full flex-wrap items-center justify-end gap-4 sm:w-auto">
                <a href="{{ route('admin.students.create') }}" class="btn btn-primary gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                        <line x1="12" y1="5" x2="12" y2="19"></line>
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                    Add Student
                </a>
            </div>
        @endcan
    </div>

    <div class="panel mt-6">
        <div class="mb-5 flex flex-col gap-5 md:flex-row md:items-center">
            <form action="{{ route('admin.students.index') }}" method="GET"
                class="flex flex-1 flex-col gap-5 md:flex-row md:items-center w-full">
                <div class="relative w-full md:w-80">
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Search name, phone or email..." class="form-input ltr:pr-11 rtl:pl-11" />
                    <button type="submit"
                        class="absolute inset-y-0 flex items-center hover:text-primary ltr:right-4 rtl:left-4">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="11.5" cy="11.5" r="9.5" stroke="currentColor" stroke-width="1.5" opacity="0.5" />
                            <path d="M18.5 18.5L22 22" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                        </svg>
                    </button>
                </div>
                <div class="flex gap-2">
                    <select name="stage" class="form-select w-full md:w-44 pr-10">
                        <option value="">Stage</option>
                        @foreach (['lead', 'counseling', 'payment', 'application', 'offer', 'visa', 'enrolled'] as $stage)
                            <option value="{{ $stage }}" {{ request('stage') == $stage ? 'selected' : '' }}>
                                {{ ucfirst($stage) }}
                            </option>
                        @endforeach
                    </select>
                    <select name="status" class="form-select w-full md:w-44 pr-10">
                        <option value="">Status</option>
                        @foreach (['lead', 'counseling', 'payment', 'application', 'offer', 'visa', 'enrolled'] as $status)
                            <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                                {{ ucfirst($status) }}
                            </option>
                        @endforeach
                    </select>
                    <button type="submit" class="btn btn-primary">Filter</button>
                    <a href="{{ route('admin.students.index') }}" class="btn btn-outline-danger">Reset</a>
                </div>
            </form>
        </div>

        <div class="datatable">
            <div class="overflow-x-auto">
                <table class="table-hover w-full table-auto">
                    <thead>
                        <tr>
                            <th>Student</th>
                            <th>Father's Name</th>
                            <th>Phone</th>
                            <!-- <th>Stage</th>
                                                                                                    <th>Status</th> -->
                            <th>Address</th>
                            <th>Collected By</th>
                            <!-- <th>Assigned By</th> -->
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($students as $student)
                            <tr>
                                <td>
                                    <div class="font-semibold">{{ $student->first_name }} {{ $student->last_name }}</div>
                                    <div class="text-xs text-white-dark">{{ $student->email ?? 'No Email' }}</div>
                                </td>
                                <td>{{ $student->father_name }}</td>
                                <td>{{ $student->phone }}</td>
                                <!-- <td>
                                                                                                                                    <span class="badge badge-outline-primary capitalize">{{ $student->current_stage }}</span>
                                                                                                                                </td>
                                                                                                                                <td>
                                                                                                                                    <span class="badge badge-outline-primary capitalize">{{ $student->current_status }}</span>
                                                                                                                                </td> -->
                                <td>{{ \Illuminate\Support\Str::limit($student->address, 30) }}</td>
                                <td>
                                    @if ($student->creator)
                                        <span class="badge badge-outline-primary">{{ $student->creator->name }}</span>
                                    @else
                                        -
                                    @endif
                                </td>

                                <!-- <td>
                                                    <span
                                                        class="badge badge-outline-secondary">{{ $student->marketingAssignee->name ?? '-' }}</span>
                                                </td> -->
                                <td class="text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('admin.students.show', $student->id) }}"
                                            class="btn btn-sm btn-outline-info">View</a>

                                        <a href="{{ route('admin.students.edit', $student->id) }}"
                                            class="btn btn-sm btn-outline-primary">Edit</a>

                                        <form action="{{ route('admin.students.destroy', $student->id) }}" method="POST"
                                            onsubmit="return confirm('Delete this student?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                        </form>

                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">No data found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $students->links() }}
            </div>
        </div>
    </div>
@endsection