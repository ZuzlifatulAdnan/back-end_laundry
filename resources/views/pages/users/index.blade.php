@extends('layouts.app')

@section('title', 'Users')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/d') }}">
@endpush

@section('main')
    @if (Auth::user()->role == 'Admin')
        <div class="main-content">
            <section class="section">
                <div class="section-body">
                    <div class="row">
                        <div class="col-12">
                            @include('layouts.alert')
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4>Data Users</h4>
                                </div>
                                <div class="card-body">
                                    {{-- Filter & Tambah --}}
                                    <div class="mb-3">
                                        <div class="row align-items-end">
                                            <div class="col-md-2 mb-2">
                                                <a href="{{ route('user.create') }}" class="btn btn-primary w-100">
                                                    <i class="fas fa-plus"></i> Tambah
                                                </a>
                                            </div>
                                            <div class="col-md-10">
                                                <form action="{{ route('user.index') }}" method="GET">
                                                    <div class="form-row row">
                                                        <div class="col-md-2 mb-2">
                                                            <select name="role" class="form-control"
                                                                onchange="this.form.submit()">
                                                                <option value="">Semua Role</option>
                                                                <option value="Admin"
                                                                    {{ request('role') == 'Admin' ? 'selected' : '' }}>
                                                                    Admin</option>
                                                                <option value="Customer"
                                                                    {{ request('role') == 'Customer' ? 'selected' : '' }}>
                                                                    Customer</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-3 mb-2">
                                                            <input type="text" name="name" class="form-control"
                                                                placeholder="Cari Nama User" value="{{ request('name') }}">
                                                        </div>
                                                        <div class="col-md-1 mb-2">
                                                            <button class="btn btn-primary w-100" type="submit">
                                                                <i class="fas fa-search"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="clearfix  divider mb-3"></div>

                                    <div class="table-responsive">
                                        <table class="table table-hover table-bordered table-lg" id="table-1">
                                            <tr>
                                                <th style="width: 3%">No</th>
                                                <th class="text-center">Image</th>
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>Role</th>
                                                <th style="width: 5%" class="text-center">Action</th>
                                            </tr>
                                            @foreach ($users as $index => $user)
                                                <tr>
                                                    <td>
                                                        {{ $users->firstItem() + $index }}
                                                    </td>
                                                    <td class="text-center">
                                                        <img alt="image"
                                                            src="{{ $user->image ? asset('img/user/' . $user->image) : asset('img/avatar/avatar-1.png') }}"
                                                            class="rounded-circle" width="35" height="35"
                                                            data-toggle="tooltip" title="avatar">
                                                    </td>
                                                    <td>
                                                        {{ $user->name }}
                                                    </td>
                                                    <td>
                                                        {{ $user->email }}
                                                    </td>
                                                    <td>
                                                        {{ $user->role }}
                                                    </td>
                                                    <td text='text-center'>
                                                        <div class="d-flex justify-content-center">
                                                            <a href="{{ route('user.edit', $user) }}"
                                                                class="btn btn-sm btn-icon btn-primary m-1"
                                                                data-bs-toggle="tooltip" title="Edit"><i
                                                                    class="fas fa-edit"></i></a>
                                                            <a href="{{ route('user.show', $user) }}"
                                                                class="btn btn-sm btn-icon btn-info m-1"
                                                                data-bs-toggle="tooltip" title="Lihat"><i
                                                                    class="fas fa-eye"></i></a>
                                                            <form action="{{ route('user.destroy', $user) }}"
                                                                method="post">
                                                                @csrf
                                                                @method('delete')
                                                                <button
                                                                    class="btn btn-sm btn-icon m-1 btn-danger confirm-delete "
                                                                    data-bs-toggle="tooltip" title="Hapus">
                                                                    <i class="fas fa-trash"></i>
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </table>
                                        <div class="card-footer d-flex justify-content-between">
                                            <span>
                                                Showing {{ $users->firstItem() }}
                                                to {{ $users->lastItem() }}
                                                of {{ $users->total() }} entries
                                            </span>
                                            <div class="paginate-sm">
                                                {{ $users->onEachSide(0)->links() }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
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

@push('scripts')
    <!-- JS Libraies -->
    <script src="{{ asset('library/datatables/media/js/jquery.dataTables.min.js') }}"></script>
    {{-- <script src="{{ asset() }}"></script> --}}
    {{-- <script src="{{ asset() }}"></script> --}}
    <script src="{{ asset('library/jquery-ui-dist/jquery-ui.min.js') }}"></script>

    <!-- Page Specific JS File -->
    <script src="{{ asset('js/page/modules-datatables.js') }}"></script>
    <script src="{{ asset('js/page/components-table.js') }}"></script>
@endpush
