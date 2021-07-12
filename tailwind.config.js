const colors = require('tailwindcss/colors')

module.exports = {
    purge: [
        './views/**/*.blade.php',
        './resources/*.js',
        './resources/*.css'
    ],
    darkMode: 'class', // or 'media' or 'class'
    theme: {
        extend: {
            backdropBrightness: {
                25: '.25',
                35: '.35',
            },
            transitionDuration: {
                '50': '50ms',
            },
            spacing: {
                '144': '36rem',
                '192': '48rem',
            },
            blur: {
                px: '1px',
            },
            colors: {
                transparent: 'transparent',
                current: 'currentColor',
                black: colors.black,
                white: colors.white,
                blueGray: colors.blueGray,
                gray: colors.gray,
                coolGray: colors.coolGray,
                warmGrey: colors.warmGray,
                red: colors.red,
                orange: colors.orange,
                amber: colors.amber,
                yellow: colors.yellow,
                lime: colors.lime,
                green: colors.green,
                emerald: colors.emerald,
                teal: colors.teal,
                cyan: colors.cyan,
                lightBlue: colors.lightBlue,
                sky: colors.sky,
                blue: colors.blue,
                indigo: colors.indigo,
                purple: colors.purple,
                pink: colors.pink,
                rose: colors.rose,
            }
        },
    },
    variants: {
        extend: {
            height: ['hover'],
            width: ['hover'],
            display: ["group-hover"],
            textOverflow: ['hover'],
            cursor: ['hover'],
        },
    },
    plugins: [
        require('@tailwindcss/forms'),
        require('postcss-import'),
        require('tailwindcss'),
        require('autoprefixer'),
        require('@tailwindcss/typography'),
    ]
}
