var gulp = require('gulp');
var exec = require('gulp-exec');

gulp.task('test', function(){
  gulp.src('.')
    .pipe(exec('phpunit', {
      continueOnError: true,
    }))
    .pipe(exec.reporter())
});

gulp.task('default', function(){
  gulp.watch('./app/**/*.php', ['test']);
});
