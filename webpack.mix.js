let mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

// mix.js('resources/assets/js/app.js', 'public/js')
//    .sass('resources/assets/sass/app.scss', 'public/css');

mix.options({
    cleanCss: {
        level: {
            1: {
                specialComments: 'none'
            }
        }
    },
    purifyCss: true,
    processCssUrls: false
});

//Master theme scripts
mix.styles([
    'public/assets/plugins/bootstrap/css/bootstrap.min.css',
    'public/assets/plugins/morrisjs/morris.css'
], 'public/css/lib.css');

mix.styles([
    'public/css/style.css',
    'public/assets/plugins/sweetalert/sweetalert.css',
    'public/scss/icons/font-awesome/css/font-awesome.min.css',
    'public/scss/icons/simple-line-icons/css/simple-line-icons.css',
    'public/scss/icons/weather-icons/css/weather-icons.min.css',
    'public/scss/icons/linea-icons/linea.css',
    'public/scss/icons/themify-icons/themify-icons.css',
    'public/scss/icons/flag-icon-css/flag-icon.min.css',
    'public/scss/icons/material-design-iconic-font/css/materialdesignicons.min.css',
    'public/css/spinners.css',
    'public/css/animate.css',
], 'public/css/app.css');

mix.scripts([
    'https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js',
    'https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js',
], 'public/js/headscripts.js');

mix.scripts([
    'public/assets/plugins/jquery/jquery.min.js',
    'public/assets/plugins/jqueryui/jquery-ui.min.js',
    'public/assets/plugins/bootstrap/js/popper.min.js',
    'public/assets/plugins/bootstrap/js/bootstrap.min.js',
    'public/js/jquery.slimscroll.js',
    'public/js/waves.js',
    'public/js/sidebarmenu.js',
    'public/assets/plugins/sticky-kit-master/dist/sticky-kit.min.js',
    'public/js/jquery.nicescroll.min.js',
    'public/assets/plugins/sweetalert/sweetalert.min.js',
    'public/assets/plugins/moment/moment.js',
    'public/assets/plugins/css-element-queries-master/src/ResizeSensor.js',
    'public/assets/plugins/session-timeout/idle/jquery.idletimeout.js',
    'public/assets/plugins/session-timeout/idle/jquery.idletimer.js'
], 'public/js/all.js');

mix.copy('public/scss/icons/font-awesome/fonts', 'public/fonts');
mix.copy('public/scss/icons/simple-line-icons/fonts', 'public/fonts');
mix.copy('public/scss/icons/weather-icons/fonts', 'public/fonts');
mix.copy('public/scss/icons/linea-icons/fonts', 'public/fonts');
mix.copy('public/scss/icons/themify-icons/fonts', 'public/css/fonts');
mix.copy('public/scss/icons/flag-icon-css/flags', 'public/css/flags');
mix.copy('public/scss/icons/material-design-iconic-font/fonts', 'public/fonts');
//End

//Daily report scripts
mix.styles([
    'public/assets/plugins/clockpicker/dist/jquery-clockpicker.min.css',
    'public/assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.css',
    'public/assets/plugins/x-editable/dist/bootstrap4-editable/css/bootstrap-editable.css',
    'public/assets/plugins/toast-master/css/jquery.toast.css',
], 'public/css/report.css');

mix.scripts([
    'public/assets/plugins/clockpicker/dist/jquery-clockpicker.min.js',
    'public/assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.js',
    'public/assets/plugins/x-editable/dist/bootstrap4-editable/js/bootstrap-editable.js',
    'public/assets/plugins/toast-master/js/jquery.toast.js',
    'public/js/mask.js'
], 'public/js/report.js');

mix.copy('public/assets/plugins/x-editable/dist/bootstrap4-editable/img', 'public/img');
//End

//Datatable scripts
mix.styles([
    'public/assets/plugins/bootstrap-datatable/css/buttons.dataTables.min.css',
], 'public/css/datatable.css');

mix.scripts([
    'public/assets/plugins/datatables/jquery.dataTables.min.js',
    'public/assets/plugins/bootstrap-datatable/js/dataTables.buttons.min.js',
    'public/assets/plugins/bootstrap-datatable/js/buttons.flash.min.js',
    'public/assets/plugins/bootstrap-datatable/js/jszip.min.js',
    'public/assets/plugins/bootstrap-datatable/js/pdfmake.min.js',
    'public/assets/plugins/bootstrap-datatable/js/vfs_fonts.js',
    'public/assets/plugins/bootstrap-datatable/js/buttons.html5.min.js',
    'public/assets/plugins/bootstrap-datatable/js/buttons.print.min.js',
    'public/assets/plugins/bootstrap-datatable/js/buttons.colVis.min.js',
    'public/vendor/datatables/buttons.server-side.js'
], 'public/js/datatable.js');
//End


if (mix.inProduction()) {
    mix.version();
}
