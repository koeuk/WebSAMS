<script setup lang="ts">
import type { HTMLAttributes } from "vue"
import { cn } from "@/lib/utils"

const props = defineProps<{
  class?: HTMLAttributes["class"]
}>()
</script>

<template>
  <th :class="cn('px-6 py-3.5 text-left align-middle text-[11px] font-bold uppercase tracking-[0.1em] text-slate-400 bg-[#faf8f3] border-b border-line [&:has([role=checkbox])]:pr-0', props.class)">
    <slot />
  </th>
</template>
