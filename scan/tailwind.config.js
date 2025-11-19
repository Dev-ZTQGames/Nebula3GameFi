/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './pages/**/*.{html,js}',
    './index.html',
    './public/**/*.{html,js}'
    
  ],
  theme: {
    screens: {
      xs: '100%',
      sm: '540px',
      md: '720px',
      lg: '960px',
      xl: '1140px',
      xxl: '1500px',
    },
    container: {
      center: true,
      padding: '0.75rem',
    },
    fontFamily: {
      'primary': ["Lato", 'sans-serif'],
    },
    extend: {
      fontSize: {
        'xs-1': '13px',
        'sm-1': '15px',
        'base-1': '17px',
        'xl-1': '21px',
        '2xl-1': '27px',
        '4xl-1': '44px',
      },
      colors: {
        'primary': '#001216',
        'primary-shade-1': '#9399C8',
        'primary-shade-2': '#D4D6E9',
        'primary-shade-3': '#001216cf',
        'primary-shade-4': '#CCCBE0',
        'primary-shade-5': '#2c5e670d',
        'primary-shade-6': '#2c5e6714',
        'secondary': '#85839D',
        'secondary-shade-1': '#2C5E67'
      },
      borderRadius: {
        'sm-1' : '3px',
      },
      boxShadow: {
        'global': '0px 0px 24px rgba(0, 0, 0, 0.06)',
      },
      backgroundImage: {
        'visa-card': "linear-gradient(90deg, rgba(44,94,103,1) 0%, rgba(0,18,22,0.9332107843137255) 0%), url(../images/card-bg.png)",
        'visa-card-1': "linear-gradient(90deg, rgba(44,94,103,1) 0%, rgba(0,18,22,0.9332107843137255) 0%), url(../images/card-bg.png)",
        'profile-card': "linear-gradient(90deg, rgba(44,94,103,1) 0%, rgba(0,18,22,0.9332107843137255) 0%), url(../images/trade-img.jpg)",
        'hero': "linear-gradient(90deg, rgba(44,94,103,1) 0%, rgba(0,18,22,0.9332107843137255) 0%),url(../images/hero_bg.jpg)",
        'CTA': "linear-gradient(90deg, rgba(44,94,103,1) 0%, rgba(0,18,22,0.9332107843137255) 0%),url(../images/cta_bg_wave.png)",
        'token-metrics': "linear-gradient(90deg, rgba(44, 94, 103, 1) 0%, rgb(0 18 22 / 97%) 0%), url(../images/liner_bg.jpg)",
        'footer': "linear-gradient(90deg, rgba(44,94,103,1) 0%, rgba(0,18,22,0.9332107843137255) 0%)",
      },
    },
  },
  plugins: [],
}

