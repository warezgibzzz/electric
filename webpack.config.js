// webpack.config.js
var Encore = require('@symfony/webpack-encore');

Encore
    .setOutputPath('web/assets/')
    .setPublicPath('/assets')
    .cleanupOutputBeforeBuild()

    .addEntry('app', './assets/js/app.js')

    .addStyleEntry('global', './assets/scss/global.scss')
    .enableSassLoader()
    .autoProvidejQuery()

    .enableSourceMaps(!Encore.isProduction())

// create hashed filenames (e.g. app.abc123.css)
// .enableVersioning()
;

module.exports = Encore.getWebpackConfig();