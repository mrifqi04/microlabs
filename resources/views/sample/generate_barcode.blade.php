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
    <div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
        <div class="card shadow">
            <div id="elementBarcode">
                <div class="row">
                    <div class="col-4">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=1000x1000&data={{ $sample->qr_code }}"
                            alt="" width="100%">
                    </div>
                    <div class="col-8 mt-5">
                        <h1>{{ $sample->TypeTesting->type }}</h1>
                        <h1 id="barcodeName">{{ $sample->no_sample }}</h1>
                        <h1>{{ $sample->no_batch }}</h1>
                        <h1 id="barcodeNameType">{{ $sample->ParameterTesting->parameter_uji }}</h1>
                    </div>
                </div>
            </div>
            <a class="btn btn-success btn-sm mt-3" id="downloadBarcode">Download</a>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.js"></script>

    <script>
        const elementBarcode = $("#elementBarcode");

        $(document).ready(function() {
            const fileName = $('#barcodeName').html()
            const fileNameType = $('#barcodeNameType').html()

            html2canvas(elementBarcode, {
                useCORS: true,
                onrendered: function(canvas) {
                    var imageData = canvas.toDataURL("image/jpg");
                    var newData = imageData.replace(/^data:image\/jpg/,
                        "data:application/octet-stream");
                    $('#downloadBarcode').attr(
                        "download", fileName + "-" + fileNameType + ".jpg").attr("href", newData);
                }
            });
        });
    </script>
</body>

</html>
