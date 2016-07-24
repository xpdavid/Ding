@extends('layouts.auth')

@section('content')
    @include('auth._login_register')
@endsection

@section('footer')
    <script type="text/javascript">
        $(function() {
            $('a[href="#signin"]').click();
        });
    </script>
@endsection
