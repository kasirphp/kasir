module.exports = {
    title: 'Kasir',
    description: 'Documentation for KasirPHP, a Laravel library for Midtrans payment gateway.',

    head: [
        ['link', {rel: 'icon', type: 'image/svg+xml', href: '/logo.svg'}],
        ['meta', {property: 'og:type', content: 'website'}],
        ['meta', {property: 'og:title', content: og().title}],
        ['meta', {property: 'og:image', content: og().image}],
        ['meta', {property: 'og:url', content: og().url}],
        ['meta', {property: 'og:description', content: og().description}],
    ],

    base: '/',

    vue: {
        reactivityTransform: true,
    },

    themeConfig: {
        logo: '/logo.svg',
        siteTitle: false,
        nav: navbar(),
        socialLinks: socialLinks(),
        sidebar: {
            '/': sidebar(),
        },
        footer: {
            copyright: 'Copyright © 2023 Risang Baskoro',
            message: '"Midtrans" and Midtrans Logo are trademark of Midtrans (PT Midtrans). Kasir is not affiliated by Midtrans.',
        }
    }
}

function og() {
    return {
        title: 'Kasir',
        description: 'Documentation for KasirPHP, a Laravel library for Midtrans payment gateway.',
        image: 'https://raw.githubusercontent.com/kasirphp/art/master/svg/logo.svg',
        url: 'https://kasirphp.com',
    }
}

function navbar() {
    return [
        {text: 'Home', link: '/'},
        {text: 'Documentation', items: sidebar()},
    ]
}

function sidebar() {
    return [
        {text: 'Installation', link: '/installation'},
        {text: 'Transaction Details', link: '/transaction-details'},
        {text: 'Payment Methods', link: '/payment-methods'},
        {text: 'Core API', link: '/core-api'},
        {text: 'Snap API', link: '/snap-api'},
    ]
}

function socialLinks() {
    return [
        {icon: 'twitter', link: 'https://twitter.com/i/communities/1623376036779130881'},
        {icon: 'github', link: 'https://github.com/kasirphp'},
    ]
}
