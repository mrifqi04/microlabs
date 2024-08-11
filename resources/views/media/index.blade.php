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
                        <h6 class="m-0 font-weight-bold text-primary">Data Medias</h6>
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
                                <th>Download Barcode</th>
                                <th>No Media</th>
                                <th>Nama Media</th>
                                <th>Leadtime</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr class="text-center">
                                <th>No</th>
                                <th>Download Barcode</th>
                                <th>No Media</th>
                                <th>Nama Media</th>
                                <th>Leadtime</th>
                                <th>Action</th>
                            </tr>
                        </tfoot>
                        <tbody>
                            @foreach ($medias as $key => $media)
                                <tr class="text-center">
                                    <td class="align-middle">{{ $key + 1 }}</td>
                                    <td class="align-middle">
                                        <a target="_blank" href="{{ route('generateBarcodeMedia', $media->id) }}"
                                            class="btn btn-info btn-sm" type="button">
                                            <i class="fas fa-barcode fa-sm text-white-50"></i> <br>
                                            Download Barcode
                                        </a>
                                    </td>
                                    <td class="align-middle">
                                        {{ $media->no_media }}
                                    </td>
                                    <td class="align-middle">{{ $media->media_name }}</td>
                                    <td class="align-middle">
                                        {{ $media->leadtime ? Date('d-m-Y, H:i', strtotime($media->leadtime)) : '-' }}
                                    </td>
                                    <td class="align-middle">
                                        <button class="btn btn-info btn-sm" type="button" data-toggle="modal"
                                            data-target="#showModal{{ $media->id }}">
                                            <i class="fas fa-fw fa-eye"></i>
                                        </button> <br>
                                        <button class="btn btn-warning btn-sm" type="button" data-toggle="modal"
                                            data-target="#updateModal{{ $media->id }}">
                                            <i class="fas fa-fw fa-pen"></i>
                                        </button> <br>
                                        @if (Auth::user()->role == 'SuperAdmin' || Auth::user()->role == 'Administrator')
                                            <form action="{{ route('deleteMedia', $media->id) }}" method="post"
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

                                <div class="modal fade" id="showModal{{ $media->id }}" tabindex="-1" role="dialog"
                                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-lg" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Detail Media Microbiology
                                                </h5>
                                                <button class="close" type="button" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">x</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-4">
                                                    <label for="">No ID Media</label>
                                                    <input class="form-control" value="{{ $media->no_media }}" readonly
                                                        name="no_media" type="text">
                                                </div>
                                                <div class="mb-4">
                                                    <label for="">Nama Media</label>
                                                    <input class="form-control" value="{{ $media->media_name }}" readonly
                                                        name="media_name" type="text">
                                                </div>
                                                <div class="mb-4">
                                                    <label for="">PIC</label>
                                                    <input class="form-control" value="{{ $media->PIC->name }}" readonly
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

                                <div class="modal fade" id="updateModal{{ $media->id }}" tabindex="-1" role="dialog"
                                    aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                                            <form action="{{ route('updateMedia', $media->id) }}" method="post">
                                                @csrf
                                                <div class="modal-body">
                                                    <div class="mb-4">
                                                        <label for="">No ID Media</label>
                                                        <input class="form-control" value="{{ $media->no_media }}"
                                                            name="no_media" type="text">
                                                    </div>
                                                    <div class="mb-4">
                                                        <label for="">Nama Media</label>
                                                        <input class="form-control" value="{{ $media->media_name }}"
                                                            name="media_name" type="text">
                                                    </div>
                                                    <div class="mb-4">
                                                        <label for="">PIC</label>
                                                        <input class="form-control" value="{{ $media->PIC->name }}" readonly
                                                            name="no_batch" type="text">
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
            <form action="{{ route('storeMedia') }}" method="post">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Register Media Microbiology</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">x</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-4">
                            <label for="">No ID Media</label>
                            <input class="form-control" required name="no_media" type="text">
                        </div>
                        <div class="mb-4">
                            <label for="">Nama Media</label>
                            <input class="form-control" required name="media_name" type="text">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success">Submit</button>
                    </div>
                </div>
            </form>
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
