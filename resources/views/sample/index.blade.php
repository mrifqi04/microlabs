@extends('layouts.master')

{{-- @section('head')
    <style>
        .table td,
        table th {
            text-align: center;
            vertical-align: center;
            align-items: mid;
        }
    </style>
@endsection --}}

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
                        <h6 class="m-0 font-weight-bold text-primary">Data Samples</h6>
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
                            <tr class="text-center">
                                <th>No</th>
                                <th>Barcode</th>
                                <th>Detail</th>
                                <th>Jumlah</th>
                                <th>Tanggal Terima</th>
                                <th>PIC</th>
                                <th>Parameter Uji</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr class="text-center">
                                <th>No</th>
                                <th>Barcode</th>
                                <th>Detail</th>
                                <th>Jumlah</th>
                                <th>Tanggal Terima</th>
                                <th>PIC</th>
                                <th>Parameter Uji</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </tfoot>
                        <tbody>
                            @foreach ($samples as $key => $sample)
                                <tr class="text-center">
                                    <td class="align-middle">{{ $key + 1 }}</td>
                                    <td class="align-middle">
                                        <a target="_blank" href="{{ route('generateBarcode', $sample->id) }}"
                                            class="btn btn-info btn-sm" type="button">
                                            <i class="fas fa-barcode fa-sm text-white-50"></i> <br>
                                            Download Barcode
                                        </a>
                                    </td>
                                    <td>
                                        <small>Type</small>
                                        <p>
                                            {{ $sample->TypeTesting->type }}
                                        </p>
                                        <hr>
                                        <small>Deskripsi</small>
                                        <p>
                                            {{ $sample->deskripsi_sample }}
                                        </p>
                                        <hr>
                                        <small>No Sample</small>
                                        <p>
                                            {{ $sample->no_sample }}
                                        </p>
                                        <hr>
                                        <small>No Batch</small>
                                        <p>
                                            {{ $sample->no_batch }}
                                        </p>
                                    </td>
                                    <td class="align-middle">{{ $sample->jumlah_sampel }}</td>
                                    <td class="align-middle"> {{ Date('d-m-Y, H:i', strtotime($sample->tanggal_terima)) }}
                                    </td>
                                    <td class="align-middle">{{ $sample->PIC->name }}</td>
                                    <td class="align-middle">{{ $sample->ParameterTesting->parameter_uji }}</td>
                                    <td class="align-middle">
                                        @if ($sample->status == 'Pending')
                                            <span class="badge badge-warning">{{ $sample->status }}</span>
                                        @endif
                                        @if ($sample->status == 'On Process')
                                            <span class="badge badge-info">{{ $sample->status }}</span>
                                        @endif
                                        @if ($sample->status == 'Done')
                                            <span class="badge badge-success">{{ $sample->status }}</span>
                                        @endif
                                    </td>
                                    <td class="align-middle">
                                        <button class="btn btn-info btn-sm" type="button" data-toggle="modal"
                                            data-target="#showModal{{ $sample->id }}">
                                            <i class="fas fa-fw fa-eye"></i>
                                        </button> <br>
                                        <button class="btn btn-warning btn-sm" type="button" data-toggle="modal"
                                            data-target="#updateModal{{ $sample->id }}">
                                            <i class="fas fa-fw fa-pen"></i>
                                        </button> <br>
                                        @if (Auth::user()->role == 'SuperAdmin' || Auth::user()->role == 'Administrator')
                                            <form action="{{ route('deleteSample', $sample->id) }}" method="post"
                                                class="d-inline">
                                                @csrf
                                                <button onclick="return confirm('Hapus data?')" type="submit"
                                                    class="btn btn-danger btn-sm">
                                                    <i class="fas fa-fw fa-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>

                                <div class="modal fade" id="showModal{{ $sample->id }}" tabindex="-1" role="dialog"
                                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-lg" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Detail Sample Microbiology
                                                </h5>
                                                <button class="close" type="button" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">x</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-4">
                                                    <label for="">Type</label>
                                                    <input class="form-control" value="{{ $sample->TypeTesting->type }}"
                                                        readonly name="no_batch" type="text">
                                                </div>
                                                <div class="mb-4">
                                                    <label for="">No ID Sample</label>
                                                    @php
                                                        $no_sample = explode('-', $sample->no_sample);
                                                    @endphp
                                                    <div class="row">
                                                        <div class="col-2">
                                                            <input class="form-control" value="{{ $no_sample[0] }}"
                                                                readonly name="section1" type="text">
                                                        </div>
                                                        <div class="col-10">
                                                            <input class="form-control" value="{{ $no_sample[1] }}"
                                                                name="section2" type="text" readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mb-4">
                                                    <label for="">Deskripsi Sample</label>
                                                    <textarea class="form-control" readonly name="deskripsi_sample" id="" cols="30" rows="5">{{ $sample->deskripsi_sample }}</textarea>
                                                </div>
                                                <div class="mb-4">
                                                    <label for="">No. Batch / No. QC / Titik Sampling /
                                                        Lokasi</label>
                                                    <input class="form-control" value="{{ $sample->no_batch }}" readonly
                                                        name="no_batch" type="text">
                                                </div>
                                                <div class="mb-4">
                                                    <label for="">Waktu di Terima QC</label>
                                                    <input class="form-control"
                                                        value="{{ Date('d-m-Y, H:i', strtotime($sample->tanggal_terima)) }}"
                                                        readonly name="tanggal_terima" type="text">
                                                </div>
                                                <div class="mb-4">
                                                    <label for="">Tenggat QC</label>
                                                    <input class="form-control"
                                                        value="{{ Date('d-m-Y, H:i', strtotime($sample->tenggat_testing)) }}"
                                                        readonly name="tenggat_testing" type="text">
                                                </div>
                                                <div class="mb-4">
                                                    <label for="">Jumlah Sample</label>
                                                    <input class="form-control" value="{{ $sample->jumlah_sampel }}"
                                                        readonly name="jumlah_sampel" min="1" type="number">
                                                </div>
                                                <div class="mb-4">
                                                    <label for="">Parameter Uji</label>
                                                    <input class="form-control"
                                                        value="{{ $sample->ParameterTesting->parameter_uji }}" readonly
                                                        name="no_batch" type="text">
                                                </div>
                                                <div class="mb-4">
                                                    <label for="">PIC</label>
                                                    <input class="form-control" value="{{ $sample->PIC->name }}" readonly
                                                        name="no_batch" type="text">
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button class="btn btn-secondary" type="button"
                                                    data-dismiss="modal">Back</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="modal fade" id="updateModal{{ $sample->id }}" tabindex="-1"
                                    role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-lg" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Update Sample Microbiology
                                                </h5>
                                                <button class="close" type="button" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">x</span>
                                                </button>
                                            </div>
                                            <form action="{{ route('updateSample', $sample->id) }}" method="post">
                                                @csrf
                                                <div class="modal-body">
                                                    <div class="mb-4">
                                                        <select name="type" class="form-control"
                                                            id="update_type_sample">
                                                            <option selected disabled>--- Select ----</option>
                                                            @foreach ($types as $type)
                                                                <option data-id={{ $type->id }}
                                                                    {{ $type->id == $sample->type_id ? 'selected' : '' }}
                                                                    value="{{ $type->type_code }}">
                                                                    {{ $type->type }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="mb-4">
                                                        <label for="">No ID Sample</label>
                                                        @php
                                                            $no_sample = explode('-', $sample->no_sample);
                                                        @endphp
                                                        <div class="row">
                                                            <div class="col-2">
                                                                <input class="form-control" value="{{ $no_sample[0] }}"
                                                                    name="section1" type="text">
                                                            </div>
                                                            <div class="col-10">
                                                                <input class="form-control" value="{{ $no_sample[1] }}"
                                                                    name="section2" type="text">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="mb-4">
                                                        <label for="">Deskripsi Sample</label>
                                                        <textarea class="form-control" name="deskripsi_sample" id="" cols="30" rows="5">{{ $sample->deskripsi_sample }}</textarea>
                                                    </div>
                                                    <div class="mb-4">
                                                        <label for="">No. Batch / No. QC / Titik Sampling /
                                                            Lokasi</label>
                                                        <input class="form-control" value="{{ $sample->no_batch }}"
                                                            name="no_batch" type="text">
                                                    </div>
                                                    <div class="mb-4">
                                                        <label for="">Waktu di Terima QC</label>
                                                        <input class="form-control" value="{{ $sample->tanggal_terima }}"
                                                            name="tanggal_terima" type="datetime-local">
                                                    </div>
                                                    <div class="mb-4">
                                                        <label for="">Tenggat QC</label>
                                                        <input class="form-control"
                                                            value="{{ $sample->tenggat_testing }}" name="tenggat_testing"
                                                            type="datetime-local">
                                                    </div>
                                                    <div class="mb-4" id="update_jumlah_sampel">
                                                        <label for="">Jumlah Sample</label>
                                                        <input class="form-control" value="{{ $sample->jumlah_sampel }}"
                                                            name="jumlah_sampel" min="1" type="number" required>
                                                    </div>
                                                    <div class="mb-4">
                                                        <label for="">Parameter Uji</label>
                                                        <div class="row">
                                                            @foreach ($parameters as $parameter)
                                                                <div class="col-4 mb-2">
                                                                    <div class="form-check">
                                                                        <input class="form-check-input"
                                                                            {{ $sample->parameter_testing_id === $parameter->id ? 'checked' : '' }}
                                                                            value="{{ $parameter->id }}" type="radio"
                                                                            required name="parameter_testing_id"
                                                                            id="flexRadioDefault1">
                                                                        <label class="form-check-label">
                                                                            {{ $parameter->parameter_uji }}
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        </div>
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
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Register Sample Microbiology</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">x</span>
                    </button>
                </div>
                <form action="{{ route('storeSamples') }}" method="post">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-4">
                            <select name="type" class="form-control" id="type_sample">
                                <option selected disabled>--- Select ----</option>
                                @foreach ($types as $type)
                                    <option data-id={{ $type->id }} value="{{ $type->type_code }}">
                                        {{ $type->type }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-4">
                            <label for="">No ID Sample</label>
                            <div class="row">
                                <div class="col-2">
                                    <input class="form-control" required name="section1" type="text"
                                        value="{{ Date('Y', strtotime(\Carbon\Carbon::now())) }}" readonly>
                                </div>
                                <div class="col-2">
                                    <input class="form-control" required name="section2" type="text"
                                        id="no_sample_section_2" readonly>
                                </div>
                                <div class="col-2">
                                    <input class="form-control" required name="section3" type="text">
                                </div>
                                <div class="col-auto">
                                    <input class="form-control" required name="section4" id="no_sample_section_4"
                                        type="text" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label for="">Deskripsi Sample</label>
                            <textarea class="form-control" required name="deskripsi_sample" id="" cols="30" rows="5"></textarea>
                        </div>
                        <div class="mb-4">
                            <label for="">No. Batch / No. QC / Titik Sampling / Lokasi</label>
                            <input class="form-control" required name="no_batch" type="text">
                        </div>
                        <div class="mb-4">
                            <label for="">Waktu di Terima QC</label>
                            <input class="form-control" required name="tanggal_terima" type="datetime-local">
                        </div>
                        <div class="mb-4" style="display: none" id="jumlah_sampel">
                            <label for="">Jumlah Sample</label>
                            <input class="form-control" name="jumlah_sampel" min="1" type="number">
                        </div>
                        <div class="mb-4">
                            <label for="">Parameter Uji</label>
                            <div class="row">
                                @foreach ($parameters as $parameter)
                                    <div class="col-4 mb-2">
                                        <div class="form-check">
                                            <input class="form-check-input" value="{{ $parameter->id }}" type="checkbox"
                                                name="parameter_testing_id[]" id="flexRadioDefault1">
                                            <label class="form-check-label">
                                                {{ $parameter->parameter_uji }}
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
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


    <script>
        $('#type_sample').on('change', function() {
            const value = $(this).val();
            const dataId = $(this).find(':selected').attr('data-id')

            $.ajax({
                url: "/count-sample/" + dataId,
                type: 'GET',
                success: function(res) {
                    var res = res + 1
                    const countSample = res.toString();
                    $('#no_sample_section_4').val(countSample.padStart(4, "0"));
                }
            });
            $('#no_sample_section_2').val(value)
            if (value === 'BEM') {
                $('#jumlah_sampel').css('display', 'block')
            } else {
                $('#jumlah_sampel').css('display', 'none')
            }
        })
    </script>
@endsection
