module.exports = function (grunt) {
    grunt.initConfig({
        copy: {
            main: {
                options: {
                    mode: true
                },
                src: [
                    '**',
                    '*.zip',
                    '!node_modules/**',
                    '!build/**',
                    '!css/sourcemap/**',
                    '!.git/**',
                    '!bin/**',
                    '!.gitlab-ci.yml',
                    '!bin/**',
                    '!tests/**',
                    '!phpunit.xml.dist',
                    '!*.sh',
                    '!*.map',
                    '!package.json',
                    '!phpunit.xml',
                    '!README.md',
                    '!sass/**',
                    '!codesniffer.ruleset.xml',
                    '!.gitignore',
                    '!Gruntfile.js',
                    '!vendor/**',
                    '!composer.json',
                    '!composer.lock',
                    '!package-lock.json',
                    '!phpcs.xml.dist',
                ],
                dest: 'developer-tool/'
            }
        },

        compress: {
            main: {
                options: {
                    archive: 'developer-tool.zip',
                    mode: 'zip'
                },
                files: [
                    {
                        src: [
                            './developer-tool/**'
                        ]

                    }
                ]
            }
        },

        clean: {
            main: ["developer-tool"],
            zip: ["developer-tool.zip"],
        },

        makepot: {
            target: {
                options: {
                    domainPath: '/',
                    mainFile: 'developer-tool.php',
                    potFilename: 'languages/dev-tool.pot',
                    potHeaders: {
                        poedit: true,
                        'x-poedit-keywordslist': true
                    },
                    type: 'wp-plugin',
                    updateTimestamp: true
                }
            }
        },
        
        addtextdomain: {
            options: {
                textdomain: 'developer-tool',
            },
            target: {
                files: {
                    src: ['*.php', '**/*.php', '!node_modules/**', '!php-tests/**', '!bin/**', '!asset/bsf-core/**']
                }
            }
        }
    });

    grunt.loadNpmTasks('grunt-contrib-copy');
    grunt.loadNpmTasks('grunt-contrib-compress');
    grunt.loadNpmTasks('grunt-contrib-clean');
    grunt.loadNpmTasks('grunt-wp-i18n');

    grunt.registerTask('i18n', ['addtextdomain', 'makepot']);
    grunt.registerTask('release', ['clean:zip', 'copy', 'compress', 'clean:main']);
    
};
