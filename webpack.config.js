// webpack.config.js
var Encore = require('@symfony/webpack-encore');

Encore
    .setOutputPath('web/assets/')
    .setPublicPath('/assets')
    .cleanupOutputBeforeBuild()
    .addEntry('app', './assets/js/app.js')
    .addStyleEntry('global', './assets/scss/global.scss')
    .configureBabel(function(babelConfig) {
        babelConfig.presets.push('env');
    })
    .enableSassLoader()
    .autoProvidejQuery()
    .autoProvideVariables({
        $: 'jquery',
        jQuery: 'jquery',
        'window.jQuery': 'jquery',
        Popper: 'popper.js'
    })
    .enableSourceMaps(!Encore.isProduction())
    .enableVersioning(Encore.isProduction())
;

module.exports = Encore.getWebpackConfig();