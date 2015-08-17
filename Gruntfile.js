module.exports = function (grunt) {
    grunt.initConfig({
        bowerRequirejs: {
            target: {
                rjsConfig: 'assets/includes.js',
                options: {
                    baseUrl: './',
                    transitive: true
                }
            }
        }
    });

    grunt.loadNpmTasks('grunt-bower-requirejs');

    grunt.registerTask('default', ['bowerRequirejs']);
};