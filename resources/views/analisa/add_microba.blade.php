<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Centered Form on Card</title>
    <!-- Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container d-flex justify-content-center align-items-center p-3" style="min-height: 100vh;">
        <div class="row">
            <div class="col-8">
                <div class="card shadow">
                    <div class="card-body">
                        <h5 class="card-title text-center">Detail Sample</h5>
                        <div class="modal-body">
                            <div class="mb-4">
                                <label for="">Type</label>
                                <input class="form-control" value="{{ $sample->TypeTesting->type }}" readonly
                                    name="no_batch" type="text">
                            </div>
                            <div class="mb-4">
                                <label for="">No ID Sample</label>
                                @php
                                    $no_sample = explode('-', $sample->no_sample);
                                @endphp
                                <div class="row">
                                    <div class="col-4">
                                        <input class="form-control" value="{{ $no_sample[0] }}" readonly name="section1"
                                            type="text">
                                    </div>
                                    <div class="col-8">
                                        <input class="form-control" value="{{ $no_sample[1] }}" name="section2"
                                            type="text" readonly>
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
                                <input class="form-control" value="{{ $sample->no_batch }}" readonly name="no_batch"
                                    type="text">
                            </div>
                            <div class="mb-4">
                                <label for="">Waktu di Terima QC</label>
                                <input class="form-control"
                                    value="{{ Date('d-m-Y, H:i', strtotime($sample->tanggal_terima)) }}" readonly
                                    name="tanggal_terima" type="text">
                            </div>
                            <div class="mb-4">
                                <label for="">Tenggat QC</label>
                                <input class="form-control"
                                    value="{{ Date('d-m-Y, H:i', strtotime($sample->tenggat_testing)) }}" readonly
                                    name="tenggat_testing" type="text">
                            </div>
                            <div class="mb-4">
                                <label for="">Jumlah Sample</label>
                                <input class="form-control" value="{{ $sample->jumlah_sampel }}" readonly
                                    name="jumlah_sampel" min="1" type="number">
                            </div>
                            <div class="mb-4">
                                <label for="">Parameter Uji</label>
                                <input class="form-control" value="{{ $sample->ParameterTesting->parameter_uji }}"
                                    readonly name="no_batch" type="text">
                            </div>
                            <div class="mb-4">
                                <label for="">PIC</label>
                                <input class="form-control" value="{{ $sample->PIC->name }}" readonly name="no_batch"
                                    type="text">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-4">
                <form action="{{ route('submitMicroba', [$sample->id, $instrument_id]) }}" method="post">
                    @csrf
                    <div class="card shadow mb-3" style="width: 100%; max-width: 700px;">
                        <div class="card-body mb-3">
                            <h5 class="card-title text-center mb-5">Detail Microba</h5>
                            <div class="mb-4">
                                @foreach ($parameters as $parameter)
                                    <div class=" mb-2">
                                        <div class="form-check">
                                            <input class="form-check-input" value="{{ $parameter->id }}"
                                                type="checkbox" name="parameter_testing_id[]"
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
                    <button class="btn btn-success btn-block" type="submit">Submit</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
