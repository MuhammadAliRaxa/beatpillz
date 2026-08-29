<!DOCTYPE html>
<html lang="{{ getLocale() }}">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>{{ @$settings->maintenance->title }}</title>
    <link rel="shortcut icon" href="{{ asset(@$settings->maintenance->icon) }}">
    <link rel="stylesheet" href="{{ asset('vendor/libs/bootstrap/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/libs/fontawesome/fontawesome.min.css') }}">
    <style>
        .card {
            width: 100%;
        }

        .logo {
            height: 50px;
        }

        .logo img {
            height: 100%;
        }
    </style>
</head>

<body class="bg-light d-flex justify-content-center align-items-center" style="height: 100vh;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm rounded-4 text-center">
                    <div class="card-body p-5">
                        <div class="my-5">
                            <div class="logo mb-5">
                                <img src="{{ asset($settings->maintenance->icon) }}"
                                    alt="{{ @$settings->maintenance->title }}">
                            </div>
                            <h1 class="mb-3">{{ @$settings->maintenance->title }}</h1>
                            <p>{{ @$settings->maintenance->body }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('vendor/libs/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/libs/bootstrap/bootstrap.bundle.min.js') }}"></script>
</body>

</html>
