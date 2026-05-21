<template>
  <div class="animate-fade-in">
    <div class="flex items-center justify-between mb-7">
      <div>
        <h2 class="text-2xl font-bold text-slate-900 tracking-tight">{{ __('Announcements') }}</h2>
        <p class="text-sm text-slate-500 mt-1">{{ __('Post and manage class announcements') }}</p>
      </div>
      <Button @click="openNew" class="flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        {{ __('New Announcement') }}
      </Button>
    </div>

    <div v-if="loading" class="card p-12 text-center text-slate-400 text-sm">{{ __('Loading...') }}</div>

    <div v-else class="space-y-4">
      <div v-if="!announcements.length" class="card overflow-hidden">
        <Empty class="border-0 rounded-none">
          <EmptyHeader>
            <EmptyMedia variant="icon"><Megaphone class="w-6 h-6" /></EmptyMedia>
            <EmptyTitle>{{ __('No announcements yet') }}</EmptyTitle>
            <EmptyDescription>{{ __('Create your first announcement to notify your students.') }}</EmptyDescription>
          </EmptyHeader>
        </Empty>
      </div>
      <div v-for="a in announcements" :key="a.id" class="card p-5 animate-fade-in-up">
        <div class="flex items-start justify-between gap-4">
          <div class="flex-1 min-w-0">
            <div class="flex items-center gap-2 mb-1 flex-wrap">
              <h3 class="font-semibold text-slate-900">{{ a.title }}</h3>
              <span class="badge" :class="audienceConfig[a.audience] ?? ''">{{ __(audienceLabel(a.audience)) }}</span>
            </div>
            <div class="text-[13px] text-slate-500 line-clamp-2 prose prose-sm max-w-none" v-html="a.body"></div>
            <p class="text-[11px] text-slate-400 mt-2">{{ __('Published {date}', { date: formatDate(a.published_at) }) }}</p>
          </div>
          <div class="flex gap-2 shrink-0">
            <Button variant="outline" size="sm" @click="openEdit(a)">{{ __('Edit') }}</Button>
            <Button variant="ghost" size="sm" class="text-rose-500 hover:text-rose-700 hover:bg-rose-50" @click="remove(a)">{{ __('Delete') }}</Button>
          </div>
        </div>
      </div>
    </div>

    <!-- Form dialog -->
    <Dialog :open="showForm" @update:open="(v) => !v && (showForm = false)">
      <DialogContent class="max-w-lg">
        <DialogHeader>
          <DialogTitle>{{ editing ? __('Edit Announcement') : __('New Announcement') }}</DialogTitle>
        </DialogHeader>
        <div class="space-y-4">
          <div class="space-y-1.5">
            <Label class="text-[12px] font-semibold text-slate-500 uppercase tracking-wider">{{ __('Title') }}</Label>
            <TranslatableInput v-model="form.title" :placeholder="{ en: 'Announcement title...', km: 'ចំណង​ជើង​សេចក្តី​ប្រកាស...', zh: '公告标题...' }" />
          </div>
          <div class="space-y-1.5">
            <Label class="text-[12px] font-semibold text-slate-500 uppercase tracking-wider">{{ __('Message') }}</Label>
            <TranslatableRichText v-model="form.body" :placeholder="{ en: 'Write your announcement...', km: 'សរសេរ​សេចក្តី​ប្រកាស​របស់​អ្នក...', zh: '撰写您的公告...' }" />
          </div>
          <div class="space-y-1.5">
            <Label class="text-[12px] font-semibold text-slate-500 uppercase tracking-wider">{{ __('Audience') }}</Label>
            <AppCombobox
              v-model="form.audience"
              :options="audienceOptions"
              :placeholder="__('Select audience...')"
              search-placeholder="Search..."
              class="w-full"
            />
          </div>
        </div>
        <DialogFooter class="gap-2 mt-2">
          <Button variant="outline" @click="showForm = false">{{ __('Cancel') }}</Button>
          <Button @click="save" :disabled="saving || !(form.title?.en || '').trim() || !(form.body?.en || '').trim()">
            {{ saving ? __('Saving...') : (editing ? __('Update') : __('Publish')) }}
          </Button>
        </DialogFooter>
      </DialogContent>
    </Dialog>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import { Megaphone } from 'lucide-vue-next'
import TranslatableInput from '~/components/TranslatableInput.vue'
import TranslatableRichText from '~/components/TranslatableRichText.vue'

definePageMeta({ middleware: 'auth' })

const { apiFetch } = useApi()
const data = ref<any>({ data: [] })
const loading = ref(true)
const showForm = ref(false)

const emptyTr = () => ({ en: '', km: '', zh: '' })
const readTr = (v: any) => {
  if (!v) return emptyTr()
  if (typeof v === 'string') return { ...emptyTr(), en: v }
  return { ...emptyTr(), ...v }
}

const form = ref<{ title: Record<string, string>, body: Record<string, string>, audience: string, class_id: string | number }>({
  title: emptyTr(),
  body: emptyTr(),
  audience: 'students',
  class_id: '',
})
const saving = ref(false)
const editing = ref<any>(null)

onMounted(async () => {
  try { data.value = await apiFetch('/teacher/announcements') } catch {}
  loading.value = false
})

const announcements = computed(() => data.value?.data ?? [])

const openNew = () => {
  editing.value = null
  form.value = { title: emptyTr(), body: emptyTr(), audience: 'students', class_id: '' }
  showForm.value = true
}
const openEdit = (a: any) => {
  editing.value = a
  form.value = {
    title: readTr(a.title_translations ?? a.title),
    body: readTr(a.body_translations ?? a.body),
    audience: a.audience,
    class_id: a.class_id ?? '',
  }
  showForm.value = true
}

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
  if (!confirm(__('Delete this announcement?'))) return
  try {
    await apiFetch(`/teacher/announcements/${a.id}`, { method: 'DELETE' })
    data.value.data = data.value.data.filter((x: any) => x.id !== a.id)
  } catch {}
}

const audienceOptions = computed(() => [
  { value: 'students', label: __('Students only') },
  { value: 'teachers', label: __('Teachers only') },
  { value: 'all',      label: __('Everyone') },
])

const audienceConfig: Record<string, string> = {
  all:      'bg-violet-50 text-violet-700 ring-1 ring-violet-200',
  students: 'bg-sky-50 text-sky-700 ring-1 ring-sky-200',
  teachers: 'bg-amber-50 text-amber-700 ring-1 ring-amber-200',
}

const audienceLabel = (a: string) => ({
  all:      'Everyone',
  students: 'Students only',
  teachers: 'Teachers only',
} as Record<string, string>)[a] ?? a

const { locale } = useI18n()
const dateLocale = computed(() => ({ en: 'en-US', km: 'km-KH', zh: 'zh-CN' } as Record<string, string>)[locale.value] ?? 'en-US')
const formatDate = (d: string) => d ? new Date(d).toLocaleDateString(dateLocale.value, { year: 'numeric', month: 'short', day: 'numeric' }) : '—'
</script>
