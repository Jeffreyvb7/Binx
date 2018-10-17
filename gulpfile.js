'use strict';

var gulp = require('gulp');
var sass = require('gulp-sass');
var sourcemaps = require('gulp-sourcemaps');
var copyAssets = require('gulp-css-copy-assets');

const scssDir = './resources/assets/scss';
const scssPath = scssDir + '/**/*.scss';
const cssDist = './public/assets/css';

gulp.task('scss', function () {
    return gulp.src(scssPath)
        .pipe(sourcemaps.init())
        .pipe(sass({outputStyle: 'compressed'}).on('error', sass.logError))
        .pipe(sourcemaps.write('.'))
        .pipe(copyAssets.default({
            srcdirs: [scssDir]
        }))
        .pipe(gulp.dest(cssDist));
});

gulp.task('scss:watch', function () {
    gulp.watch(scssPath, ['scss']);
});

gulp.task('default', [ 'scss' ]);
gulp.task('dev', [ 'scss:watch' ]);