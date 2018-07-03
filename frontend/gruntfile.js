function ConfigProcessor(grunt)
{
    function mergeSassConfig(targetConfig, sourceConfig)
    {
        sourceConfig.files = sourceConfig.files || {};
        sourceConfig.paths = sourceConfig.paths || [];
        for(var k in sourceConfig.files) {
            targetConfig.files[k] = sourceConfig.files[k];
        }
        sourceConfig.paths.forEach(function(path){
            targetConfig.paths.push(path);
        });
    }

    function mergeBrowserifyConfig(targetConfig, sourceConfig)
    {

        sourceConfig.base_path = sourceConfig.base_path || "";

        sourceConfig.alias = sourceConfig.alias || {};
        sourceConfig.src = sourceConfig.src || [];
        sourceConfig.dest = sourceConfig.dest || undefined;

        function fixPath(path)
        {
            return sourceConfig.base_path + path;
        }
        for(var k in sourceConfig.alias) {
            targetConfig.alias[k] = fixPath(sourceConfig.alias[k]);
        }
        sourceConfig.src.forEach(function(src){
            src = fixPath(src);
            targetConfig.src.push(src);
        });

        if(sourceConfig.dest) {
            targetConfig.dest = sourceConfig.dest;
        }
    }

    function merge(targetConfig, sourceConfig)
    {
        sourceConfig.browserify = sourceConfig.browserify || {};
        sourceConfig.sass = sourceConfig.sass || {};
        sourceConfig.templates = sourceConfig.templates || {};
        sourceConfig.imports = sourceConfig.imports || [];

        for(var key in sourceConfig.imports) {
            var filename = sourceConfig.imports[key];
            importConfig(sourceConfig, filename);
        }


        mergeBrowserifyConfig(targetConfig.browserify, sourceConfig.browserify);
        mergeSassConfig(targetConfig.sass, sourceConfig.sass);

        if(sourceConfig.dist !== undefined) {
            targetConfig.dist = sourceConfig.dist;
        }

        for(var k in sourceConfig.templates) {
            targetConfig.templates[k] = sourceConfig.templates[k];
        }
    }

    function importConfig(targetConfig, importFilename)
    {
        var sourceConfig = grunt.file.readJSON(importFilename);
        merge(targetConfig, sourceConfig);
    };

    this.load = function(filename)
    {
        var targetConfig = {
            dist: undefined,
            browserify: {
                base_path: "",
                imports: [],
                alias: {},
                src: [],
                dest: undefined,
            },
            "sass": {
                paths: [],
                files: {},
            },
            "templates": {},
        };

        importConfig(targetConfig, filename);

        return targetConfig;
    }

    this.process = function(config)
    {

    }
}

module.exports = function(grunt)
{
    require('load-grunt-tasks')(grunt);

    grunt.loadNpmTasks('grunt-browserify');
    grunt.loadNpmTasks('grunt-envify');
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-sass');
    grunt.loadNpmTasks('grunt-contrib-sass');

    var configProcessor = new ConfigProcessor(grunt);


    var config = configProcessor.load('grunt-config.json');


    var distDir = config.dist;
    var jsFilename = 'app';
    var cssFilename = 'app';
    var templatesDirname = 'templates';

    var jsPath = config.dist+'/'+jsFilename+'.js';
    var jsMinPath = config.dist+'/'+jsFilename+'.min.js';
    var cssPath = config.dist+'/'+cssFilename+'.css';
    var cssMinPath = config.dist+'/'+cssFilename+'.min.css';

    var templatesDir = distDir+"/"+templatesDirname;
    var copyFiles = {};

    for(var templateName in config.templates) {
        var dest = templatesDir+"/"+templateName+".html";
        var src = config.templates[templateName];
        copyFiles[dest] = src;
    }



    var uglifyFiles = {};
    uglifyFiles[jsMinPath] = jsPath;

    var sassPaths = config.sass.paths;

    var sassFiles = {};

    sassFiles[cssPath] = sassPaths;

    var cssminFiles = {};
    cssminFiles[cssMinPath] = cssPath;

    grunt.initConfig({

        copy: {
            dist: {
                options: {},
                files: copyFiles,
            },

        },
        browserify: {
            dist: {
                options: {
                    //watch: true,
                    //keepAlive: true,
                    alias: config.browserify.alias,
                    transform: [['babelify', {presets: ['env']}]]
                },
                src: config.browserify.src,
                dest: config.browserify.dest,
                paths: []
            }
        },
        sass: {
            dist: {
                options: {
                    style: 'expanded',
                },
                files: sassFiles,
            },
        },
        uglify: {
            options: {
                mangle: false,
            },
            dist: {
                files: uglifyFiles,
            },
        },
        cssmin: { // minifying css task
            dist: {
                files: cssminFiles,
            }
        },
    });


    grunt.registerTask('html', ['copy:dist']);
    grunt.registerTask('js', ['browserify:dist', 'uglify:dist']);
    grunt.registerTask('js:dev', ['browserify:dist']);
    grunt.registerTask('css', ['sass:dist', 'cssmin:dist']);


    grunt.registerTask('default', ['js', 'css', 'html']);
}