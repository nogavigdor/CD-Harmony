const gulp = require('gulp');
const exec = require('child_process').exec;

// Define a Gulp task to run 'npm run build'
gulp.task('build', (cb) => {
  exec('npm run build', (err, stdout, stderr) => {
    console.log(stdout);
    console.log(stderr);
    cb(err);
  });
});

// Define a Gulp task to watch for file changes and run 'build' task
gulp.task('watch', () => {
  gulp.watch(['index.php', 'src/css/input.css', 'src/css/custom.css'], gulp.series('build'));
});
