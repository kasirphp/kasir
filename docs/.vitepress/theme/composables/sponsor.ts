import { ref, onMounted } from 'vue'

interface Sponsors {
    sponsors: Sponsor[]
}

interface Sponsor {
    handle: string,
    avatar: string
    profile: string,
    details: SponsorDetails[]
}

interface SponsorDetails {
    login: string
    html_url: string
    avatarUrl: string
    name?: string
    twitterUsername?: string
}

// shared data across instances so we load only once.
const data = ref()

const dataHost = 'https://ghs.vercel.app'
const dataUrl = `${dataHost}/sponsors/risangbaskoro`

export function useSponsor() {
    // @ts-ignore
    onMounted(async () => {
        if (data.value) {
            return
        }

        const result = await fetch(dataUrl)
        const json = await result.json()

        data.value = mapSponsors(json)
    })

    return {
        data,
    }
}

function mapSponsors(sponsors: Sponsors) {
    return [
        {
            tier: 'GitHub Sponsors',
            size: 'medium',
            items: mapImgPath(sponsors['sponsors']),
        },
    ]
}

function mapImgPath(sponsors: Sponsor[]) {

    return sponsors.map((sponsor) => ({
        ...sponsor,
        name: sponsor.details['name'] || sponsor.details['login'] || sponsor.handle,
        img: sponsor.details['avatar_url'] || sponsor.avatar,
        url: sponsor.details['html_url'] || sponsor.profile,
    }))
}