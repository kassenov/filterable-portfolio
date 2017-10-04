const gulp = require('gulp');
const sass = require('gulp-sass');
const concat = require('gulp-concat');
const uglify = require('gulp-uglify');

gulp.task('scss', function () {
    gulp.src('./assets/scss/*.scss')
        .pipe(sass({
            outputStyle: 'compressed'
        }).on('error', sass.logError))
        .pipe(gulp.dest('./assets/css'));
});

gulp.task('js-admin', function () {
    gulp.src('./assets/js/admin/*.js')
        .pipe(concat('admin.js'))
        .pipe(gulp.dest('./assets/js'))
        .pipe(concat('admin.min.js'))
        .pipe(uglify())
        .pipe(gulp.dest('./assets/js'));
});

gulp.task('js-public', function () {
    gulp.src('./assets/js/public/*.js')
        .pipe(concat('script.js'))
        .pipe(gulp.dest('./assets/js'))
        .pipe(concat('script.min.js'))
        .pipe(uglify())
        .pipe(gulp.dest('./assets/js'));
});

gulp.task('watch', function () {
    gulp.watch('./assets/scss/*.scss', ['scss']);
    gulp.watch('./assets/js/admin/*.js', ['js-admin']);
    gulp.watch('./assets/js/public/*.js', ['js-public']);
});

gulp.task('default', ['scss', 'js-admin', 'js-public', 'watch']);