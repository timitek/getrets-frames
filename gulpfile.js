var gulp = require('gulp');
var uglify = require('gulp-uglify');
var sass = require('gulp-sass');


gulp.task('js', function() {
    gulp.src('./resources/assets/js/*.js')
      .pipe(gulp.dest('./public/js'));
      /*
      .pipe(uglify())
      .pipe(gulp.dest('./public/js'));
      */
});

gulp.task('sass', function () {
  return gulp.src('./resources/assets/sass/*.scss')
    .pipe(sass().on('error', sass.logError))
    .pipe(gulp.dest('./public/css'));
});

gulp.task('assets', function () {
  gulp.src(['./resources/assets/flexslider/**/*'])
    .pipe(gulp.dest('./public/flexslider'));
  gulp.src(['./resources/assets/css/**/*'])
    .pipe(gulp.dest('./public/css'));
  gulp.src(['./resources/assets/images/**/*'])
    .pipe(gulp.dest('./public/images'));
});

gulp.task('default', ['js', 'sass', 'assets']);
