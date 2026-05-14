@extends('admin.layouts.master')

@section('title', 'Courses')

@section('content')

    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-semibold uppercase">Courses</h2>
        @can('create-course')
            <a href="{{ route('admin.courses.create') }}" class="btn btn-primary">Add Course</a>
        @endcan
    </div>

    <div class="panel mt-6">
        <div class="mb-5 flex flex-col gap-5 md:flex-row md:items-center">
            <form action="{{ route('admin.courses.index') }}" method="GET"
                class="flex flex-1 flex-col gap-5 md:flex-row md:items-center w-full">
                <div class="relative w-full md:w-80">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search course name..."
                        class="form-input ltr:pr-11 rtl:pl-11" />
                    <button type="submit"
                        class="absolute inset-y-0 flex items-center hover:text-primary ltr:right-4 rtl:left-4">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <circle cx="11.5" cy="11.5" r="9.5" stroke="currentColor" stroke-width="1.5"
                                opacity="0.5" />
                            <path d="M18.5 18.5L22 22" stroke="currentColor" stroke-width="1.5"
                                stroke-linecap="round" />
                        </svg>
                    </button>
                </div>
                <div class="flex gap-2">
                    <select name="university_id" class="form-select w-full md:w-80 pr-10">
                        <option value="">All Universities</option>
                        @foreach ($universities as $university)
                            <option value="{{ $university->id }}" {{ request('university_id') == $university->id ? 'selected' : '' }}>
                                {{ $university->name }}
                            </option>
                        @endforeach
                    </select>
                    <button type="submit" class="btn btn-primary">Filter</button>
                    <a href="{{ route('admin.courses.index') }}" class="btn btn-outline-danger">Reset</a>
                </div>
            </form>
        </div>
        <table class="table-auto w-full">
            <thead>
                <tr>
                    <th>#</th>
                    <th>University</th>
                    <th>Name</th>
                    <!-- <th>Description</th> -->
                    <th>Degree Level</th>
                    <th>Duration</th>
                    <!-- <th>Tuition Fee</th> -->
                    <th>Status</th>
                    <th class="text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($courses as $course)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $course->university->name ?? 'N/A' }}</td>
                        <td>{{ \Illuminate\Support\Str::limit($course->name, 30) ?? 'N/A' }}</td>
                        <!-- <td>{{ \Illuminate\Support\Str::limit(strip_tags($course->description), 50) ?? 'N/A' }}</td> -->
                        <td>{{ $course->degree_level ?? 'N/A' }}</td>
                        <td>{{ $course->duration ?? 'N/A' }}</td>
                        <!-- <td>{{ $course->tuition_fee ? number_format($course->tuition_fee, 2) : 'N/A' }}</td> -->
                        <td>
                            <span class="badge {{ $course->status ? 'bg-success' : 'bg-danger' }}">
                                {{ $course->status ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="flex items-center justify-center gap-2">
                            <a href="{{ route('admin.courses.show', $course->id) }}"
                                class="btn btn-sm btn-outline-info">View</a>
                            <a href="{{ route('admin.courses.edit', $course->id) }}"
                                class="btn btn-sm btn-outline-primary">Edit</a>

                            <form action="{{ route('admin.courses.destroy', $course->id) }}" method="POST" class="inline-block"
                                onsubmit="return confirm('Are you sure?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center py-4">No courses found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-4">
            {{ $courses->links() }}
        </div>
    </div>

@endsection