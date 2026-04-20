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

const audienceConfig: Record<string, string> = {
  all:      'bg-violet-50 text-violet-700 ring-1 ring-violet-200',
  students: 'bg-sky-50 text-sky-700 ring-1 ring-sky-200',
  teachers: 'bg-amber-50 text-amber-700 ring-1 ring-amber-200',
}

const formatDate = (d: string) => d ? new Date(d).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' }) : '—'
</script>

<template>
  <div class="animate-fade-in">
    <div class="flex items-center justify-between mb-7">
      <div>
        <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Announcements</h2>
        <p class="text-sm text-slate-500 mt-1">Post and manage class announcements</p>
      </div>
      <button @click="openNew" class="btn-primary py-2.5 text-[13px]">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        New Announcement
      </button>
    </div>

    <div v-if="loading" class="card p-12 text-center text-slate-400 text-sm">Loading...</div>

    <div v-else class="space-y-4">
      <div v-if="!announcements.length" class="card p-12 text-center text-slate-400">
        No announcements yet. Create your first one.
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
            <button @click="openEdit(a)" class="btn-secondary py-1.5 px-3 text-[12px]">Edit</button>
            <button @click="remove(a)" class="py-1.5 px-3 rounded-lg text-[12px] font-medium text-rose-500 hover:bg-rose-50 transition-colors">Delete</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Form modal -->
    <Teleport to="body">
      <Transition name="modal">
        <div v-if="showForm" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-sm" @click.self="showForm = false">
          <div class="w-full max-w-lg bg-white rounded-2xl shadow-2xl p-6">
            <h3 class="text-lg font-bold text-slate-900 mb-5">{{ editing ? 'Edit' : 'New' }} Announcement</h3>
            <div class="space-y-4">
              <div>
                <label class="block text-[12px] font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Title</label>
                <input v-model="form.title" type="text" class="input-modern w-full" placeholder="Announcement title..." />
              </div>
              <div>
                <label class="block text-[12px] font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Message</label>
                <textarea v-model="form.body" rows="4" class="input-modern w-full resize-none" placeholder="Write your announcement..."></textarea>
              </div>
              <div>
                <label class="block text-[12px] font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Audience</label>
                <select v-model="form.audience" class="select-modern w-full">
                  <option value="students">Students only</option>
                  <option value="teachers">Teachers only</option>
                  <option value="all">Everyone</option>
                </select>
              </div>
            </div>
            <div class="flex gap-3 mt-6">
              <button @click="save" :disabled="saving || !form.title || !form.body" class="btn-primary flex-1 justify-center py-2.5 text-[13px]">
                {{ saving ? 'Saving...' : (editing ? 'Update' : 'Publish') }}
              </button>
              <button @click="showForm = false" class="btn-secondary py-2.5 px-5 text-[13px]">Cancel</button>
            </div>
          </div>
        </div>
      </Transition>
    </Teleport>
  </div>
</template>

<style scoped>
.modal-enter-active, .modal-leave-active { transition: opacity 0.2s; }
.modal-enter-from, .modal-leave-to { opacity: 0; }
</style>
