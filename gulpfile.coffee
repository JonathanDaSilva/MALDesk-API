gulp = require('gulp')
exec = require('gulp-exec')

gulp.task('test', ->
  gulp.src('.')
    .pipe(exec('phpunit', {
      continueOnError: true
    }))
    .pipe(exec.reporter())
)

gulp.task('default', ->
  gulp.watch('./app/**/*.php', ['test'])
)
