module.exports = function(grunt){
  // 构建任务配置
  grunt.initConfig({
    pkg: grunt.file.readJSON('package.json'),
    meta: {
      srcPath: 'Public/src/',
      staticPath: 'Public/Static/',
      homePath: 'Public/Home/',
      defaultPath: 'Public/Home/default/',
      mobilePath: 'Public/Home/default-mobile/',
      installPath: 'Public/Install/',
    },
    banner: '/*\n' +
          ' * <%= pkg.name %> <%= pkg.version %>\n' +
          ' * Released on: <%= grunt.template.today("yyyy-mm-dd HH:mm:ss") %> $<%= grunt.template.today("mmddHHmm") %>\n' +
          ' */\n',
    // SCSS构建
    sass: {
      options: {
        // banner: '<%= banner %>',
        style: 'expanded',
        unixNewlines: true
      },
      // PC端采用Bootstrap构建
      install: {
        files: {
          '<%= meta.installPath %>/css/style.css': '<%= meta.srcPath %>scss/install.scss'
        }
      },
      defaultPc: {
        files: {
          '<%= meta.homePath %>default/css/base.css': '<%= meta.srcPath %>scss/default.scss'
        }
      },
      defaultMobile: {
        files: {
          '<%= meta.homePath %>default-mobile/css/base.css': '<%= meta.srcPath %>scss/default.mobile.scss'
        }
      },
      paidashuPc: {
        files: {
          '<%= meta.homePath %>paidashu/css/base.css': '<%= meta.srcPath %>scss/paidashu.scss'
        }
      },
      paidashuMobile: {
        files: {
          '<%= meta.homePath %>paidashu-mobile/css/base.css': '<%= meta.srcPath %>scss/paidashu.mobile.scss'
        }
      }
    },

    // CSS压缩
    cssmin: {
      options: {
        banner: '<%= banner %>'
      },
      defaultPc: {
        src: [
          '<%= meta.homePath %>default/css/base.css'
        ],
        dest: '<%= meta.homePath %>default/css/style.min.css'
      },
      defaultMobile: {
        src: [
          '<%= meta.homePath %>default-mobile/css/base.css'
        ],
        dest: '<%= meta.homePath %>default-mobile/css/style.min.css'
      },
      paidashuPc: {
        src: [
          '<%= meta.homePath %>paidashu/css/base.css'
        ],
        dest: '<%= meta.homePath %>paidashu/css/style.min.css'
      },
      paidashuMobile: {
        src: [
          '<%= meta.homePath %>paidashu-mobile/css/base.css'
        ],
        dest: '<%= meta.homePath %>paidashu-mobile/css/style.min.css'
      }
    },
    watch: {
      // scripts: {
      //   files: 'Public/Home/*.js',
      //   tasks: ['uglify'],
      //   options: {
      //     debounceDelay: 250
      //   }
      // },
      // css: {
      //   files: 'Public/Home/*.css',
      //   tasks: ['cssmin'],
      //   options: {
      //     debounceDelay: 250
      //   }
      // },
      sass: {
        files: [
          '<%= meta.srcPath %>scss/default.scss',
          '<%= meta.srcPath %>scss/default.mobile.scss',
          '<%= meta.srcPath %>scss/paidashu.scss',
          '<%= meta.srcPath %>scss/paidashu.mobile.scss',
          '<%= meta.srcPath %>scss/linglingxian.scss',
          '<%= meta.srcPath %>scss/linglingxian.mobile.scss',
          '<%= meta.srcPath %>scss/jsuka.scss',
          '<%= meta.srcPath %>scss/jsuka.mobile.scss',
          '<%= meta.srcPath %>scss/bootstrap/**/*',
          '<%= meta.srcPath %>scss/ratchet/**/*',
        ],
        // tasks: ['sass:paidashuMobile', 'cssmin:paidashuMobile', 'sass:paidashuPc', 'cssmin:paidashuPc'],
        tasks: ['sass:paidashuPc', 'cssmin:paidashuPc'],
        // tasks: ['sass:paidashuMobile', 'cssmin:paidashuMobile']
        options: {
          debounceDelay: 250
        }
      }
    }
  });

  grunt.loadNpmTasks('grunt-contrib-uglify');
  grunt.loadNpmTasks('grunt-contrib-cssmin');
  grunt.loadNpmTasks('grunt-contrib-less');
  grunt.loadNpmTasks('grunt-contrib-sass');
  grunt.loadNpmTasks('grunt-contrib-watch');

  grunt.registerTask('dist-css', ['less','sass','cssmin']);

  //系统安装
  grunt.registerTask('dist-install', ['sass:install']);

  //默认模板default
  grunt.registerTask('dist-default', ['sass:defaultPc', 'cssmin:defaultPc']);
  grunt.registerTask('dist-default-mobile', ['sass:defaultMobile', 'cssmin:defaultMobile']);

  //PDS
  grunt.registerTask('dist-paidashu', ['sass:paidashuPc', 'cssmin:paidashuPc']);
  grunt.registerTask('dist-paidashu-mobile', ['sass:paidashuMobile', 'cssmin:paidashuMobile']);

  //其他模板类似增加......

};