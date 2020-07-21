const mix = require('laravel-mix');

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

mix.webpackConfig({
    resolve: {
      extensions: ['.js', '.vue', '.json'],
      alias: {
        '@': __dirname + '/resources/js'
      },
    },
});

mix.disableNotifications();

mix.js('resources/js/app.js', 'public/js')
  .sass('resources/sass/app/app.scss', 'public/css')
  .sass('resources/sass/store/store.scss', 'public/css');

