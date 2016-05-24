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
    var bpath = 'node_modules/bootstrap-sass/assets';
    var jsPath = 'resources/assets/js';
    mix.sass('app.scss')
        .copy(jsPath + '/jquery-1.12.3.min.js', 'public/js')
        .copy(bpath + '/fonts', 'public/fonts')
        .copy(bpath + '/javascripts/bootstrap.min.js', 'public/js');
    
    
    mix.scripts('main.js')
        .copy(jsPath + '/bootstrap3-typeahead.js', 'public/js')
        .copy(jsPath + '/navbar.js', 'public/js');
});
