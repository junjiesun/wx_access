var gulp = require('gulp');

var jshint = require('gulp-jshint');		// JSHint plugin for gulp
// var minify = require('gulp-minify');		// Minify JavaScript with UglifyJS2
var rename = require('gulp-rename');		// gulp-rename is a gulp plugin to rename files easily
var uglify = require("gulp-uglify");		// Minify files with UglifyJS
var sass = require('gulp-sass');			// Gulp plugin for sass
var concat = require('gulp-concat');		// Concatenates files
var minifyCss = require('gulp-minify-css');	// Minify css with clean-css
var less = require('gulp-less');			// A LESS plugin for Gulp

gulp.task('lint', function() {
    return gulp.src('webstore/community.putao.com/kerisy/application/static/js/community/*.js')
        .pipe(jshint())
        .pipe(jshint.reporter('default'));
});

gulp.task('scripts', function(){
    return gulp.src('webstore/community.putao.com/kerisy/application/static/js/community/*.js')
        .pipe(concat('all.min.js'))
        .pipe(uglify())
        .pipe(gulp.dest('dist'));
});

gulp.task('css', function(){
	return gulp.src('webstore/community.putao.com/kerisy/application/static/css/*.css')
    	.pipe(concat('all.min.css'))
    	.pipe(minifyCss())
    	.pipe(gulp.dest('dist'));
});

// Watch Files For Changes
/*gulp.task('watch', function() {
    gulp.watch('webstore/community.putao.com/kerisy/application/static/js/community/*.js', ['lint', 'scripts']);
});*/

// gulp.task('default', ['lint', 'scripts', 'css', 'watch']);
gulp.task('default', ['lint', 'scripts', 'css']);