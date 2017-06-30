var css = [
    './public/bower_components/bootstrap/dist/css/bootstrap.min.css',
    './public/bower_components/jqueryui/themes/base/jquery-ui.min.css',
    './public/css/style.css'
];

var js  = [
    './public/bower_components/jquery/dist/jquery.min.js',
    './public/bower_components/bootstrap/dist/js/bootstrap.min.js',
    './public/bower_components/jqueryui/jquery-ui.min.js',
    './public/js/app.js',
];
 
var gulp = require('gulp');
var uglify = require("gulp-uglify");
var concat = require("gulp-concat");
var watch = require('gulp-watch'); 
 
var cssmin = require("gulp-cssmin");
var stripCssComments = require('gulp-strip-css-comments');
 
gulp.task('minify-css', function(){
    gulp.src(css)
    .pipe(concat('style.min.css'))
    .pipe(stripCssComments({all: true}))
    .pipe(cssmin())
    .pipe(gulp.dest('./public/css/'));
});
 
gulp.task('minify-js', function () {
    gulp.src(js)                        
    .pipe(concat('script.min.js'))      
    .pipe(uglify())                     
    .pipe(gulp.dest('./public/js/')); 
});
 
gulp.task('default',['minify-js','minify-css']);
 
gulp.task('watch', function() {
    gulp.watch(js, ['minify-js']);
    gulp.watch(css, ['minify-css']);
});