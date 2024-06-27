@extends('layouts.master')

@section('head')
    <link href="{{ asset('assets/css/sb-admin-2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="container-fluid">
        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <div class="row justify-content-between">
                    <div class="col">
                        <h6 class="m-0 font-weight-bold text-primary">Data Parameter Uji</h6>
                    </div>
                    <div class="col d-flex justify-content-end">
                        <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#registerModal">
                            <i class="fas fa-fw fa-plus"></i>
                            Register
                        </button>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Parameter Uji</th>
                                <th>Leadtime</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>Parameter Uji</th>
                                <th>Leadtime</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </tfoot>
                        <tbody>
                            @foreach ($parameters as $parameter)
                                <tr>
                                    <td>{{ $parameter->parameter_uji }}</td>
                                    <td>{{ $parameter->leadtime }} Hari</td>
                                    <td>
                                        <div class="row justify-content-center">
                                            <div class="col-auto">
                                                <button type="button" class="btn btn-warning btn-sm" data-toggle="modal"
                                                    data-target="#editModal{{ $parameter->id }}">Edit</button>
                                            </div>
                                            <div class="col-auto d-flex">
                                                <a class="btn btn-danger btn-sm" data-toggle="modal"
                                                    data-target="#deleteModal{{ $parameter->id }}">Delete</a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>

                                <div class="modal fade" id="editModal{{ $parameter->id }}" tabindex="-1" role="dialog"
                                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Register Sample Microbiology
                                                </h5>
                                                <button class="close" type="button" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">×</span>
                                                </button>
                                            </div>
                                            <form action="{{ route('updateParametersTesting', $parameter->id) }}"
                                                method="post">
                                                @csrf
                                                <div class="modal-body">
                                                    <div class="mb-4">
                                                        <label for="">Nama Parameter</label>
                                                        <input class="form-control" value="{{ $parameter->parameter_uji }}"
                                                            name="parameter_uji" type="text">
                                                    </div>
                                                    <div class="input-group mb-4">
                                                        <input type="number" value="{{ $parameter->leadtime }}"
                                                            min="1" name="leadtime" class="form-control"
                                                            aria-describedby="basic-addon2">
                                                        <span class="input-group-text" id="basic-addon2">Hari</span>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button class="btn btn-secondary" type="button"
                                                        data-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-success">Update</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <div class="modal fade" id="deleteModal{{ $parameter->id }}" tabindex="-1" role="dialog"
                                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Apa anda yakin ?
                                                </h5>
                                                <button class="close" type="button" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">×</span>
                                                </button>
                                            </div>
                                            <form action="{{ route('deleteParametersTesting', $parameter->id) }}"
                                                method="post">
                                                @csrf
                                                <div class="modal-body">
                                                    <div class="mb-4">
                                                        <label for="">Nama Parameter</label>
                                                        <input class="form-control" readonly value="{{ $parameter->parameter_uji }}"
                                                            name="parameter_uji" type="text">
                                                    </div>
                                                    <div class="input-group mb-4">
                                                        <input type="number" readonly value="{{ $parameter->leadtime }}"
                                                            min="1" name="leadtime" class="form-control"
                                                            aria-describedby="basic-addon2">
                                                        <span class="input-group-text" id="basic-addon2">Hari</span>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button class="btn btn-secondary" type="button"
                                                        data-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-danger">Delete</button>
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
                    <h5 class="modal-title" id="exampleModalLabel">Register Sample Microbiology</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="{{ route('storeParametersTesting') }}" method="post">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-4">
                            <label for="">Nama Parameter</label>
                            <input class="form-control" name="parameter_uji" type="text">
                        </div>
                        <div class="input-group mb-4">
                            <input type="number" min="1" name="leadtime" class="form-control"
                                aria-describedby="basic-addon2">
                            <span class="input-group-text" id="basic-addon2">Hari</span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success">Register</button>
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
