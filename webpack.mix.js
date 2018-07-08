let mix = require('laravel-mix');

const ImageminPlugin     = require('imagemin-webpack-plugin').default;
const CopyWebpackPlugin  = require('copy-webpack-plugin');
const imageminMozjpeg    = require('imagemin-mozjpeg');

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

mix.autoload({
    'jquery': ['jQuery', '$']
});

mix.js('resources/assets/js/app.js', 'public/js')
    .extract(['jquery', 'materialize-css']);
mix.sass('resources/assets/sass/app.scss', 'public/css');

// if(mix.inProduction()) {
    mix.webpackConfig({
        plugins: [
            //Compress images
            new CopyWebpackPlugin([{
                from: 'resources/assets/images', // FROM
                to: 'images/', // TO
            }]),
            new ImageminPlugin({
                test: /\.(jpe?g|png|gif|svg)$/i,
                pngquant: {
                    quality: '65-80'
                },
                plugins: [
                    imageminMozjpeg({
                        quality: 65,
                        //Set the maximum memory to use in kbytes
                        maxmemory: 1000 * 512
                    })
                ]
            })
        ],
    });
// }

mix.version(['public/images']);

mix.version();

// mix.browserSync('citymatters.test');