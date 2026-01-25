<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>
    <link rel="stylesheet" as="style" crossorigin href="https://cdn.jsdelivr.net/gh/orioncactus/pretendard@v1.3.9/dist/web/variable/pretendardvariable.min.css" />
    <link rel="stylesheet" href="{{ asset('css/reset.css') }}">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <!-- 1. 토스 페이먼츠 SDK 추가 -->
    {{--<script src="https://js.tosspayments.com/v1/payment-widget"></script>--}}
{{--    <script src="https://js.tosspayments.com/v1/payment"></script>--}}
    <script type="text/javascript" src="https://cdn.portone.io/v2/browser-sdk.js"></script>
    <script type="text/javascript" charset="UTF-8" src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <script type="text/javascript" src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.js"></script>
</head>
<body>
<div class="e-cash @yield('body-class')">
    @include('partials.sidebar')
    @yield('content')
</div>
{{--@include('partials.layer')--}}
@if(session('error'))
<script>
    alert('{{ session('error') }}');
</script>
@endif
</body>
</html>
