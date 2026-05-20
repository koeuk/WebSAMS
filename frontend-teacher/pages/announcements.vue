<template>
  <div class="animate-fade-in">
    <div class="flex items-center justify-between mb-7">
      <div>
        <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Announcements</h2>
        <p class="text-sm text-slate-500 mt-1">Post and manage class announcements</p>
      </div>
      <Button @click="openNew" class="flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        New Announcement
      </Button>
    </div>

    <div v-if="loading" class="card p-12 text-center text-slate-400 text-sm">Loading...</div>

    <div v-else class="space-y-4">
      <div v-if="!announcements.length" class="card overflow-hidden">
        <Empty class="border-0 rounded-none">
          <EmptyHeader>
            <EmptyMedia variant="icon"><Megaphone class="w-6 h-6" /></EmptyMedia>
            <EmptyTitle>No announcements yet</EmptyTitle>
            <EmptyDescription>Create your first announcement to notify your students.</EmptyDescription>
          </EmptyHeader>
        </Empty>
      </div>
      <div v-for="a in announcements" :key="a.id" class="card p-5 animate-fade-in-up">
        <div class="flex items-start justify-between gap-4">
          <div class="flex-1 min-w-0">
            <div class="flex items-center gap-2 mb-1 flex-wrap">
              <h3 class="font-semibold text-slate-900">{{ a.title }}</h3>
              <span class="badge" :class="audienceConfig[a.audience] ?? ''">{{ a.audience }}</span>
            </div>
            <p class="text-[13px] text-slate-500 line-clamp-2">{{ a.body }}</p>
            <p class="text-[11px] text-slate-400 mt-2">Published {{ formatDate(a.published_at) }}</p>
          </div>
          <div class="flex gap-2 shrink-0">
            <Button variant="outline" size="sm" @click="openEdit(a)">Edit</Button>
            <Button variant="ghost" size="sm" class="text-rose-500 hover:text-rose-700 hover:bg-rose-50" @click="remove(a)">Delete</Button>
          </div>
        </div>
      </div>
    </div>

    <!-- Form dialog -->
    <Dialog :open="showForm" @update:open="(v) => !v && (showForm = false)">
      <DialogContent class="max-w-lg">
        <DialogHeader>
          <DialogTitle>{{ editing ? 'Edit' : 'New' }} Announcement</DialogTitle>
        </DialogHeader>
        <div class="space-y-4">
          <div class="space-y-1.5">
            <Label class="text-[12px] font-semibold text-slate-500 uppercase tracking-wider">Title</Label>
            <Input v-model="form.title" type="text" placeholder="Announcement title..." />
          </div>
          <div class="space-y-1.5">
            <Label class="text-[12px] font-semibold text-slate-500 uppercase tracking-wider">Message</Label>
            <RichTextEditor v-model="form.body" placeholder="Write your announcement..." />
          </div>
          <div class="space-y-1.5">
            <Label class="text-[12px] font-semibold text-slate-500 uppercase tracking-wider">Audience</Label>
            <AppCombobox
              v-model="form.audience"
              :options="audienceOptions"
              placeholder="Select audience..."
              search-placeholder="Search..."
              class="w-full"
            />
          </div>
        </div>
        <DialogFooter class="gap-2 mt-2">
          <Button variant="outline" @click="showForm = false">Cancel</Button>
          <Button @click="save" :disabled="saving || !form.title || !form.body">
            {{ saving ? 'Saving...' : (editing ? 'Update' : 'Publish') }}
          </Button>
        </DialogFooter>
      </DialogContent>
    </Dialog>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'

definePageMeta({ middleware: 'auth' })

const { apiFetch } = useApi()
const data = ref<any>({ data: [] })
const loading = ref(true)
const showForm = ref(false)

const form = ref({ title: '', body: '', audience: 'students', class_id: '' })
const saving = ref(false)
const editing = ref<any>(null)

onMounted(async () => {
  try { data.value = await apiFetch('/teacher/announcements') } catch {}
  loading.value = false
})

const announcements = computed(() => data.value?.data ?? [])

const openNew = () => { editing.value = null; form.value = { title: '', body: '', audience: 'students', class_id: '' }; showForm.value = true }
const openEdit = (a: any) => { editing.value = a; form.value = { title: a.title, body: a.body, audience: a.audience, class_id: a.class_id ?? '' }; showForm.value = true }

const save = async () => {
  saving.value = true
  try {
    if (editing.value) {
      await apiFetch(`/teacher/announcements/${editing.value.id}`, { method: 'PUT', body: form.value })
    } else {
      await apiFetch('/teacher/announcements', { method: 'POST', body: form.value })
    }
    showForm.value = false
    data.value = await apiFetch('/teacher/announcements')
  } catch {}
  saving.value = false
}

const remove = async (a: any) => {
  if (!confirm('Delete this announcement?')) return
  try {
    await apiFetch(`/teacher/announcements/${a.id}`, { method: 'DELETE' })
    data.value.data = data.value.data.filter((x: any) => x.id !== a.id)
  } catch {}
}

const audienceOptions = [
  { value: 'students', label: 'Students only' },
  { value: 'teachers', label: 'Teachers only' },
  { value: 'all', label: 'Everyone' },
]

const audienceConfig: Record<string, string> = {
  all:      'bg-violet-50 text-violet-700 ring-1 ring-violet-200',
  students: 'bg-sky-50 text-sky-700 ring-1 ring-sky-200',
  teachers: 'bg-amber-50 text-amber-700 ring-1 ring-amber-200',
}

const formatDate = (d: string) => d ? new Date(d).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' }) : '—'
</script>
