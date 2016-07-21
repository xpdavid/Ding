@extends('layouts.auth')

@section('content')
    @include('auth._login_register')
    {{--check if the url is login operation--}}
    <script type="text/javascript">
        // equivalent to jQuery $(function() {});
        document.addEventListener("DOMContentLoaded", function(event) {
            $('a[href="#signin"]').click();
        });
    </script>
@endsection
