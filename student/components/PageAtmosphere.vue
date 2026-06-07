<template>
  <!--
    Cursor spotlight + static atmosphere, rendered behind page content.
    This is a dedicated component on purpose: useMouseInElement updates 60×/sec,
    so keeping the reactive CSS-var binding here means ONLY these three divs
    re-render per frame — the parent layout and the page <slot/> never do.
  -->
  <div
    ref="band"
    class="page-atmosphere"
    :style="{
      '--mouse-x': mouseX,
      '--mouse-y': mouseY,
      '--mouse-opacity': mouseOpacity,
    }"
    aria-hidden="true"
  >
    <div class="page-atmosphere__static"></div>
    <div class="page-atmosphere__spotlight"></div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'
import { useMouseInElement } from '@vueuse/core'

const band = ref<HTMLElement | null>(null)
// elementX/Y are relative to the band wrapper; isOutside is true when the
// cursor leaves it (over the sidebar, below the band, or off-window).
const { elementX, elementY, isOutside } = useMouseInElement(band)

const mouseX = computed(() => `${elementX.value}px`)
const mouseY = computed(() => `${elementY.value}px`)
const mouseOpacity = computed(() => (isOutside.value ? '0' : '1'))
</script>

<style scoped>
/* ── Layer wrapper: sits behind content, never intercepts the cursor ── */
.page-atmosphere {
  position: absolute;
  inset: 0 0 auto 0;     /* pin to the top of the page wrapper */
  height: 520px;         /* a "hero" band; cards below stay clean */
  pointer-events: none;
  z-index: -1;
  overflow: clip;
}

.page-atmosphere__static,
.page-atmosphere__spotlight {
  position: absolute;
  inset: 0;
  /* Fade both layers out toward the bottom so content below is untouched */
  -webkit-mask-image: linear-gradient(180deg, #000 0%, #000 60%, transparent 100%);
          mask-image: linear-gradient(180deg, #000 0%, #000 60%, transparent 100%);
}

/* ── Static atmosphere: dotted grid + emerald/teal corner tints ── */
.page-atmosphere__static {
  background:
    radial-gradient(130% 90% at 0% 0%,   var(--atmo-tint-1), transparent 50%), /* corner glow (top-left)  */
    radial-gradient(130% 90% at 100% 0%, var(--atmo-tint-2), transparent 52%), /* corner glow (top-right) */
    radial-gradient(circle, var(--atmo-dot) 1px, transparent 1.6px);          /* dotted grid             */
  background-size: 100% 100%, 100% 100%, 18px 18px;
  background-repeat: no-repeat, no-repeat, repeat;
}

/* ── Cursor spotlight: its own layer so only it repaints on mousemove ── */
.page-atmosphere__spotlight {
  background: radial-gradient(
    560px circle at var(--mouse-x, 50%) var(--mouse-y, -200px),
    var(--atmo-spot),
    transparent 62%
  );
  /* Only opacity is transitioned — gradients can't interpolate cross-browser */
  opacity: var(--mouse-opacity, 0);
  transition: opacity 220ms ease-out;
  will-change: opacity;
}

/* Vestibular accessibility: no roaming light for reduced-motion users */
@media (prefers-reduced-motion: reduce) {
  .page-atmosphere__spotlight { display: none; }
}
</style>
