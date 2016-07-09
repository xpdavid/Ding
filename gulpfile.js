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
    var select2Path = 'resources/assets/select2';
    var cropperPath = 'resources/assets/cropper';

    var cssPath = 'resources/assets/css';
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

    // for cropper
    mix.copy(cropperPath + '/cropper.css', 'public/css');
    mix.copy(cropperPath + '/cropper.js', 'public/js');
    // for cropper canvas to blob support
    mix.copy(cropperPath + '/canvas-to-blob.js', 'public/js');

    // for jsdiff
    mix.copy(jsPath + '/jsdiff', 'public/js/jsdiff');

    // for tiny mce
    mix.copy(jsPath + '/tinymce', 'public/js/tinymce')
        .copy(jsPath + '/equation.js', 'public/js'); // copy equation support
    
    // for project css file
    mix.sass('app.scss');

    // handlebars requirement
    mix.copy(jsPath + '/handlebars/handlebars-latest.js', 'public/js');


    // for project js file
    mix.scripts('main.js')
        .copy(jsPath + '/jquery-1.12.3.min.js', 'public/js')
        .copy(jsPath + '/bootstrap3-typeahead.js', 'public/js')
        .copy(jsPath + '/validator.js', 'public/js')
        .copy(jsPath + '/navbar.js', 'public/js')
        .copy(jsPath + '/login.js', 'public/js')
        .copy(jsPath + '/inbox.js', 'public/js')
        .copy(jsPath + '/profile.js', 'public/js')
        .copy(jsPath + '/question.js', 'public/js')
        .copy(jsPath + '/topic.js', 'public/js')
        .copy(jsPath + '/userCenter.js', 'public/js')
        .copy(jsPath + '/highlight.js', 'public/js')
        .copy(jsPath + '/search.js', 'public/js')
        .copy(jsPath + '/bookmark.js', 'public/js')
        .copy(jsPath + '/history.js', 'public/js');
});
