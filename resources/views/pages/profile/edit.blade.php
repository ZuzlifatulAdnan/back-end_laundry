@extends('layouts.app')

@section('title', 'Profile')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/selectric/public/selectric.css') }}">
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Profile</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('profile.index') }}">Profile</a></div>
                    <div class="breadcrumb-item">Edit Profile</div>
                </div>
            </div>
            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        <form action="{{ route('profile.update', Auth::user()) }}" enctype="multipart/form-data"
                            method="POST">
                            {{-- @method('PUT') --}}
                            @csrf
                            <div class="row">
                                <div class="col-12 col-md-12 col-lg-4">
                                    <div class="card profile-widget">
                                        <div class="profile-widget-header">
                                            <img alt="image" id="image-preview"
                                                src="{{ Auth::user()->foto ? asset('img/avatar/' . Auth::user()->foto) : asset('img/avatar/avatar-1.png') }}"
                                                class="rounded-circle profile-widget-picture" width="100" height="100">
                                        </div>
                                        <div class="p-3">
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" id="file-input"
                                                    name="file" id="file-input" accept="image/*"
                                                    onchange="previewImage()">
                                                <label class="custom-file-label" for="customFile">Choose file</label>
                                            </div>
                                            @error('file')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>

                                        <div class="profile-widget-description">
                                            <div class="profile-widget-name">
                                                {{ Auth::user()->email }}
                                            </div>

                                        </div>
                                        <div class="card-footer">

                                            <button class="btn btn-primary btn-lg btn-block">Simpan
                                            </button>
                                            <a href="{{ route('profile.index') }}"
                                                class="btn btn-warning btn-lg btn-block">Kembali</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-12 col-lg-8">
                                    <div class="card">
                                        <div class="card-header">
                                            <h4>Edit Akun</h4>
                                        </div>
                                        <div class="card-body">
                                                                                        <div class="row">
                                                <div class="form-group col-md-6 mb-3">
                                                    <label for="name" class="form-label">Name</label>
                                                    <input type="text"
                                                        class="form-control @error('name') is-invalid

                                                    @enderror"
                                                        id="name" name="name" value="{{ Auth::user()->name }}">
                                                    @error('name')
                                                        <div class="invalid-feedback">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                                <div class="form-group col-md-6 mb-3">
                                                    <label for="email" class="form-label">Email</label>
                                                    <input type="email"
                                                        class="form-control @error('email') is-invalid
                                                    @enderror"
                                                        name="email" id="email" value="{{ Auth::user()->email }}">
                                                    @error('email')
                                                        <div class="invalid-feedback">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="form-group col-md-12 mb-3">
                                                    <label class="form-label">No Handphone</label>
                                                    <input type="text"
                                                        class="form-control @error('no_handphone') is-invalid

                                                @enderror"
                                                        id="no_handphone" name="no_handphone" placeholder="6282176556"
                                                        value="{{ Auth::user()->no_handphone }}">
                                                    @error('no_handphone')
                                                        <div class="invalid-feedback">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="row">
                                                @if (Auth::user()->role == 'Mahasiswa')
                                                    <div class="form-group col-md-12 mb-3">
                                                        <label for="npm" class="form-label">NPM</label>
                                                        <input type="text" class="form-control" id="npm"
                                                            name="npm"  value="{{ Auth::user()->npm }}">
                                                    </div>
                                                @elseif(Auth::user()->role == 'Dosen')
                                                    <div class="form-group col-md-12 mb-3">
                                                        <label for="nip" class="form-label">NIP</label>
                                                        <input type="text" class="form-control" id="nip"
                                                            name="nip"  value="{{ Auth::user()->nip }}">
                                                    </div>
                                                @else
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <!-- JS Libraies -->
    <script src="{{ asset('library/selectric/public/jquery.selectric.min.js') }}"></script>
    <script>
        function previewImage() {
            var fileInput = document.getElementById('file-input');
            var imagePreview = document.getElementById('image-preview');

            // Cek apakah ada file yang dipilih
            if (fileInput.files && fileInput.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    // Menetapkan src gambar pratinjau dengan data URL dari file yang dipilih
                    imagePreview.src = e.target.result;
                }

                // Membaca file sebagai data URL
                reader.readAsDataURL(fileInput.files[0]);
            } else {
                // Jika tidak ada file yang dipilih, menetapkan src ke gambar default
                imagePreview.src = "{{ asset('img/avatar/avatar-1.png') }}";
            }
        }
    </script>

    <!-- Page Specific JS File -->
    <script src="{{ asset('js/page/features-posts.js') }}"></script>
@endpush