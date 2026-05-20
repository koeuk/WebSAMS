# Empty Component Integration Guide

A step-by-step guide to integrate the shadcn-vue `Empty` component for all empty states, and make `RichTextEditor` height resizable.

---

## Step 1 — Install Empty Component

Run in each portal that has a `components.json`:

```bash
npx shadcn-vue@latest add empty --yes
```

This creates 7 files: `Empty.vue`, `EmptyContent.vue`, `EmptyDescription.vue`, `EmptyHeader.vue`, `EmptyMedia.vue`, `EmptyTitle.vue`, `index.ts/js`.

---

## Step 2 — Replace All Empty States

Find every plain-text empty state in the codebase:

```
<TableRow v-if="!data.length"><TableCell ...>No X found.</TableCell></TableRow>
<div v-if="!data.length" class="... text-center ...">No X yet.</div>
<tr v-if="!data.length"><td ...>No X.</td></tr>
```

### Pattern A — Inside a Table (spanning cell)

```html
<TableRow v-if="!data.length">
  <TableCell :colspan="N" class="p-0">
    <Empty class="border-0 rounded-none">
      <EmptyHeader>
        <EmptyMedia variant="icon"><SomeIcon class="w-6 h-6" /></EmptyMedia>
        <EmptyTitle>No X found</EmptyTitle>
        <EmptyDescription>Contextual hint for the user.</EmptyDescription>
      </EmptyHeader>
    </Empty>
  </TableCell>
</TableRow>
```

### Pattern B — Card / List Based

```html
<div v-if="!data.length" class="card overflow-hidden">
  <Empty class="border-0 rounded-none">
    <EmptyHeader>
      <EmptyMedia variant="icon"><SomeIcon class="w-6 h-6" /></EmptyMedia>
      <EmptyTitle>No X yet</EmptyTitle>
      <EmptyDescription>Contextual hint for the user.</EmptyDescription>
    </EmptyHeader>
  </Empty>
</div>
```

### Icon Reference

| Content Type | Icon |
|---|---|
| Users / People | `Users` |
| Courses | `BookOpen` |
| Subjects | `Book` |
| Classes / Rooms | `School` |
| Attendance | `ClipboardList` |
| Schedules | `CalendarDays` |
| Semesters | `CalendarRange` |
| Time Slots | `Clock` |
| Excuse Requests | `FileText` |
| Absent Records | `ClipboardCheck` |
| Announcements | `Megaphone` |
| Notifications | `Bell` |
| General List | `List` |

### Import Rules

**Nuxt 3** — `Empty*` components are auto-imported by `shadcn-nuxt`, but Lucide icons need explicit imports in each `<script setup>`:

```ts
import { Bell, Megaphone } from 'lucide-vue-next'
```

**Inertia / Vue (Laravel)** — Both Empty components and Lucide icons need explicit imports:

```js
import { Empty, EmptyHeader, EmptyMedia, EmptyTitle, EmptyDescription } from '@/Components/ui/empty'
import { Bell } from 'lucide-vue-next'
```

---

## Step 3 — Flexible RichTextEditor Height

In `RichTextEditor.vue`, make three changes:

### 1. Add `minHeight` prop

**Vue (JS):**
```js
const props = defineProps({
  modelValue:  { type: String, default: '' },
  placeholder: { type: String, default: 'Write something...' },
  minHeight:   { type: String, default: '120px' },
})
```

**Vue (TS):**
```ts
const props = defineProps<{
  modelValue?: string
  placeholder?: string
  minHeight?: string
}>()
```

### 2. Remove hardcoded `min-h-[80px]` from editorProps

```js
editorProps: {
  attributes: { class: 'prose prose-sm max-w-none focus:outline-none px-3 py-2' },
},
```

### 3. Wrap `EditorContent` in a resizable container

```html
<div class="overflow-auto resize-y" :style="{ minHeight: minHeight ?? '120px' }">
  <EditorContent :editor="editor" class="editor-content" />
</div>
```

### Result

- Drag handle appears at the bottom-right corner of the editor
- Default height is `120px` (comfortable starting size)
- Override per usage: `<RichTextEditor min-height="200px" />`

---

## Notes

- Use `border-0 rounded-none` on `<Empty>` when it's inside a card or table to avoid double borders
- Use `<Empty class="border-dashed rounded-lg">` (default) for standalone empty states with no card wrapper
- The `EmptyContent` slot is for action buttons (e.g. "Create your first X")
