gulp    = require('gulp')
phpunit = require('gulp-phpunit')
notify  = require('gulp-notify')

gulp.task('test', ->
  gulp.src('./app/tests/*.php')
    .pipe(phpunit(null, {
      debug: false
      notify: true
      configurationFile: 'phpunit.xml'
    }))
    .on('error', notify.onError('Test Fail'))
    .pipe(notify({
      message: 'Test OK'
      onLast: true
    }))
)

gulp.task('default', ->
  gulp.watch('./app/**/*.php', ['test'])
)
