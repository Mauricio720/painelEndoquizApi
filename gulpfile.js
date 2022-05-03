const {src,dest,series, parallel,watch}=require('gulp');
const rename=require('gulp-rename');
const uglify=require('gulp-uglify-es').default;
const uglifycss=require('gulp-uglifycss');
const concat=require('gulp-concat');

function javascript(){
    return src('resources/js/painel/*.js')
        .pipe(uglify())  
        .pipe(rename({'extname':'.min.js'})) 
        .pipe(dest('public/js/painel'));
}

function css(){
    return src('resources/css/painel/*.css')
        .pipe(uglifycss(
            {
                'max-length':80,
                'uglyComments':true
            }
        ))    
        .pipe(rename({'extname':'.min.css'}))
        .pipe(dest('public/css/painel'));
}


function watchFileCss(){
    return  watch('resources/css/painel/*.css', series(css, javascript));   
}

function watchFileJs(){
    return  watch('resources/js/painel/*.js', series(css, javascript));   
}


exports.default=parallel(javascript,css, watchFileCss,watchFileJs);