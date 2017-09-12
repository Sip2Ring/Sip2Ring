<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('crm.site_title') }} - @yield('page_title')</title>
    <!-- Bootstrap Core CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ URL::asset('/') }}css/main.css" />
    <link rel="stylesheet" href="{{ URL::asset('/') }}css/jquery-ui.min.css" />
</head>
<body>
     <div ng-app="Sip2ringApp" class="mainContainer">
            <div class="navMenu-default"  ng-controller="dashboardController">
                @include('layouts.dashboard.header')
            </div>
            <div class="shrink leftMenu"  ng-controller="dashboardController">
                 @include('layouts.dashboard.leftbar')
            </div>
            <div class="contentContainer">
                @yield('content')
            </div>
        </div>
        <script src="{{ URL::asset('/') }}js/constant.js" type="text/javascript"></script>
        <script src="{{ URL::asset('/') }}js/jquery-3.2.1.min.js" type="text/javascript"></script>
        <script src="{{ URL::asset('/') }}js/jquery-migrate-1.4.1.js" type="text/javascript"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <script src="{{ URL::asset('/') }}js/jquery-ui.min.js" type="text/javascript"></script>
        <script src="{{ URL::asset('/') }}js/chart/highcharts.js" type="text/javascript"></script>
        <script src="{{ URL::asset('/') }}js/chart/modules/exporting.js" type="text/javascript"></script>
        <script src="{{ URL::asset('/') }}js/angular.min.js" type="text/javascript"></script>
        <script src="{{ URL::asset('/') }}js/view/app.js" type="text/javascript"></script>
        <script src="{{ URL::asset('/') }}js/filters/directive.js" type="text/javascript"></script>
        <script src="{{ URL::asset('/') }}js/controller/controllers.js" type="text/javascript"></script>
        <script src="{{ URL::asset('/') }}js/script.js" type="text/javascript"></script>
    @yield('footer')
    

</body>
</html>
