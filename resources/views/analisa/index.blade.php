@extends('layouts.master')

@section('head')
    <link href="{{ asset('assets/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="container-fluid">

        <div class="row">
            <!-- Earnings (Monthly) Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Sample Dianalisa</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $inreview_sample }}</div>
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
                                    Sample Selesai</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $done_sample }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-arrow-up fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-grid gap-2 col-12 mx-auto">
            <button href="#" class="btn btn-primary shadow-sm" data-toggle="modal" data-target="#scanInModal">
                <i class="fas fa-arrow-down fa-sm text-white-50"></i>
                Scan Sample
            </button>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Sample</th>
                            <th>Deskripsi</th>
                            <th>No Batch</th>
                            <th>Tanggal Terima</th>
                            <th>Leadtime</th>
                            <th>PIC</th>
                            <th>History</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>Sample</th>
                            <th>Deskripsi</th>
                            <th>No Batch</th>
                            <th>Tanggal Terima</th>
                            <th>Leadtime</th>
                            <th>PIC</th>
                            <th>History</th>
                            <th></th>
                        </tr>
                    </tfoot>
                    <tbody>
                        @foreach ($analytics as $analytic)
                            <tr>
                                <td>{{ $analytic->TypeTesting->type }} - {{ $analytic->no_sample }}</td>
                                <td>{{ $analytic->deskripsi_sample }}</td>
                                <td>{{ $analytic->no_batch }}</td>
                                <td> {{ Date('d M Y - H:i', strtotime($analytic->tanggal_terima)) }}</td>
                                <td> {{ Date('d M Y - H:i', strtotime($analytic->tenggat_testing)) }}</td>
                                <td>{{ $analytic->PIC->name }}</td>
                                <td>
                                    <button href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"
                                        data-toggle="modal" data-target="#historySample{{ $analytic->id }}">
                                        <i class="fas fa-book fa-sm text-white-50"></i>
                                        History
                                    </button>
                                </td>
                                <td>
                                    <button href="#"
                                        class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm ml-3"
                                        data-toggle="modal" data-target="#scanInModal"
                                        onclick="scanDoneSample({{ $analytic->id }})" data-type="done">
                                        <i class="fas fa-barcode fa-sm text-white-50"></i> <br>
                                        Scan Done
                                    </button>
                                </td>
                            </tr>

                            <div class="modal fade" id="historySample{{ $analytic->id }}" tabindex="-1" role="dialog"
                                aria-labelledby="historySample{{ $analytic->id }}" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="historySample{{ $analytic->id }}">History
                                            </h5>
                                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">x</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <ul>
                                                @foreach ($analytic->Analytics as $history)
                                                    <li>
                                                        @if ($history->scan_in)
                                                            <b>{{ $history->scan_in }}</b> by {{ $history->PIC->name }}
                                                            @if ($history->status == 'In Review')
                                                                <span class="text-warning">
                                                                    <i>({{ $history->status }})</i>
                                                                </span>
                                                            @endif
                                                            <br>
                                                            {{ $history->Sample->no_sample }}
                                                            <span class="fas fa-arrow-right text-info"></span>
                                                            {{ $history->Instrument->nama_instrument }}
                                                        @elseif($history->scan_out)
                                                            <b>{{ $history->scan_out }}</b> by {{ $history->PIC->name }}
                                                            @if ($history->status == 'In Review')
                                                                <span class="text-warning">
                                                                    <i>({{ $history->status }})</i>
                                                                </span>
                                                            @endif
                                                            <br>
                                                            {{ $history->Instrument->nama_instrument }}
                                                            <span class="fas fa-arrow-right text-danger"></span>
                                                            {{ $history->Sample->no_sample }}
                                                        @elseif($history->scan_done)
                                                            <b>{{ $history->scan_out }}</b> by {{ $history->PIC->name }}
                                                            @if ($history->status == 'Done')
                                                                <span class="text-success">
                                                                    <i>({{ $history->status }})</i>
                                                                </span>
                                                            @endif
                                                            <br>
                                                            {{ $history->Instrument->nama_instrument }}
                                                            <span class="fas fa-arrow-right text-success"></span>
                                                            {{ $history->Sample->no_sample }}
                                                        @endif
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="scanInModal" tabindex="-1" role="dialog" aria-labelledby="scanInModal" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="scanInModalTitle">Scan In Sample</h5>
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
                                <input type="hidden" name="id_sample" id="sampleId">
                                <div class="row">
                                    <div class="col-3">
                                        <input readonly class="form-control" name="sampleIDSection1"
                                            id="sampleIDSection1">
                                    </div>
                                    <div class="col-9">
                                        <input readonly class="form-control" name="sampleIDSection2"
                                            id="sampleIDSection2">
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
                                <input readonly class="form-control" name="sampleDateIn" type="datetime-local"
                                    id="sampleDateIn">
                            </div>
                            <div class="mb-4">
                                <label class="form-label">Parameter uji</label>
                                <input readonly class="form-control" name="sampleParameterUji" id="sampleParameterUji">
                            </div>
                        </div>
                        <div id="instrumentData" style="display: none">
                            <input type="hidden" name="id_instrument" id="idInstrument">
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
                                <input readonly class="form-control" name="instrumentCalibrate" type="date"
                                    id="instrumentCalibrate">
                            </div>
                        </div>
                        <div style="width: 100%; display: none" id="instrumentReader"></div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                        <div id="scan_in_button" style="display: none">
                            <button type="submit" value="scan_in" name="scan_type" class="btn btn-success">
                                Scan In
                            </button>
                        </div>
                        <div id="scan_out_button" style="display: none">
                            <button type="submit" value="scan_out" name="scan_type" class="btn btn-info">
                                Scan Out
                            </button>
                        </div>
                        <div id="scan_done_button" style="display: none">
                            <button type="submit" value="scan_done" name="scan_type" class="btn btn-info">
                                Scan Done
                            </button>
                        </div>
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        var QrCodeScannerSample = new Html5QrcodeScanner(
            "sampleReader", {
                fps: 10,
                qrbox: 250
            });
        QrCodeScannerSample.render(onScanSampleSuccess);

        function onScanSampleSuccess(decodedText, decodedResult) {
            $.ajax({
                url: "/show/sample/analitycs/" + decodedText,
                type: 'GET',
                success: function(res) {
                    const data = res.data

                    if (data.status == 'Done') {
                        Swal.fire({
                            title: "Info !",
                            text: "This sample already done",
                            icon: "info"
                        });
                        return
                    }

                    if (data.analytics.length > 0) {
                        if (data.analytics[0].scan_in) {
                            onScanInstrumentSuccess(data.analytics[0].instrument.qr_code, null, data
                                .analytics)
                            console.log('here 1')
                            $('#scan_out_button').css('display', 'block')
                            $('#scan_in_button').css('display', 'none')
                        } else {
                            const titleModal = $('#scanInModalTitle').html()
                            if (titleModal == 'Scan Done Sample') {
                                console.log('should leave')
                                Swal.fire({
                                    title: "Info !",
                                    text: "Please insert sample into instrument first",
                                    icon: "info"
                                });
                                return
                            }
                            $('#instrumentReader').css('display', 'block')
                        }
                    } else {
                        console.log('here 2')
                        $('#instrumentReader').css('display', 'block')
                    }



                    const no_sample = data.no_sample.split("-")

                    $('#sampleReader').css('display', 'none')
                    $('#sampleData').css('display', 'block')

                    $('#sampleType').val(data.type_testing.type)
                    $('#sampleId').val(data.id)
                    $('#sampleIDSection1').val(no_sample[0])
                    $('#sampleIDSection2').val(no_sample[1])
                    $('#sampleIDSection3').val(no_sample[2])
                    $('#sampleIDSection4').val(no_sample[3])
                    $('#sampleDesc').val(data.deskripsi_sample)
                    $('#sampleNoBatch').val(data.no_batch)
                    $('#sampleDateIn').val(data.tanggal_terima)
                    $('#sampleParameterUji').val(data.parameter_testing.parameter_uji)

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

        function onScanInstrumentSuccess(decodedText, decodedResult, sample) {
            $.ajax({
                url: "/show/instrument/analitycs/" + decodedText,
                type: 'GET',
                success: function(res) {
                    var data = res.data

                    $('#idInstrument').val(data.id)
                    $('#instrumentReader').css('display', 'none')
                    $('#instrumentID').val(data.id_instrument)
                    $('#instrumentName').val(data.nama_instrument)
                    $('#instrumentCalibrate').val(data.tanggal_kalibrasi)
                    $('#instrumentData').css('display', 'block')

                    if (!sample) {
                        $('#scan_in_button').css('display', 'block')
                    }

                    const titleModal = $('#scanInModalTitle').html()
                    if (titleModal == 'Scan Done Sample') {
                        console.log('here 3')
                        $('#scan_out_button').css('display', 'none')
                        $('#scan_done_button').css('display', 'block')
                    }

                }
            });
        }

        function scanDoneSample(id) {
            $('#scanInModalTitle').html('Scan Done Sample')

            $('#scanInModal').on("hidden.bs.modal", function() {
                console.log('here 4')

                $('#scan_done_button').css('display', 'none')

                $('#scanInModalTitle').html('Scan In Sample')
            });
        }
    </script>
@endsection
