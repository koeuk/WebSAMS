<template>
  <SelectItemText v-bind="props">
    <slot />
  </SelectItemText>
</template>

<script setup>
import { SelectItemText } from "reka-ui";

const props = defineProps({
  asChild: { type: Boolean, required: false },
  as: { type: null, required: false },
});
</script>
