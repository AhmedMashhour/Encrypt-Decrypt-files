<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@200;600&display=swap" rel="stylesheet">

    <!-- Styles -->
    <style>
        html, body {
            background-color: #fff;
            color: #636b6f;
            font-family: 'Nunito', sans-serif;
            font-weight: 200;
            height: 100vh;
            margin: 0;
        }

        .full-height {
            height: 100vh;
        }

        .flex-center {
            align-items: center;
            display: flex;
            justify-content: center;
        }

        .position-ref {
            position: relative;
        }

        .top-right {
            position: absolute;
            right: 10px;
            top: 18px;
        }

        .content {
            text-align: center;
        }

        .title {
            font-size: 84px;
        }

        .links > a {
            color: #636b6f;
            padding: 0 25px;
            font-size: 13px;
            font-weight: 600;
            letter-spacing: .1rem;
            text-decoration: none;
            text-transform: uppercase;
        }

        .m-b-md {
            margin-bottom: 30px;
        }
    </style>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">

</head>
<body style="padding: 30px">
<div class="row">
    <div class="col-sm-6">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Encrypt File</h5>
                <form method="POST" action="{{ route('encrypt.encrypt-file') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1">File Name</span>
                        </div>
                        <input type="text" class="form-control" name="file_name" placeholder="File Name" aria-describedby="basic-addon1">
                    </div>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1">public/</span>
                        </div>
                        <input type="text" class="form-control" name="saving_path" placeholder="File Location: example/name of folder " aria-describedby="basic-addon1">

                    </div>
                    <div class="form-group form-check" id="file_details" style="display: none">
                        <p>
                            <span> file name = <span class="file_name"></span></span>

                        </p>
                        <p>
                            <span> file size = <span class="file_size"></span></span>
                        </p>
                        <p>
                            <span> file extension = <span class="file_extension"></span></span>
                            <input type="hidden" name="extension">
                        </p>
                    </div>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="inputGroupFileAddon01">Upload</span>
                        </div>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" name="encrypt_file" id="encrypt-file" aria-describedby="inputGroupFileAddon01">
                            <label class="custom-file-label" for="encrypt-file">Choose file</label>
                        </div>
                    </div>

                    @if(Session::get('flash_file_encryption'))
                        <p style="color: blue">
                            Your File Has been Encrypted stored at :{{ Session::get('flash_file_encryption') }}
                        </p>
                    @endif

                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Decrypt A file </h5>
                <form method="POST" action="{{ route('decrypt.decrypt-file') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1">File Name</span>
                        </div>
                        <input type="text" class="form-control" name="file_name" placeholder="File Name" aria-describedby="basic-addon1">
                    </div>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1">public/</span>
                        </div>
                        <input type="text" class="form-control" name="saving_path" placeholder="File Location: example/name of folder " aria-describedby="basic-addon1">

                    </div>
                    <div class="form-group form-check" id="file_details" style="display: none">
                        <p>
                            <span> file name = <span class="file_name"></span></span>

                        </p>
                        <p>
                            <span> file size = <span class="file_size"></span></span>
                        </p>
                        <p>
                            <span> file extension = <span class="file_extension"></span></span>
                            <input type="hidden" name="extension">
                        </p>
                    </div>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="inputGroupFileAddon01">Upload</span>
                        </div>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" name="encrypt_file" id="encrypt-file" aria-describedby="inputGroupFileAddon01">
                            <label class="custom-file-label" for="encrypt-file">Choose file</label>
                        </div>
                    </div>

                    @if(Session::get('flash_file_decryption'))
                        <p style="color: blue">
                            Your File Has been Decrypted File stored at :{{ Session::get('flash_file_decryption') }}
                        </p>
                    @endif

                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>
<script  src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
         crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js" integrity="sha384-+sLIOodYLS7CIrQpBjl+C7nPvqq+FbNUBDunl/OZv93DB7Ln/533i8e/mZXLi/P+" crossorigin="anonymous"></script>

<script>
    $(document).ready(function(){

        $('#encrypt-file').on('change',function (){
            $('#file_details').show();
            $('.file_size').html(this.files[0].size)
            $('.file_name').html($('#encrypt-file').val().split('\\')[2])
            $('.file_extension').html($('#encrypt-file').val().split('.').pop())
        })

    });
</script>
</body>
</html>

