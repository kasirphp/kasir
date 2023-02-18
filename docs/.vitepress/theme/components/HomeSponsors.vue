<script setup lang="ts">
import { VPHomeSponsors } from 'vitepress/theme'
import { useSponsor } from '../composables/sponsor'
const { data } = useSponsor()
</script>

<template>
    <VPHomeSponsors
            v-if="data"
            message="Kasir is free and open source, made possible by our wonderful supporters."
            action-text="Become a sponsor"
            action-link="https://github.com/sponsors/risangbaskoro"
            :data="data"
    />
</template>