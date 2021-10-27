const mix = require('laravel-mix');

const CopyWebpackPlugin = require('copy-webpack-plugin');
const ImageminPlugin = require('imagemin-webpack-plugin').default;
const imageminMozjpeg = require('imagemin-mozjpeg');

require('laravel-mix-webp');
require("laravel-mix");


mix
    .js('resources/js/slider.js', 'public/js/slider.js')
    .js('resources/js/cardRotate.js', 'public/js/cardRotate.js')
    .js('resources/js/cardMove.js', 'public/js/cardMove.js')
    .js('resources/js/moveOther.js', 'public/js/moveOther.js')
    .postCss('resources/css/main.css', 'public/css')
    .options({
        postCss: [
            require('postcss-sort-media-queries')
        ]
    })
    .webpackConfig({
        plugins: [
            new CopyWebpackPlugin({
                patterns: [
                    {
                        from: 'resources/img',
                        to: 'img'
                    }
                ]
            }),

            new ImageminPlugin({
                    test: /\.(jpe?g|png|svg)$/i,
                    plugins: [
                        imageminMozjpeg({
                            quality: 80,
                            progressive: true
                        })
                    ]
                }
            )
        ]
    })
    .ImageWebp({
        from: 'resources/img',
        to: 'public/img',
        imageminWebpOptions: {
            quality: 70
        }
    })
    .version();
