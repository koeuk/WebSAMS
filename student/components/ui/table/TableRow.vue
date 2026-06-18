<script setup lang="ts">
import type { HTMLAttributes } from "vue"
import { cn } from "@/lib/utils"

const props = defineProps<{
  class?: HTMLAttributes["class"]
}>()
</script>

<template>
  <tr :class="cn('border-b border-line transition-colors hover:bg-[#faf8f3] dark:hover:bg-white/5 data-[state=selected]:bg-muted', props.class)">
    <slot />
  </tr>
</template>
