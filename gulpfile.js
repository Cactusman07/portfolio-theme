// Gulp.js configuration
'use strict';

const

  // source and build folders
  dir = {
    src         : 'src/', // Source file path
    build       : 'C:/xampp/htdocs/sammuir.co.nz/wp-content/themes/portfolio/', // Build path
    localServer : 'localhost/sammuir.co.nz/' //Add your local server path here
  },

  // Gulp and plugins
  gulp          = require('gulp'),  
  newer         = require('gulp-newer'),
  imagemin      = require('gulp-imagemin'),
  autoprefixer  = require('gulp-autoprefixer'),
  concat        = require('gulp-concat'),
  csso          = require('gulp-csso'),
  less          = require('gulp-less'),
  uglify        = require('gulp-uglify'),
  notify        = require('gulp-notify'),
  plumber       = require('gulp-plumber')
;

const browserSync = require('browser-sync').create();
const reload = browserSync.reload;

// Set the browser that you want to support
const AUTOPREFIXER_BROWSERS = [
  'ie >= 10',
  'ie_mob >= 10',
  'ff >= 30',
  'chrome >= 34',
  'safari >= 7',
  'opera >= 23',
  'ios >= 7',
  'android >= 4.4',
  'bb >= 10'
];

// PHP settings
const php = {
  src           : dir.src + 'template/**/*.php',
  watch         : dir.src + 'template/**/**',
  build         : dir.build
};

// copy PHP files
gulp.task('php', () => {
  return gulp.src(php.src)
    .pipe(plumber({ errorHandler: function(err) {
        notify.onError({
            title: "Gulp error in " + err.plugin,
            message:  err.toString()
        })(err);
    }}))
    .pipe(newer(php.build))
    .pipe(gulp.dest(php.build))
    .pipe(notify({
      message: "PHP templates moved to build folder",
      title: "PHP Build Task Completed"
    }))
});

// image and font settings
const images = {
  src         : dir.src + 'images/**/*',
  build       : dir.build + 'images/'
};
const fonts = {
  src         : dir.src + 'fonts/**/*',
  build       : dir.build + 'fonts/'
};

// image processing
gulp.task('images', () => {
  return gulp.src(images.src)
    .pipe(plumber({ errorHandler: function(err) {
        notify.onError({
            title: "Gulp error in " + err.plugin,
            message:  err.toString()
        })(err);
    }}))
    .pipe(newer(images.build))
    .pipe(imagemin( { optimizationLevel: 5 /* , interlaced: true */ } ))
    .pipe(gulp.dest(images.build))
});

// fonts processing
gulp.task('fonts', () => {
  return gulp.src(fonts.src)
    .pipe(plumber({ errorHandler: function(err) {
        notify.onError({
            title: "Gulp error in " + err.plugin,
            message:  err.toString()
        })(err);
    }}))
    .pipe(newer(fonts.build))
    .pipe(gulp.dest(fonts.build))
});

// css settings
const css = {
  src         : dir.src + 'less/style.less',
  watch       : dir.src + 'less/**/**',
  build       : dir.build
};

// css processing
gulp.task('css', gulp.series('images', 'fonts', () => {
  return gulp.src(css.src)
  .pipe(plumber({ errorHandler: function(err) {
      notify.onError({
          title: "Gulp error in " + err.plugin,
          message:  err.toString()
      })(err);
  }}))
  .pipe(less({
    plugins: [autoprefixer({
      browsers: AUTOPREFIXER_BROWSERS,
      cascade: false
    })]
  }))
  .pipe(csso())
  .pipe(gulp.dest(css.build))
  .pipe(notify({
    message: "LESS compiled, minified and moved to build folder in style.css",
    title: "CSS Build Task Completed"
  }))
}));

// JavaScript settings
const js = {
  src         : dir.src + 'js/',
  build       : dir.build + 'js/',
  watch       : dir.src + 'js/**/**',
  filename    : 'app.min.js'
};

// Move non-JS files
gulp.task('json', () =>{
  return gulp.src(dir.src + 'js/particles.json')
    .pipe(gulp.dest(js.build))
});

// JavaScript processing - define list of JS files using format below
gulp.task('js', gulp.series('json', () =>{
  return gulp.src([ js.src + 'connecting-dot-particles.js',
                    js.src + 'matrix.js',
                    js.src + 'trail.js',
                    js.src + 'in-view.min.js',
                    js.src + 'app.js'
                  ])
    .pipe(plumber({ errorHandler: function(err) {
        notify.onError({
            title: "Gulp error in " + err.plugin,
            message:  err.toString()
        })(err);
    }}))
    .pipe(concat(js.filename))
    .pipe(uglify())
    .pipe(gulp.dest(js.build))
    .pipe(notify({
      message: "JS compiled, concatenated, minified and moved to build folder",
      title: "JS Build Task Completed"
    }))
}));

// run all tasks
gulp.task('build', gulp.series('php', 'css', 'js', (async) => {
  reload;
  async();
}));

// Initiate BrowserSync to start using local server URL
gulp.task('browser-sync', () => {
  browserSync.init({
    proxy   : dir.localServer,
    port    : 80,
    baseDir : dir.src,
    files   : ['*.php', '*.css', '**.*.js']
  });
});

// watch for changes to files
gulp.task('watch', gulp.series('browser-sync', () => {
  gulp.watch(css.watch, ['css', reload]);
  gulp.watch(php.watch, ['php', reload]);
  gulp.watch(images.src, ['images', reload]);
  gulp.watch(fonts.src, ['fonts', reload]);
  gulp.watch(js.watch, ['js', reload]);
}));

// Set default task to Watch, so you can start localhost work using command "gulp" only
gulp.task('default', gulp.series('watch'));