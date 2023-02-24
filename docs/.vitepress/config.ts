import {defineConfig} from 'vitepress'

export default defineConfig({
    lang: 'en-US',
    title: 'Kasir',
    description: 'Documentation for KasirPHP, a Laravel library for Midtrans payment gateway.',

    head: [
        ['link', {rel: 'icon', type: 'image/svg+xml', href: '/logo.svg'}],
        ['meta', {property: 'og:type', content: 'website'}],
        ['meta', {property: 'og:title', content: og().title}],
        ['meta', {property: 'og:image', content: og().image}],
        ['meta', {property: 'og:url', content: og().url}],
        ['meta', {property: 'og:description', content: og().description}],
        ['meta', { name: 'twitter:card', content: 'summary_large_image' }],
        ['meta', { name: 'twitter:team', content: '@risangbaskoro' }],
        ['meta', { name: 'theme-color', content: '#2F80C2' }],
    ],

    base: '/',

    cleanUrls: true,

    themeConfig: {
        logo: '/logo.svg',
        siteTitle: false,
        algolia: {
            appId: 'DG5WH4TJAL',
            apiKey: 'c28085e9480d674830daabf39fc176a1',
            indexName: 'kasirphp',
        },
        nav: navbar(),
        socialLinks: [
            {icon: 'twitter', link: 'https://twitter.com/i/communities/1623376036779130881'},
            {icon: 'github', link: 'https://github.com/kasirphp'},
        ],
        sidebar: {
            '/creator': [],
            '/': sidebar(),
        },
        footer: {
            copyright: 'Copyright © 2023 Risang Baskoro',
            message: '"Midtrans" and Midtrans Logo are trademark of Midtrans (PT Midtrans). Kasir is not affiliated by Midtrans.',
        }
    }
})

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
        {text: 'Creator', link: '/creator'},
    ]
}

function sidebar() {
    return [
        {
            text: 'Introduction',
            items: [
                {text: 'Why Kasir', link: '/why-kasir'},
                {text: 'Getting Started', link: '/getting-started'},
                {text: 'Installation', link: '/installation'},
            ]
        },
        {
            text: 'Guide',
            items: [
                {text: 'Transaction Details', link: '/transaction-details'},
                {text: 'Discounts and Taxes', link: '/discounts-and-taxes'},
                {text: 'Payment Methods', link: '/payment-methods'},
                {text: 'Core API', link: '/core-api'},
                {text: 'Snap API', link: '/snap-api'},
            ]
        },
    ]
}
