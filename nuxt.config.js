import i18n from './config/i18n'

export default {
  publicRuntimeConfig: {
    BASE_URL: process.env.BASE_URL,
    BACKEND_URL: process.env.BACKEND_URL,
    CODE_RUNNER_URL: process.env.CODE_RUNNER_URL,
    FRONTEND_URL: process.env.BROWSER_URL
  },
  /*
   ** Headers of the page
   */
  head: {
    title: 'Kodektif',
    meta: [
      { charset: 'utf-8' },
      { name: 'viewport', content: 'width=device-width, initial-scale=1.0' },
      {
        hid: 'description',
        name: 'description',
        content: process.env.npm_package_description || ''
      }
    ],
    link: [
      { rel: 'icon', type: 'image/x-icon', href: '/favicon.ico' },
      {
        rel: 'stylesheet',
        href:
          'https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;1,100;1,200;1,300;1,400;1,500;1,600;1,700&display=swap'
      },
      {
        rel: 'stylesheet',
        type: 'text/css',
        href: 'https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css'
      },
      {
        rel: 'apple-touch-icon',
        sizes: '180x180',
        type: 'text/css',
        href: '/apple-touch-icon.png'
      },
      {
        rel: 'icon',
        type: 'image/png',
        sizes: '32x32',
        href: '/favicon-32x32.png'
      },
      {
        rel: 'icon',
        type: 'image/png',
        sizes: '16x16',
        href: '/favicon-16x16.png'
      },
      {
        rel: 'manifest',
        href: '/site.webmanifest'
      },
      {
        rel: 'mask-icon',
        href: '/safari-pinned-tab.svg',
        color: '#474b60'
      },
      {
        name: 'msapplication-TileColor',
        content: '#da532c'
      },
      {
        name: 'theme-color',
        content: '#ffffff'
      }
    ]
  },
  /*
   ** Customize the progress-bar color
   */
  loading: { color: '#fff' },
  components: true,
  /*
   ** Global CSS
   */
  css: ['@/assets/scss/main.scss'],
  /*
   ** Plugins to load before mounting the App
   */
  plugins: [
    { src: '@/plugins/aos.js', mode: 'client' },
    '@/plugins/bootstrap.js',
    '@/plugins/vSelect.js',
    '@/plugins/global.js',
    { src: '@/plugins/loadingOverlay.js', mode: 'client' },
    { src: '@/plugins/vue2dropzone.js', mode: 'client' },
    { src: '@/plugins/treeSelect.js', mode: 'client' }
  ],
  /*
   ** Nuxt.js dev-modules
   */
  buildModules: [
    '@nuxtjs/eslint-module',
    [
      'nuxt-i18n',
      {
        strategy: 'prefix_and_default',
        defaultLocale: 'en',
        locales: [
          {
            code: 'en',
            name: 'English',
            file: 'en.json'
          },
          {
            code: 'id',
            name: 'Bhs. Indonesia',
            file: 'id.json'
          }
        ],
        lazy: true,
        langDir: '~/lang/',
        vueI18n: {
          silentFallbackWarn: true
        },
        vueI18nLoader: true
      }
    ],
    '@nuxtjs/moment'
  ],
  /*
   ** Nuxt.js modules
   */
  modules: [
    '@nuxtjs/axios',
    '@nuxtjs/auth-next',
    '@nuxtjs/style-resources',
    'vue-toastification/nuxt',
    'vue-scrollto/nuxt',
    '@nuxt/content'
  ],

  content: {
    dir: 'courses',
    markdown: {
      prism: {
        theme: 'prism-themes/themes/prism-dracula.css'
      }
    }
  },

  toast: {
    cssFile: '@/assets/css/toastification.css'
  },

  styleResources: {
    scss: '@/assets/scss/_variables.scss'
  },
  /*
   ** Axios module configuration
   ** See https://axios.nuxtjs.org/options
   */
  axios: {
    retry: false,
    credentials: true,
    baseURL: process.env.BASE_URL,
    https: process.env.HTTPS_BOOLEAN || false,
    proxy: false
  },

  auth: {
    localStorage: false,
    redirect: {
      login: '/auth/login',
      // logout: '/',
      // callback: '/login',
      home: '/'
    },
    strategies: {
      cookie: {
        token: {
          required: false,
          type: false
        },
        user: {
          property: 'data',
          autoFetch: true
        },
        endpoints: {
          user: {
            url: '/user',
            method: 'get',
            withCredentials: true
          },
          logout: {
            method: 'post',
            url: '/logout'
          }
        }
      }
    }
  },
  /*
   ** Build configuration
   */
  build: {
    extractCSS: true,
    babel: {
      plugins: [['@babel/plugin-proposal-private-methods', { loose: true }]]
    }
    // extend(config, { isDev, isClient }) {
    //   config.module.rules.push({
    //     test: /\.worker\.js$/,
    //     use: { loader: 'worker-loader' }
    //   })

    //   if (isDev && isClient) {
    //     config.module.rules.push({
    //       enforce: 'pre',
    //       test: /\.(js|vue)$/,
    //       loader: 'eslint-loader',
    //       exclude: /(node_modules)/
    //     })
    //   }
    // }
  },
  watchters: {
    webpack: {
      ignored: [/api/, '**/.*']
    }
  }
}
