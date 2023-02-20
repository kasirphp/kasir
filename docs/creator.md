---
layout: page
title: Meet the Creator
---

<script setup>
import {
  VPTeamPage,
  VPTeamPageTitle,
  VPTeamPageSection,
  VPTeamMembers
} from 'vitepress/theme';
import { team } from './_data/team';
</script>

<VPTeamPage>
    <VPTeamPageTitle>
        <template #title>
            Meet the Creator
        </template>
    </VPTeamPageTitle>
    <VPTeamMembers size="medium" :members="team" />
</VPTeamPage>