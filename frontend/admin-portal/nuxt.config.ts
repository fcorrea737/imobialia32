import { fileURLToPath } from 'node:url'
import svgLoader from 'vite-svg-loader'
import vuetify from 'vite-plugin-vuetify'

// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
  compatibilityVersion: 4,
  srcDir: 'src/',
  ssr: false, // Temporary fix for Pinia/routing issues
  
  // Explicitly configure pages directory
  pages: true,
  
  app: {
    head: {
      titleTemplate: '%s - NuxtJS Admin Template',
      title: 'Vuexy',

      link: [{
        rel: 'icon',
        type: 'image/x-icon',
        href: `${process.env.NUXT_APP_BASE_URL}/favicon.ico`,
      }],
    },
  },

  devtools: {
    enabled: true,
  },

  css: [
    'vuetify/styles', // Add Vuetify base styles
    '@core/scss/template/index.scss',
    '@styles/styles.scss',
    '@/plugins/iconify/icons.css',
  ],

  components: {
    dirs: [
      { path: '@core/components', pathPrefix: false },
      { path: '@/components/global', global: true },
      { path: '@/components', pathPrefix: false },
    ],
  },

  plugins: ['@/plugins/vuetify/index.ts', '@/plugins/iconify/index.ts'],

  imports: {
    dirs: ['@core/utils', '@core/composable/', '@/plugins/*/composables/*', '@/composables'],
  },

  hooks: {},

  experimental: {
    typedPages: true,
    payloadExtraction: false, // Fix Pinia hasOwnProperty error
  },

  // Use built-in alias configuration instead of TypeScript paths
  alias: {
    '@': fileURLToPath(new URL('./src', import.meta.url)),
    '@themeConfig': fileURLToPath(new URL('./src/themeConfig.ts', import.meta.url)),
    '@core': fileURLToPath(new URL('./src/@core', import.meta.url)),
    '@layouts': fileURLToPath(new URL('./src/@layouts', import.meta.url)),
    '@images': fileURLToPath(new URL('./src/assets/images/', import.meta.url)),
    '@styles': fileURLToPath(new URL('./src/assets/styles/', import.meta.url)),
    '@configured-variables': fileURLToPath(new URL('./src/assets/styles/variables/_template.scss', import.meta.url)),
    '@validators': fileURLToPath(new URL('./src/@core/utils/validators', import.meta.url)),
    '@db': fileURLToPath(new URL('./server/fake-db/', import.meta.url)),
    '@api-utils': fileURLToPath(new URL('./server/utils/', import.meta.url)),
  },

  // ℹ️ Disable source maps until this is resolved: https://github.com/vuetifyjs/vuetify-loader/issues/290
  sourcemap: {
    server: false,
    client: false,
  },

  vue: {
    compilerOptions: {
      isCustomElement: (tag: string) => tag === 'swiper-container' || tag === 'swiper-slide',
    },
  },

  vite: {
    define: { 'process.env': {} },

    build: {
      chunkSizeWarningLimit: 5000,
    },

    optimizeDeps: {
      exclude: ['vuetify'],
      entries: [
        './**/*.vue',
      ],
    },

    plugins: [
      svgLoader(),
      vuetify({
        styles: false, // Disable individual component styles
      }),
    ],
  },

  build: {
    transpile: ['vuetify'],
  },

  nitro: {
    esbuild: {
      options: {
        target: 'esnext'
      }
    }
  },

  modules: ['@vueuse/nuxt', '@nuxtjs/i18n', '@nuxtjs/device', '@pinia/nuxt'],

  pinia: {
    storesDirs: ['./stores/**', './src/stores/**', './src/@core/stores/**', './src/@layouts/stores/**'],
    disableVuex: true,
  },

  i18n: {
    locales: [
      {
        code: 'pt',
        file: 'pt.ts',
        name: 'Português'
      }
    ],
    defaultLocale: 'pt',
    lazy: true
  },
  compatibilityDate: '2025-10-04',
})
