<template>
  <div class="rich-editor rounded-md border border-slate-200 bg-white text-sm shadow-sm focus-within:ring-1 focus-within:ring-slate-300">
    <div class="flex flex-wrap gap-0.5 border-b border-slate-200 px-2 py-1.5">
      <button type="button" @click="editor?.chain().focus().toggleBold().run()" :class="btnClass('bold')" title="Bold"><svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M6 4h8a4 4 0 014 4 4 4 0 01-4 4H6zm0 8h9a4 4 0 014 4 4 4 0 01-4 4H6z"/></svg></button>
      <button type="button" @click="editor?.chain().focus().toggleItalic().run()" :class="btnClass('italic')" title="Italic"><svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M10 4v3h2.21l-3.42 10H6v3h8v-3h-2.21l3.42-10H18V4z"/></svg></button>
      <button type="button" @click="editor?.chain().focus().toggleUnderline().run()" :class="btnClass('underline')" title="Underline"><svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 17c3.31 0 6-2.69 6-6V3h-2.5v8c0 1.93-1.57 3.5-3.5 3.5S8.5 12.93 8.5 11V3H6v8c0 3.31 2.69 6 6 6zm-7 2v2h14v-2H5z"/></svg></button>
      <button type="button" @click="editor?.chain().focus().toggleStrike().run()" :class="btnClass('strike')" title="Strikethrough"><svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M10 19h4v-3h-4v3zM5 4v3h5v3h4V7h5V4H5zM3 14h18v-2H3v2z"/></svg></button>
      <div class="w-px bg-slate-200 mx-1 self-stretch"></div>
      <button type="button" @click="editor?.chain().focus().toggleHeading({ level: 2 }).run()" :class="btnClass('heading', { level: 2 })" title="Heading 2"><span class="text-[11px] font-bold">H2</span></button>
      <button type="button" @click="editor?.chain().focus().toggleHeading({ level: 3 }).run()" :class="btnClass('heading', { level: 3 })" title="Heading 3"><span class="text-[11px] font-bold">H3</span></button>
      <div class="w-px bg-slate-200 mx-1 self-stretch"></div>
      <button type="button" @click="editor?.chain().focus().toggleBulletList().run()" :class="btnClass('bulletList')" title="Bullet list"><svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M4 10.5c-.83 0-1.5.67-1.5 1.5s.67 1.5 1.5 1.5 1.5-.67 1.5-1.5-.67-1.5-1.5-1.5zm0-6c-.83 0-1.5.67-1.5 1.5S3.17 7.5 4 7.5 5.5 6.83 5.5 6 4.83 4.5 4 4.5zm0 12c-.83 0-1.5.68-1.5 1.5s.68 1.5 1.5 1.5 1.5-.68 1.5-1.5-.67-1.5-1.5-1.5zM7 19h14v-2H7v2zm0-6h14v-2H7v2zm0-8v2h14V5H7z"/></svg></button>
      <button type="button" @click="editor?.chain().focus().toggleOrderedList().run()" :class="btnClass('orderedList')" title="Ordered list"><svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M2 17h2v.5H3v1h1v.5H2v1h3v-4H2v1zm1-9h1V4H2v1h1v3zm-1 3h1.8L2 13.1v.9h3v-1H3.2L5 10.9V10H2v1zm5-6v2h14V5H7zm0 14h14v-2H7v2zm0-6h14v-2H7v2z"/></svg></button>
      <button type="button" @click="editor?.chain().focus().toggleBlockquote().run()" :class="btnClass('blockquote')" title="Blockquote"><svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M6 17h3l2-4V7H5v6h3zm8 0h3l2-4V7h-6v6h3z"/></svg></button>
      <div class="w-px bg-slate-200 mx-1 self-stretch"></div>
      <button type="button" @click="editor?.chain().focus().unsetAllMarks().clearNodes().run()" class="toolbar-btn" title="Clear formatting"><svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M6 19h4v-3H6v3zm14 0h-8v-3h4.97L20 10.45V5H4v5.45l5.03 10.55H10v3h4v-3h-1.38L10.03 14H18v5zm0-8H4V7h16v4z"/></svg></button>
    </div>
    <div class="overflow-auto resize-y" :style="{ minHeight: minHeight ?? '120px' }">
      <EditorContent :editor="editor" class="editor-content" />
    </div>
  </div>
</template>

<script setup lang="ts">
import { watch, onBeforeUnmount } from 'vue'
import { useEditor, EditorContent } from '@tiptap/vue-3'
import StarterKit from '@tiptap/starter-kit'
import Underline from '@tiptap/extension-underline'
import Placeholder from '@tiptap/extension-placeholder'

const props = defineProps<{ modelValue?: string; placeholder?: string; minHeight?: string }>()
const emit = defineEmits<{ 'update:modelValue': [value: string] }>()

const editor = useEditor({
  content: props.modelValue ?? '',
  extensions: [
    StarterKit,
    Underline,
    Placeholder.configure({ placeholder: props.placeholder ?? 'Write something...' }),
  ],
  editorProps: {
    attributes: { class: 'prose prose-sm max-w-none focus:outline-none px-3 py-2' },
  },
  onUpdate: ({ editor }) => {
    const html = editor.getHTML()
    emit('update:modelValue', html === '<p></p>' ? '' : html)
  },
})

watch(() => props.modelValue, (val) => {
  if (!editor.value) return
  const current = editor.value.getHTML()
  const normalised = val ?? ''
  if (current !== normalised && !(normalised === '' && current === '<p></p>')) {
    editor.value.commands.setContent(normalised, false)
  }
})

onBeforeUnmount(() => editor.value?.destroy())

const btnClass = (name: string, attrs: Record<string, unknown> = {}) => {
  const active = editor.value?.isActive(name, attrs)
  return ['toolbar-btn', active ? 'toolbar-btn-active' : '']
}
</script>

<style scoped>
.toolbar-btn {
  @apply flex cursor-pointer items-center justify-center w-7 h-7 rounded text-slate-500 hover:text-slate-900 hover:bg-slate-100 transition-colors;
}
.toolbar-btn-active {
  @apply bg-slate-100 text-slate-900;
}
</style>

<style>
.editor-content .ProseMirror p.is-editor-empty:first-child::before {
  content: attr(data-placeholder);
  float: left;
  color: #94a3b8;
  pointer-events: none;
  height: 0;
}
.editor-content .ProseMirror {
  outline: none;
}
.editor-content .ProseMirror ul {
  list-style-type: disc;
  padding-left: 1.25rem;
}
.editor-content .ProseMirror ol {
  list-style-type: decimal;
  padding-left: 1.25rem;
}
.editor-content .ProseMirror blockquote {
  border-left: 3px solid #e2e8f0;
  padding-left: 0.75rem;
  color: #64748b;
  margin: 0.5rem 0;
}
.editor-content .ProseMirror h2 {
  font-size: 1.1rem;
  font-weight: 700;
  margin: 0.5rem 0 0.25rem;
}
.editor-content .ProseMirror h3 {
  font-size: 1rem;
  font-weight: 600;
  margin: 0.5rem 0 0.25rem;
}
</style>
