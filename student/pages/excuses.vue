<template>
  <div class="animate-fade-in">
    <div class="mb-7">
      <h2 class="text-2xl font-bold text-slate-900 tracking-tight">{{ __('Excuse Requests') }}</h2>
      <p class="text-sm text-slate-500 mt-1">{{ __('Submit excuses for absent attendance records') }}</p>
    </div>

    <div v-if="successMsg" class="mb-5 flex items-center gap-2.5 px-4 py-3 rounded-xl bg-emerald-50 border border-emerald-200 text-[13px] text-emerald-700 animate-fade-in">
      <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M20 6L9 17l-5-5"/></svg>
      {{ successMsg }}
    </div>

    <div v-if="loading" class="card p-12 text-center text-slate-400 text-sm">{{ __('Loading...') }}</div>

    <template v-else>
      <!-- Absences to excuse -->
      <div class="card overflow-hidden mb-6">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center gap-2">
          <span class="w-1.5 h-1.5 rounded-full bg-rose-400"></span>
          <h3 class="text-sm font-semibold text-slate-900 uppercase tracking-wider">{{ __('Absent Records') }}</h3>
        </div>
        <Table>
          <TableHeader>
            <TableRow>
              <TableHead>{{ __('Date') }}</TableHead>
              <TableHead>{{ __('Class') }}</TableHead>
              <TableHead>{{ __('Subject') }}</TableHead>
              <TableHead>{{ __('Action') }}</TableHead>
            </TableRow>
          </TableHeader>
          <TableBody>
            <TableRow v-for="r in absences" :key="r.id">
              <TableCell class="font-semibold text-slate-900">{{ r.date }}</TableCell>
              <TableCell>{{ r.class_subject?.school_class?.name }}</TableCell>
              <TableCell>{{ r.class_subject?.subject?.name }}</TableCell>
              <TableCell>
                <Button
                  v-if="!pendingExcuseIds.has(r.id)"
                  variant="outline"
                  size="sm"
                  @click="openForm(r)"
                >{{ __('Request Excuse') }}</Button>
                <span v-else class="badge bg-amber-50 text-amber-700 ring-1 ring-amber-200">{{ __('Submitted') }}</span>
              </TableCell>
            </TableRow>
            <TableRow v-if="!absences.length">
              <TableCell colspan="4" class="p-0">
                <Empty class="border-0 rounded-none py-10">
                  <EmptyHeader>
                    <EmptyMedia variant="icon"><ClipboardCheck class="w-6 h-6" /></EmptyMedia>
                    <EmptyTitle>{{ __('No absent records') }}</EmptyTitle>
                    <EmptyDescription>{{ __('You have no unexcused absences.') }}</EmptyDescription>
                  </EmptyHeader>
                </Empty>
              </TableCell>
            </TableRow>
          </TableBody>
        </Table>
      </div>

      <!-- Submitted requests -->
      <div class="card overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center gap-2">
          <span class="w-1.5 h-1.5 rounded-full bg-beltei-gold"></span>
          <h3 class="text-sm font-semibold text-slate-900 uppercase tracking-wider">{{ __('My Requests') }}</h3>
        </div>
        <Table>
          <TableHeader>
            <TableRow>
              <TableHead>{{ __('Date') }}</TableHead>
              <TableHead>{{ __('Class') }}</TableHead>
              <TableHead>{{ __('Reason') }}</TableHead>
              <TableHead>{{ __('Status') }}</TableHead>
              <TableHead>{{ __('Note') }}</TableHead>
            </TableRow>
          </TableHeader>
          <TableBody>
            <TableRow v-for="e in requests" :key="e.id">
              <TableCell class="font-semibold text-slate-900">{{ formatDate(e.attendance?.date) }}</TableCell>
              <TableCell>{{ e.attendance?.class_subject?.school_class?.name }}</TableCell>
              <TableCell class="max-w-[180px] truncate text-slate-500">{{ e.reason }}</TableCell>
              <TableCell><span class="badge" :class="statusConfig[e.status]">{{ __(e.status) }}</span></TableCell>
              <TableCell class="text-slate-400">{{ e.reviewer_note || '—' }}</TableCell>
            </TableRow>
            <TableRow v-if="!requests.length">
              <TableCell colspan="5" class="p-0">
                <Empty class="border-0 rounded-none py-10">
                  <EmptyHeader>
                    <EmptyMedia variant="icon"><FileText class="w-6 h-6" /></EmptyMedia>
                    <EmptyTitle>{{ __('No requests submitted') }}</EmptyTitle>
                    <EmptyDescription>{{ __('Submit an excuse from the absent records above.') }}</EmptyDescription>
                  </EmptyHeader>
                </Empty>
              </TableCell>
            </TableRow>
          </TableBody>
        </Table>
      </div>
    </template>

    <!-- Excuse submission dialog -->
    <Dialog :open="showForm" @update:open="(v) => !v && (showForm = false)">
      <DialogContent class="max-w-md">
        <DialogHeader>
          <DialogTitle>{{ __('Request Excuse') }}</DialogTitle>
          <DialogDescription v-if="selectedAttendance">
            {{ selectedAttendance.date }} · {{ selectedAttendance.class_subject?.subject?.name }}
          </DialogDescription>
        </DialogHeader>
        <div class="space-y-1.5">
          <Label class="text-[12px] font-semibold text-slate-500 uppercase tracking-wider">{{ __('Reason') }}</Label>
          <Textarea
            v-model="reason"
            :rows="4"
            class="resize-none"
            :placeholder="__('Explain your reason for absence...')"
          />
        </div>
        <DialogFooter class="gap-2">
          <Button variant="outline" @click="showForm = false">{{ __('Cancel') }}</Button>
          <Button :disabled="submitting || !reason.trim()" @click="submit">
            {{ submitting ? __('Submitting...') : __('Submit Request') }}
          </Button>
        </DialogFooter>
      </DialogContent>
    </Dialog>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import { ClipboardCheck, FileText } from 'lucide-vue-next'

definePageMeta({ middleware: 'auth' })

const { apiFetch } = useApi()
const attendance = ref<any>({ data: [] })
const excuses = ref<any>({ data: [] })
const loading = ref(true)
const showForm = ref(false)
const selectedAttendance = ref<any>(null)
const reason = ref('')
const submitting = ref(false)
const successMsg = ref('')

onMounted(async () => {
  try {
    ;[attendance.value, excuses.value] = await Promise.all([
      apiFetch('/student/attendance'),
      apiFetch('/student/excuse-requests'),
    ])
  } catch {}
  loading.value = false
})

const absences = computed(() =>
  (attendance.value?.data ?? []).filter((r: any) => r.status === 'absent')
)

const pendingExcuseIds = computed(() =>
  new Set((excuses.value?.data ?? []).map((e: any) => e.attendance_id))
)

const requests = computed(() => excuses.value?.data ?? [])

const openForm = (record: any) => {
  selectedAttendance.value = record
  reason.value = ''
  showForm.value = true
}

const submit = async () => {
  if (!selectedAttendance.value || !reason.value.trim()) return
  submitting.value = true
  try {
    await apiFetch('/student/excuse-requests', {
      method: 'POST',
      body: { attendance_id: selectedAttendance.value.id, reason: reason.value },
    })
    showForm.value = false
    successMsg.value = __('Excuse request submitted successfully.')
    excuses.value = await apiFetch('/student/excuse-requests')
    setTimeout(() => { successMsg.value = '' }, 4000)
  } catch {}
  submitting.value = false
}

const statusConfig: Record<string, string> = {
  pending:  'bg-amber-50 text-amber-700 ring-1 ring-amber-200',
  approved: 'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-200',
  rejected: 'bg-rose-50 text-rose-700 ring-1 ring-rose-200',
}

const { locale } = useI18n()
const dateLocale = computed(() => ({ en: 'en-US', km: 'km-KH', zh: 'zh-CN' } as Record<string, string>)[locale.value] ?? 'en-US')
const formatDate = (d: string) => d ? new Date(d).toLocaleDateString(dateLocale.value, { year: 'numeric', month: 'short', day: 'numeric' }) : '-'
</script>
