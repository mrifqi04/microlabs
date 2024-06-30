@extends('layouts.master')

@section('head')
    <link href="{{ asset('assets/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="container-fluid">
        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <div class="row justify-content-between">
                    <div class="col">
                        <h6 class="m-0 font-weight-bold text-primary">Data Users</h6>
                    </div>
                    <div class="col d-flex justify-content-end">
                        <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#registerModal">
                            <i class="fas fa-fw fa-plus"></i>
                            Add User
                        </button>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>NIK</th>
                                <th>Email</th>
                                <th>Name</th>
                                <th>Username</th>
                                <th>Role</th>
                                <th>Jabatan</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>NIK</th>
                                <th>Email</th>
                                <th>Name</th>
                                <th>Username</th>
                                <th>Role</th>
                                <th>Jabatan</th>
                                <th>Action</th>
                            </tr>
                        </tfoot>
                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <td>{{ $user->nik }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->username }}</td>
                                    <td>{{ $user->jabatan }}</td>
                                    <td>{{ $user->role }}</td>
                                    <td>
                                        <button class="btn btn-warning btn-sm" type="button" data-toggle="modal"
                                            data-target="#updateModal{{ $user->id }}">
                                            <i class="fas fa-fw fa-pen"></i>
                                        </button>
                                        <form action="{{ route('deleteUser', $user->id) }}" method="post" class="d-inline">
                                            @csrf
                                            <button onclick="return confirm('Hapus data?')" type="submit"
                                                class="btn btn-danger btn-sm">
                                                <i class="fas fa-fw fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>

                                <div class="modal fade" id="updateModal{{ $user->id }}" tabindex="-1"
                                    role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Update User
                                                </h5>
                                                <button class="close" type="button" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">x</span>
                                                </button>
                                            </div>
                                            <form action="{{ route('updateUser', $user->id) }}" method="post">
                                                @csrf
                                                <div class="modal-body">
                                                    <div class="mb-4">
                                                        <label for="">NIK</label>
                                                        <input class="form-control" value="{{ $user->nik }}" required name="nik" type="text">
                                                    </div>
                                                    <div class="mb-4">
                                                        <label for="">Name</label>
                                                        <input class="form-control" value="{{ $user->name }}" required name="name" type="text">
                                                    </div>
                                                    <div class="mb-4">
                                                        <label for="">Username</label>
                                                        <input class="form-control" value="{{ $user->username }}" required name="username" type="text">
                                                    </div>
                                                    <div class="mb-4">
                                                        <label for="">Email</label>
                                                        <input class="form-control" value="{{ $user->email }}" required name="email" type="email">
                                                    </div>
                                                    <div class="mb-4">
                                                        <label for="">Role</label>
                                                        <select class="form-control" name="role" id="">
                                                            <option  {{ $user->role == 'Administrator' ? 'selected' : '' }} value="Administrator">Administrator</option>
                                                            <option  {{ $user->role == 'Analyst' ? 'selected' : '' }} value="Analyst">Analyst</option>
                                                        </select>
                                                    </div>
                                                    <div class="mb-4">
                                                        <label for="">Jabatan</label>
                                                        <input class="form-control" value="{{ $user->jabatan }}" required name="jabatan" type="text">
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button class="btn btn-secondary" type="button"
                                                        data-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-success">Submit</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="registerModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Register User</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">x</span>
                    </button>
                </div>
                <form action="{{ route('registerUser') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-4">
                            <label for="">NIK</label>
                            <input class="form-control" required name="nik" type="text">
                        </div>
                        <div class="mb-4">
                            <label for="">Name</label>
                            <input class="form-control" required name="name" type="text">
                        </div>
                        <div class="mb-4">
                            <label for="">Username</label>
                            <input class="form-control" required name="username" type="text">
                        </div>
                        <div class="mb-4">
                            <label for="">Email</label>
                            <input class="form-control" required name="email" type="email">
                        </div>
                        <div class="mb-4">
                            <label for="">Password</label>
                            <input class="form-control" name="password" min="1" type="password">
                        </div>
                        <div class="mb-4">
                            <label for="">Confirm Password</label>
                            <input class="form-control" name="password_confirmation" min="1" type="password">
                        </div>
                        <div class="mb-4">
                            <label for="">Role</label>
                            <select class="form-control" name="role" id="">
                                <option value="Administrator">Administrator</option>
                                <option value="Analyst">Analyst</option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label for="">Jabatan</label>
                            <input class="form-control" required name="jabatan" type="text">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <!-- Core plugin JavaScript-->
    <script src="{{ asset('assets/vendor/jquery-easing/jquery.easing.min.js') }}"></script>

    <!-- Custom scripts for all pages-->
    <script src="{{ asset('assets/js/sb-admin-2.min.js') }}"></script>

    <!-- Page level plugins -->
    <script src="{{ asset('assets/vendor/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>

    <!-- Page level custom scripts -->
    <script src="{{ asset('assets/js/demo/datatables-demo.js') }}"></script>
@endsection
