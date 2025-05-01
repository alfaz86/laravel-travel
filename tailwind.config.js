import preset from './vendor/filament/support/tailwind.config.preset'

const colors = require('tailwindcss/colors')

module.exports = {
  presets: [preset],
  content: [
    "./app/Filament/**/*.php",
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
    "./node_modules/flowbite/**/*.js",
    "./vendor/filament/**/*.blade.php",
  ],
  theme: {
    extend: {
      colors: {
        ...colors,
      },
    },
  },
  plugins: [
    require('flowbite/plugin')
  ],
}