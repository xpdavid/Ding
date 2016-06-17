var elixir = require('laravel-elixir');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */

elixir(function(mix) {
    var bootstrapPath = 'resources/assets/bootstrap';
    var sweetalertPath = 'resources/assets/sweetalert';
    var select2Path = 'resources/assets/select2'

    var cssPath = 'resources/assets/js';
    var jsPath = 'resources/assets/js';

    // for bootstrap
    mix.copy(bootstrapPath + '/fonts', 'public/fonts')
        .copy(bootstrapPath + '/css', 'public/css')
        .copy(bootstrapPath + '/js', 'public/js');

    // for sweetalert
    mix.copy(sweetalertPath + '/sweetalert.css', 'public/css')
        .copy(sweetalertPath + '/sweetalert.min.js', 'public/js');

    // for select2
    mix.copy(select2Path + '/css', 'public/css')
        .copy(select2Path + '/js', 'public/js');
    
    // for project css file
    mix.sass('app.scss');


    // for project js file
    mix.scripts('main.js')
        .copy(jsPath + '/jquery-1.12.3.min.js', 'public/js')
        .copy(jsPath + '/bootstrap3-typeahead.js', 'public/js')
        .copy(jsPath + '/validator.js', 'public/js')
        .copy(jsPath + '/navbar.js', 'public/js')
        .copy(jsPath + '/login.js', 'public/js')
        .copy(jsPath + '/inbox.js', 'public/js')
        .copy(jsPath + '/profile.js', 'public/js');
});
