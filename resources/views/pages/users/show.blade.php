@extends('layouts.app')

@section('title', 'Detail User')

@push('style')
    <style>
        .detail-header {
            font-weight: bold;
            color: #555;
        }

        .detail-value {
            margin-bottom: 10px;
        }

        .image-preview {
            width: 200px;
            height: 200px;
            border: 2px dashed #ddd;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden;
            border-radius: 5px;
            background: #f9f9f9;
        }

        .image-preview img {
            max-width: 100%;
            max-height: 100%;
            object-fit: cover;
        }
    </style>
@endpush

@section('main')
    @if (Auth::user()->role == 'Admin')
        <div class="main-content">
            <section class="section">
                <div class="section-body">
                    <div class="card">
                        <div class="card-header">
                            <h4>Detail User</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <!-- Name -->
                                <div class="col-md-6 detail-value">
                                    <span class="detail-header">Name:</span>
                                    <p>{{ $user->name }}</p>
                                </div>

                                <!-- Email -->
                                <div class="col-md-6 detail-value">
                                    <span class="detail-header">Email:</span>
                                    <p>{{ $user->email }}</p>
                                </div>
                            </div>

                            <div class="row">
                                <!-- Role -->
                                <div class="col-md-6 detail-value">
                                    <span class="detail-header">Role:</span>
                                    <p>{{ $user->role }}</p>
                                </div>

                                <!-- No Handphone -->
                                <div class="col-md-6 detail-value">
                                    <span class="detail-header">No Handphone:</span>
                                    <p>{{ $user->no_handphone }}</p>
                                </div>
                            </div>
                            <div class="row">
                                <!-- Image -->
                                <div class="col-md-6 detail-value">
                                    <span class="detail-header">Image:</span>
                                    <div class="image-preview">
                                        @if ($user->image)
                                            <img src="{{ asset('img/user/' . $user->image) }}" alt="User Image">
                                        @else
                                            <span>No Image</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4">
                                <a href="{{ route('user.edit', $user->id) }}" class="btn btn-primary">Edit</a>
                                <a href="{{ route('user.index') }}" class="btn btn-warning">Back</a>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    @else
        <div class="alert alert-danger">
            User role Anda tidak mendapatkan izin.
        </div>
    @endif

@endsection
