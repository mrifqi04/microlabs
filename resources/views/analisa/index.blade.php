@extends('layouts.master')

@section('head')
    <link href="{{ asset('assets/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="container-fluid">
        <div class="d-sm-flex align-items-center mb-4">
            <button href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" data-toggle="modal"
                data-target="#scanInModal">
                <i class="fas fa-arrow-down fa-sm text-white-50"></i>
                Scan in Sample
            </button>
            <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm ml-3">
                <i class="fas fa-arrow-up fa-sm text-white-50"></i>
                Scan out Sample
            </a>
        </div>

        <div class="row">
            <!-- Earnings (Monthly) Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Sample Masuk</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">12</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-arrow-down fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Earnings (Monthly) Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Sample Keluar</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">13</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-arrow-up fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Barcode</th>
                            <th>Type</th>
                            <th>No Sample</th>
                            <th>No Batch</th>
                            <th>Jumlah</th>
                            <th>Tanggal Terima</th>
                            <th>Tenggat Testing</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>Barcode</th>
                            <th>Type</th>
                            <th>No Sample</th>
                            <th>No Batch</th>
                            <th>Jumlah</th>
                            <th>Tanggal Terima</th>
                            <th>Tenggat Testing</th>
                            <th>Action</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        {{-- @foreach ($samples as $sample) --}}
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td>
                                <button class="btn btn-info btn-sm" type="button" data-toggle="modal"
                                    data-target="#showModal">
                                    <i class="fas fa-fw fa-eye"></i>
                                </button>
                                <button class="btn btn-warning btn-sm" type="button" data-toggle="modal"
                                    data-target="#updateModal">
                                    <i class="fas fa-fw fa-pen"></i>
                                </button>
                                <form action="" method="post" class="d-inline">
                                    @csrf
                                    <button onclick="return confirm('Hapus data?')" type="submit"
                                        class="btn btn-danger btn-sm">
                                        <i class="fas fa-fw fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        {{-- @endforeach --}}
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="scanInModal" tabindex="-1" role="dialog" aria-labelledby="scanInModal" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="scanInModal">Scan In Sample</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">x</span>
                    </button>
                </div>
                <form action="{{ route('storeAnalitics') }}" method="post">
                    @csrf
                    <div class="modal-body">
                        <div style="width: 100%" id="sampleReader"></div>
                        <div id="sampleData" style="display: none">
                            <div class="mb-4">
                                <label class="form-label">Jenis Sample</label>
                                <input readonly class="form-control" id="sampleType">
                            </div>
                            <div class="mb-4">
                                <label class="form-label">No Sample</label>
                                <div class="row">
                                    <div class="col-3">
                                        <input readonly class="form-control" name="sampleIDSection1" id="sampleIDSection1">
                                    </div>
                                    <div class="col-3">
                                        <input readonly class="form-control" name="sampleIDSection2" id="sampleIDSection2">
                                    </div>
                                    <div class="col-3">
                                        <input readonly class="form-control" name="sampleIDSection3" id="sampleIDSection3">
                                    </div>
                                    <div class="col-3">
                                        <input readonly class="form-control" name="sampleIDSection4" id="sampleIDSection4">
                                    </div>
                                </div>
                            </div>
                            <div class="mb-4">
                                <label class="form-label">Deskripsi Sample</label>
                                <textarea readonly class="form-control" name="sampleDesc" id="sampleDesc"></textarea>
                            </div>
                            <div class="mb-4">
                                <label class="form-label">No. Batch / No. QC / Titik Sampling / Lokasi</label>
                                <input readonly class="form-control" name="sampleNoBatch" id="sampleNoBatch">
                            </div>
                            <div class="mb-4">
                                <label class="form-label">Tanggal sample masuk</label>
                                <input readonly class="form-control" name="sampleDateIn" type="datetime-local" id="sampleDateIn">
                            </div>
                            <div class="mb-4">
                                <label class="form-label">Parameter uji</label>
                                <input readonly class="form-control" name="sampleParameterUji" id="sampleParameterUji">
                            </div>
                        </div>
                        <div id="instrumentData" style="display: none">
                            <div class="mb-4">
                                <label class="form-label">No ID Instrument</label>
                                <input readonly class="form-control" name="instrumentID" id="instrumentID">
                            </div>
                            <div class="mb-4">
                                <label class="form-label">Nama instrument</label>
                                <input readonly class="form-control" name="instrumentName" id="instrumentName">
                            </div>
                            <div class="mb-4">
                                <label class="form-label">Tanggal kalibrasi</label>
                                <input readonly class="form-control" name="instrumentCalibrate" type="date" id="instrumentCalibrate">
                            </div>
                        </div>
                        <div style="width: 100%; display: none" id="instrumentReader"></div>
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

    <script src="{{ asset('assets/js/scanqr.js') }}"></script>

    <script>
        var QrCodeScannerSample = new Html5QrcodeScanner(
            "sampleReader", {
                fps: 10,
                qrbox: 250
            });
        QrCodeScannerSample.render(onScanSampleSuccess);

        function onScanSampleSuccess(decodedText, decodedResult) {
            console.log('sample')
            $.ajax({
                url: "/show/sample/analitycs/" + decodedText,
                type: 'GET',
                success: function(res) {
                    var data = res.data
                    const no_sample = data.no_sample.split("-")

                    $('#sampleReader').css('display', 'none')
                    $('#sampleData').css('display', 'block')

                    $('#sampleType').val(data.type)
                    $('#sampleIDSection1').val(no_sample[0])
                    $('#sampleIDSection2').val(no_sample[1])
                    $('#sampleIDSection3').val(no_sample[2])
                    $('#sampleIDSection4').val(no_sample[3])
                    $('#sampleDesc').val(data.deskripsi_sample)
                    $('#sampleNoBatch').val(data.no_batch)
                    $('#sampleDateIn').val(data.tanggal_terima)
                    $('#sampleParameterUji').val(data.parameter_testing.parameter_uji)

                    $('#instrumentReader').css('display', 'block')
                    QrCodeScannerSample.clear()
                }
            });
        }

        var QrCodeScannerInstrument = new Html5QrcodeScanner(
            "instrumentReader", {
                fps: 10,
                qrbox: 250
            });
        QrCodeScannerInstrument.render(onScanInstrumentSuccess);

        function onScanInstrumentSuccess(decodedText, decodedResult) {
            console.log('instrument')
            console.log(`Scan result: ${decodedText}`, decodedResult);
            $.ajax({
                url: "/show/instrument/analitycs/" + decodedText,
                type: 'GET',
                success: function(res) {
                    var data = res.data

                    $('#instrumentReader').css('display', 'none')
                    $('#instrumentID').val(data.id_instrument)
                    $('#instrumentName').val(data.nama_instrument)
                    $('#instrumentCalibrate').val(data.tanggal_kalibrasi)
                    $('#instrumentData').css('display', 'block')

                    QrCodeScannerInstrument.clear()
                }
            });
        }
    </script>
@endsection
