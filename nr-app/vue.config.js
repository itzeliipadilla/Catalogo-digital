const { defineConfig } = require('@vue/cli-service')
module.exports = defineConfig({
  transpileDependencies: true,
  pwa: {
    name: 'Novedades Ramírez',
    themeColor: '#16A34A',
    msTileColor: '#16A34A',
    appleMobileWebAppCapable: 'yes',
    appleMobileWebAppStatusBarStyle: 'black',
    manifestOptions: {
      background_color: '#16A34A',
      icons: [
        {
          src: 'img/icon/novedades.png',
          type: 'image/png'
        },
      ]
    },
    iconPaths: {
      favicon32: 'img/icon/novedades.png',
      favicon16: 'img/icon/novedades.png',
      appleTouchIcon: 'img/icon/novedades.png',
      maskIcon: null,
      msTileImage: 'img/icon/novedades.png'
    }
  }
})
