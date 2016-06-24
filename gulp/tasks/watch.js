/**
 * Created by JPa on 2016-05-03.
 */
var gulp        = require('gulp');
var browserSync = require('browser-sync').create();

var config = require('../config');

// Static Server + watching scss/html files
gulp.task('watch-twig', function() {

    browserSync.init({
        proxy: "localhost:8000"
    });

    gulp.watch("app/Resources/views/**/*.html.twig").on('change', browserSync.reload);
});