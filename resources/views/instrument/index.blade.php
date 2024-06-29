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
                        <h6 class="m-0 font-weight-bold text-primary">Data Instrument</h6>
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
                                <th>Barcode</th>
                                <th>ID Instrument</th>
                                <th>Nama Instrument</th>
                                <th>Tanggal Kalibrasi</th>
                                <th>Tanggal Re Kalibrasi</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>Barcode</th>
                                <th>ID Instrument</th>
                                <th>Nama Instrument</th>
                                <th>Tanggal Kalibrasi</th>
                                <th>Tanggal Re Kalibrasi</th>
                                <th>Action</th>
                            </tr>
                        </tfoot>
                        <tbody>
                            @foreach ($instruments as $instrument)
                                <tr>
                                    <td>
                                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=1000x1000&data={{ $instrument->qr_code }}"
                                        alt="sample-{{ $instrument->id }}" width="100">
                                    </td>
                                    <td>{{ $instrument->id_instrument }}</td>
                                    <td>{{ $instrument->nama_instrument }}</td>
                                    <td>{{ Date('d-m-Y', strtotime($instrument->tanggal_kalibrasi)) }}</td>
                                    <td>{{ Date('d-m-Y', strtotime($instrument->tanggal_rekalibrasi)) }}</td>
                                    <td>
                                        <button class="btn btn-info btn-sm" type="button" data-toggle="modal"
                                            data-target="#showModal{{ $instrument->id }}">
                                            <i class="fas fa-fw fa-eye"></i>
                                        </button>
                                        <button class="btn btn-warning btn-sm" type="button" data-toggle="modal"
                                            data-target="#updateModal{{ $instrument->id }}">
                                            <i class="fas fa-fw fa-pen"></i>
                                        </button>
                                        <form action="{{ route('deleteInstrument', $instrument->id) }}" method="post"
                                            class="d-inline">
                                            @csrf
                                            <button onclick="return confirm('Hapus data?')" type="submit"
                                                class="btn btn-danger btn-sm">
                                                <i class="fas fa-fw fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>

                                <div class="modal fade" id="showModal{{ $instrument->id }}" tabindex="-1" role="dialog"
                                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Register Instrument
                                                    Microbiology</h5>
                                                <button class="close" type="button" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">x</span>
                                                </button>
                                            </div>
                                            <form action="{{ route('storeInstruments') }}" method="post">
                                                @csrf
                                                <div class="modal-body">
                                                    <div class="mb-4">
                                                        <label for="">ID Instrument</label>
                                                        <input class="form-control" readonly value="{{ $instrument->id_instrument }}" name="id_instrument"
                                                            type="text">
                                                    </div>
                                                    <div class="mb-4">
                                                        <label for="">Nama instrument</label>
                                                        <input class="form-control" readonly value="{{ $instrument->nama_instrument }}" name="nama_instrument"
                                                            type="text">
                                                    </div>
                                                    <div class="mb-4">
                                                        <label for="">Tanggal Kalibrasi</label>
                                                        <input class="form-control" readonly value="{{ $instrument->tanggal_kalibrasi }}" name="tanggal_kalibrasi"
                                                            min="1" type="date">
                                                    </div>
                                                    <div class="mb-4">
                                                        <label for="">Tanggal Rekalibrasi</label>
                                                        <input class="form-control" readonly value="{{ $instrument->tanggal_rekalibrasi }}" name="tanggal_rekalibrasi"
                                                            min="1" type="date">
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

                                <div class="modal fade" id="updateModal{{ $instrument->id }}" tabindex="-1" role="dialog"
                                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Register Instrument
                                                    Microbiology</h5>
                                                <button class="close" type="button" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">x</span>
                                                </button>
                                            </div>
                                            <form action="{{ route('updateInstrument', $instrument->id) }}" method="post">
                                                @csrf
                                                <div class="modal-body">
                                                    <div class="mb-4">
                                                        <label for="">ID Instrument</label>
                                                        <input class="form-control" value="{{ $instrument->id_instrument }}" name="id_instrument"
                                                            type="text">
                                                    </div>
                                                    <div class="mb-4">
                                                        <label for="">Nama instrument</label>
                                                        <input class="form-control" value="{{ $instrument->nama_instrument }}" name="nama_instrument"
                                                            type="text">
                                                    </div>
                                                    <div class="mb-4">
                                                        <label for="">Tanggal Kalibrasi</label>
                                                        <input class="form-control" value="{{ $instrument->tanggal_kalibrasi }}" name="tanggal_kalibrasi"
                                                            min="1" type="date">
                                                    </div>
                                                    <div class="mb-4">
                                                        <label for="">Tanggal Rekalibrasi</label>
                                                        <input class="form-control" value="{{ $instrument->tanggal_rekalibrasi }}" name="tanggal_rekalibrasi"
                                                            min="1" type="date">
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
                    <h5 class="modal-title" id="exampleModalLabel">Register Instrument Microbiology</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">x</span>
                    </button>
                </div>
                <form action="{{ route('storeInstruments') }}" method="post">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-4">
                            <label for="">ID Instrument</label>
                            <input class="form-control" required name="id_instrument" type="text">
                        </div>
                        <div class="mb-4">
                            <label for="">Nama instrument</label>
                            <input class="form-control" required name="nama_instrument" type="text">
                        </div>
                        <div class="mb-4">
                            <label for="">Tanggal Kalibrasi</label>
                            <input class="form-control" required name="tanggal_kalibrasi" min="1"
                                type="datetime-local">
                        </div>
                        <div class="mb-4">
                            <label for="">Tanggal Rekalibrasi</label>
                            <input class="form-control" required name="tanggal_rekalibrasi" min="1"
                                type="datetime-local">
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
