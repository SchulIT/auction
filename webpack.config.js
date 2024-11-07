const Encore = require('@symfony/webpack-encore');
const GlobImporter = require('node-sass-glob-importer');

// Manually configure the runtime environment if not already configured yet by the "encore" command.
// It's useful when you use tools that rely on webpack.config.js file.
if (!Encore.isRuntimeEnvironmentConfigured()) {
    Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}

Encore
    .setOutputPath('public/build/')
    .setPublicPath('/build')

    .addEntry('app', './assets/js/app.js')
    .addStyleEntry('simple', './assets/css/simple.scss')
    .addStyleEntry('admin', './assets/css/admin.scss')

    .configureBabel(() => {}, {
        useBuiltIns: 'usage',
        corejs: 3
    })

    .disableSingleRuntimeChunk()
    .cleanupOutputBeforeBuild()
    .enableSourceMaps(!Encore.isProduction())

    .enableSassLoader(function(options) {
        options.sassOptions.importer = GlobImporter();
    })
    .enablePostCssLoader()
    .enableVersioning(Encore.isProduction());

module.exports = Encore.getWebpackConfig();
