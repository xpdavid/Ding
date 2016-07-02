@extends('layouts.settings')

@section('content')
    @include('partials._user_settings_nav')

    <div class="container userSetting_content" >
        <form class="form-horizontal" method="POST" action="/settings/update" data-toggle="validator" id="form4">
            {{ csrf_field() }}
            <div class="row userSetting_section">
                <div class="col-md-8">
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Block User</label>
                        <div class="col-sm-5">
                            {!! Form::select('block_users[]', [], null, ['class' => 'form-control', 'id' => 'block_users', 'multiple']) !!}
                        </div>
                        <div class="col-sm-5">
                            <div class="userSetting_info">
                                You will not see anything about these user
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10 userSetting_Item">
                            @foreach ($user->blockings as $blocking)
                                <div>
                                    <a href="/people/{{ $blocking->url_name }}">{{ $blocking->name }}</a>
                                    (<a onclick="cancelBlocking(event, '{{ $blocking->id }}')" class="text-danger">Cancel</a>)
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <div class="row userSetting_section">
                <div class="col-md-8">
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <button type="submit" class="btn btn-primary" id="btn_form4">Save</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

@endsection

@section('javascript')
    <script type="text/javascript">
        $(function() {
            user_name_autocomplete('block_users');
        });
    </script>
@endsection