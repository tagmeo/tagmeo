var elixir = require('laravel-elixir');

require('elixir-busting');
require('elixir-imagemin');
require('elixir-modernizr');

var src = {
  img: elixir.config.assetsPath + '/img',
  js: elixir.config.assetsPath + '/js',
  scss: elixir.config.assetsPath + '/scss'
};

var dist = {
  css: elixir.config.publicPath + '/css',
  fonts: elixir.config.publicPath + '/fonts',
  img: elixir.config.publicPath + '/img',
  js: elixir.config.publicPath + '/js'
};

var packages = {
  bootstrap: './node_modules/bootstrap-sass/assets',
  fontAwesome: './node_modules/font-awesome',
  html5shiv: './node_modules/html5shiv/dist',
  jquery: './node_modules/jquery/dist',
  respond: './node_modules/respond.js/dest'
};

var assetList = [];

for (var handle in elixir.config.assets) {
  for (var param in elixir.config.assets[handle]) {
    if (param === 'file') {
      if (!elixir.config.assets[handle].file.match(/^\.\/|\.\.\/|\/\/|https?:\/\//)) {
        var extension = elixir.config.assets[handle].file.substr(elixir.config.assets[handle].file.lastIndexOf('.') + 1);
        assetList.push(dist[extension] + '/' + elixir.config.assets[handle].file);
      }
    }
  }
}

elixir(function (mix) {
  // Copy fonts to distribution folder
  mix.copy(packages.bootstrap + '/fonts/bootstrap/*.+(eot|svg|ttf|woff|woff2)', dist.fonts);
  mix.copy(packages.fontAwesome + '/fonts/*.+(eot|svg|ttf|woff|woff2)', dist.fonts);

  // Optimize images
  mix.imagemin({
    multipass: true,
    progressive: true
  });

  // Our default stylesheet
  mix.sass([
    src.scss + '/app.scss'
  ], dist.css + '/app.css');

  // Our default script
  mix.scripts([
    src.js + '/app.js'
  ], dist.js + '/app.js');

  // IE8 support of HTML5 elements
  mix.scripts([
    packages.html5shiv + '/html5shiv.js',
    packages.respond + '/respond.src.js'
  ], dist.js + '/ie.js');

  // Add support for Customizer
  mix.scripts([
    src.js + '/customizer.js'
  ], dist.js + '/customizer.js');

  // Vendor scripts
  mix.scripts([
    packages.jquery + '/jquery.js',
    packages.bootstrap + '/javascripts/bootstrap.js'
  ], dist.js + '/vendor.js');

  // Build custom Modernizr script
  mix.modernizr([
    dist.css + '/**/*.css',
    dist.js + '/**/*.js'
  ], dist.js + '/modernizr.js');

  // Cache busting
  mix.busting(assetList);

  // Enable BrowserSync
  mix.browserSync({
    files: [
      'app/**/*',
      'public/assets/**/*',
      'public/mu-plugins/**/*',
      'public/plugins/**/*',
      'public/themes/**/*.php',
      'public/uploads/**/*'
    ]
  });
});
