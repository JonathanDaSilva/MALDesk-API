var gulp = require('gulp');
var exec = require('gulp-exec');

gulp.task('test', function(){
  gulp.src('.').pipe(exec('phpunit'));
});

gulp.task('watch', function(){
  gulp.watch('./app/**/*.php', ['test']);
});
