const gulpfile = require('gulp');
const sass = require('gulp-sass');
const del = require('del');

gulpfile.task('styles', () => {
    return gulpfile.src('sass/**/*.scss')
        .pipe(sass().on('error', sass.logError))
        .pipe(gulpfile.dest('css'));
});
gulpfile.task('watch', () => {
    gulpfile.watch('sass/**/*.scss', (done) => {
        gulpfile.series(['styles'])(done);
    });
});

gulpfile.task('default', gulpfile.series(['watch', 'styles']));