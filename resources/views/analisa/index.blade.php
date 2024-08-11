@extends('layouts.master')

@section('head')
    <link href="{{ asset('assets/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
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
                Scan In Sample
            </button>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
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
                        {{-- {{ dd($analytics) }} --}}
                        @foreach ($analytics as $key => $analytic)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>
                                    {{ $analytic->Sample->TypeTesting->type }} - {{ $analytic->Sample->no_sample }} <br>
                                    Parameter : <b>{{ $analytic->Sample->ParameterTesting->parameter_uji }}</b> <br>
                                    Replikasi : <b>{{ $analytic->replication }}</b>
                                </td>
                                <td class="align-middle">{{ $analytic->Sample->deskripsi_sample }}</td>
                                <td class="align-middle">{{ $analytic->Sample->no_batch }}</td>
                                <td class="align-middle">
                                    {{ Date('d M Y - H:i', strtotime($analytic->Sample->tanggal_terima)) }}</td>
                                <td class="align-middle text-center">
                                    {{ Date('d M Y - H:i', strtotime($analytic->Sample->HistoryAnalytics($analytic->replication)[0]->leadtime)) }}
                                    @if (Date('Y-m-d', strtotime($analytic->Sample->HistoryAnalytics($analytic->replication)[0]->leadtime)) == Carbon\Carbon::now()->format('Y-m-d'))
                                        <img src="{{ asset('assets/img/warning-leadtime.png') }}" width="20">
                                    @endif
                                </td>
                                <td class="align-middle">{{ $analytic->PIC->name }}</td>
                                <td class="align-middle">
                                    <button href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"
                                        data-toggle="modal" data-target="#historySample{{ $analytic->id }}">
                                        <i class="fas fa-book fa-sm text-white-50"></i>
                                        History
                                    </button>
                                </td>
                                <td class="text-center">
                                    <div class="mb-3">
                                        <button href="#" class=" btn btn-sm btn-primary shadow-sm "
                                            data-toggle="modal" data-target="#scanInModal"
                                            onclick="scanOutSample({{ $analytic->sample_id }}, {{ $analytic->instrument_id }}, '{{ $analytic->replication }}')"
                                            data-type="done">
                                            Scan Out
                                        </button>
                                    </div>
                                    <div class="">
                                        <button href="#" class=" btn btn-sm btn-primary shadow-sm "
                                            data-toggle="modal" data-target="#scanInModal"
                                            onclick="scanDoneSample({{ $analytic->sample_id }}, {{ $analytic->instrument_id }}, '{{ $analytic->replication }}')"
                                            data-type="done">
                                            Scan Done
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <div class="modal fade" id="historySample{{ $analytic->id }}" tabindex="-1" role="dialog"
                                aria-labelledby="historySample{{ $analytic->id }}" aria-hidden="true">
                                <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="historySample{{ $analytic->id }}">History
                                            </h5>
                                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">x</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row justify-content-between align-items-center border-bottom p-2">
                                                <div class="col-2">
                                                    <h6>Item</h6>
                                                </div>
                                                <div class="col-2">
                                                    <h6>Scan In</h6>
                                                </div>
                                                <div class="col-2">
                                                    <h6>Scan Out</h6>
                                                </div>
                                                <div class="col-2">
                                                    <h6>Scan Done</h6>
                                                </div>
                                                <div class="col-2">
                                                    <h6>PIC</h6>
                                                </div>
                                                <div class="col-2">
                                                    <h6>Status</h6>
                                                </div>
                                            </div>
                                            @foreach ($analytic->Sample->HistoryAnalytics($analytic->replication) as $history)
                                                <div class="row justify-content-between align-items-center border-bottom p-1">
                                                    <div class="col-2">
                                                        <span>
                                                            {{ $history->Sample->no_sample }}
                                                        </span> <br>
                                                        <span>
                                                            {{ $history->Sample->no_batch }}
                                                        </span>
                                                        <hr>
                                                        <span>
                                                            {{ $history->Instrument->nama_instrument }}
                                                        </span>
                                                    </div>
                                                    <div class="col-2">
                                                        <span>{{ $history->scan_in }}</span>
                                                    </div>
                                                    <div class="col-2">
                                                        <span>{{ $history->scan_out }}</span>
                                                    </div>
                                                    <div class="col-2">
                                                        <span>{{ $history->scan_done }}</span>
                                                    </div>
                                                    <div class="col-2">
                                                        <span>{{ $history->PIC->name }}</span>
                                                    </div>
                                                    <div class="col-2">
                                                        <span>{{ $history->status }}</span>
                                                    </div>
                                                </div>
                                            @endforeach
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

    <div class="modal fade modalScan" id="scanInModal" tabindex="-1" role="dialog" aria-labelledby="scanInModal"
        aria-hidden="true">
        <div class="modal-dialog">
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
                        <div id="anotherInputPreScan" style="display: none">
                            <div class="mb-4" style="display: none" id="temperatureInput">
                                <label class="form-label">Temperature</label>
                                <div class="input-group mb-4">
                                    <input type="number" min="1" name="temperature" id="temperatureVal"
                                        class="form-control" aria-describedby="basic-addon2">
                                    <span class="input-group-text" id="basic-addon2">&#8451;</span>
                                </div>
                            </div>
                            <div class="mb-4" style="display: none" id="methodInput">
                                <label class="form-label">Metode</label>
                                <input class="form-control" id="methodVal" name="method">
                            </div>
                            <div class="mb-4" style="display: none" id="leadtimeInput">
                                <label class="form-label">Leadtime</label>
                                <div class="input-group mb-4">
                                    <input type="number" min="1" name="leadtime" class="form-control"
                                        aria-describedby="basic-addon2">
                                    <select name="leadtimeType" id="basic-addon2">
                                        <option value="jam">Jam</option>
                                        <option value="hari">Hari</option>
                                    </select>
                                </div>
                            </div>
                            <div class="mb-4" style="display: none" id="replicationInput">
                                <label class="form-label">Replikasi</label> <br>
                                {{-- <input type="text" name="replication" id="replicationVal" class="form-control"> --}}
                                <select class="tag-input" name="replication" multiple="multiple">
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-dismiss="modal"
                            onclick="return window.location.reload()">Cancel</button>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

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
        var analyticId;
        var sampleIdVar;
        var replicationVar;
        var instrumentVar;
        var method = 'scan_in';

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
                data: {
                    replication: replicationVar,
                    method: method,
                    sampleId: sampleIdVar,
                    instrumentId: instrumentVar,
                },
                success: function(res) {
                    const data = res.data

                    const titleModal = $('#scanInModalTitle').html()

                    // if (data.status == 'Done') {
                    //     Swal.fire({
                    //         title: "Info !",
                    //         text: "This sample already done",
                    //         icon: "info"
                    //     });
                    //     return
                    // }
                    console.log(data)
                    if (titleModal === "Scan Out Sample" || titleModal === "Scan Done Sample") {
                        if (data.analytics[0].scan_in) {
                            onScanInstrumentSuccess(data.analytics[0].instrument.qr_code, null, data
                                .analytics)
                            if (titleModal === 'Scan Out Sample') {
                                $('#scan_out_button').css('display', 'block')
                            }
                            $('#scan_in_button').css('display', 'none')
                        } else {
                            // if (titleModal == 'Scan Done Sample') {
                            Swal.fire({
                                title: "Info !",
                                text: "Please insert sample into instrument first",
                                icon: "info"
                            });
                            return
                            // }
                            // $('#instrumentReader').css('display', 'block')
                        }
                    } else {
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

                    if (data.parameter_testing.is_microba) {
                        $('#methodInput').css("display", "block")
                        $('#leadtimeInput').css("display", "block")
                    }

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
                    instrumentVar = data.id
                    $('#idInstrument').val(data.id)
                    $('#instrumentReader').css('display', 'none')
                    $('#instrumentID').val(data.id_instrument)
                    $('#instrumentName').val(data.nama_instrument)
                    $('#instrumentCalibrate').val(data.tanggal_kalibrasi)
                    $('#instrumentData').css('display', 'block')
                    $('#anotherInputPreScan').css('display', 'block')

                    if (!sample) {
                        $('#scan_in_button').css('display', 'block')
                    }

                    if (sample) {
                        $('#temperatureVal')
                            .attr("readonly", true)
                            .val(sample[0].temperature)
                        $('#methodVal')
                            .attr("readonly", true)
                            .val(sample[0].method)
                        $('#leadtimeInput').css("display", "none")
                    }

                    const titleModal = $('#scanInModalTitle').html()

                    if (titleModal == 'Scan Done Sample') {
                        $('#scan_out_button').css('display', 'none')
                        $('#scan_done_button').css('display', 'block')
                    } else if (titleModal == 'Scan In Sample') {
                        $('#temperatureInput').css('display', 'block')
                        $('#replicationInput').css('display', 'block')
                    } else if (titleModal === 'Scan Out Sample') {
                        $('#scan_in_button').css('display', 'none')
                        $('#scan_out_button').css('display', 'block')
                    }
                    console.log(data.id)
                    getReplication(data.id, $('#sampleId').val(), 'sample')
                }
            });
        }

        function getReplication(instrumentID, sampleID, type) {
            $('.tag-input').select2({
                tags: true,
                ajax: {
                    url: '/analytic/replication', // URL to fetch existing tags
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            q: params.term,
                            sampleID: sampleID,
                            instrumentID: instrumentID,
                            type: type
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: $.map(data, function(item) {
                                return {
                                    id: item,
                                    text: item
                                };
                            })
                        };
                    },
                    cache: true
                },
                placeholder: 'Add or select replication',
                minimumInputLength: 1,
                maximumSelectionLength: 1,
                width: '100%'
            });
        }

        function scanOutSample(sampleId, instrumentId, replication) {
            method = 'scan_out';
            replicationVar = replication
            sampleIdVar = sampleId;
            instrumentVar = instrumentId;

            $('#scanInModalTitle').html('Scan Out Sample')
        }

        function scanDoneSample(sampleId, instrumentId, replication) {
            method = 'scan_done';
            replicationVar = replication
            sampleIdVar = sampleId;
            instrumentVar = instrumentId;

            $('#scanInModalTitle').html('Scan Done Sample')
        }

        $('#scanInModal').on("hidden.bs.modal", function() {
            $('#scan_done_button').css('display', 'none')
            $('#scanInModalTitle').html('Scan In Sample')

            window.location.reload()
        });
    </script>
@endsection
