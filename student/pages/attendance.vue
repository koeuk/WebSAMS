<template>
  <div class="animate-fade-in">
    <div class="mb-7">
      <h2 class="text-2xl font-bold text-slate-900 tracking-tight">{{ __('My Attendance') }}</h2>
      <p class="text-sm text-slate-500 mt-1">{{ __('View and filter your attendance records') }}</p>
    </div>

    <!-- Filters -->
    <div class="card p-4 mb-5">
      <div class="flex flex-wrap gap-3 items-end">
        <div>
          <label class="block text-[11px] font-semibold text-slate-400 uppercase tracking-wider mb-1.5">{{ __('From') }}</label>
          <DatePicker v-model="dateFrom" :placeholder="__('Start date')" @update:model-value="loadAttendance" />
        </div>
        <div>
          <label class="block text-[11px] font-semibold text-slate-400 uppercase tracking-wider mb-1.5">{{ __('To') }}</label>
          <DatePicker v-model="dateTo" :placeholder="__('End date')" @update:model-value="loadAttendance" />
        </div>
        <Button
          v-if="dateFrom || dateTo"
          variant="outline"
          @click="dateFrom = ''; dateTo = ''; loadAttendance()"
          class="flex items-center gap-2"
        >
          {{ __('Clear') }}
        </Button>
      </div>
    </div>

    <!-- Mini stats (shown when data exists) -->
    <div v-if="!loading && stats.total > 0" class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-5 stagger-children">
      <div class="card stat-card stat-card-blue px-4 py-3 animate-fade-in-up">
        <p class="text-[11px] font-semibold text-slate-400 uppercase tracking-wide">{{ __('Total') }}</p>
        <p class="text-xl font-extrabold text-slate-900 mt-1">{{ stats.total }}</p>
      </div>
      <div class="card stat-card stat-card-emerald px-4 py-3 animate-fade-in-up">
        <p class="text-[11px] font-semibold text-slate-400 uppercase tracking-wide">{{ __('Present') }}</p>
        <p class="text-xl font-extrabold text-emerald-600 mt-1">{{ stats.present }}</p>
      </div>
      <div class="card stat-card stat-card-rose px-4 py-3 animate-fade-in-up">
        <p class="text-[11px] font-semibold text-slate-400 uppercase tracking-wide">{{ __('Absent') }}</p>
        <p class="text-xl font-extrabold text-rose-600 mt-1">{{ stats.absent }}</p>
      </div>
      <div class="card stat-card stat-card-amber px-4 py-3 animate-fade-in-up">
        <p class="text-[11px] font-semibold text-slate-400 uppercase tracking-wide">{{ __('Rate') }}</p>
        <p class="text-xl font-extrabold mt-1" :class="stats.rate >= 80 ? 'text-emerald-600' : 'text-rose-600'">
          {{ stats.rate }}%
        </p>
      </div>
    </div>

    <div v-if="loading" class="card p-12 text-center text-slate-400 text-sm">{{ __('Loading...') }}</div>

    <div v-else class="card overflow-hidden">
      <Table>
        <TableHeader>
          <TableRow>
            <TableHead>{{ __('Date') }}</TableHead>
            <TableHead>{{ __('Time Slot') }}</TableHead>
            <TableHead>{{ __('Class') }}</TableHead>
            <TableHead>{{ __('Subject') }}</TableHead>
            <TableHead>{{ __('Status') }}</TableHead>
            <TableHead>{{ __('Remarks') }}</TableHead>
          </TableRow>
        </TableHeader>
        <TableBody>
          <TableRow v-for="record in records" :key="record.id">
            <TableCell class="font-semibold text-slate-900">{{ formatDate(record.date) }}</TableCell>
            <TableCell class="text-slate-500">{{ record.time_slot?.name || '-' }}</TableCell>
            <TableCell>{{ record.class_subject?.school_class?.name }}</TableCell>
            <TableCell>{{ record.class_subject?.subject?.name }}</TableCell>
            <TableCell>
              <span class="badge" :class="statusConfig[record.status] ?? ''">{{ __(({ present: 'Present', absent: 'Absent', late: 'Late', excused: 'Excused' } as Record<string, string>)[record.status] ?? record.status) }}</span>
            </TableCell>
            <TableCell class="text-slate-500">{{ record.remarks || '-' }}</TableCell>
          </TableRow>
          <TableRow v-if="!records.length">
            <TableCell colspan="6" class="p-0">
              <Empty class="border-0 rounded-none">
                <EmptyHeader>
                  <EmptyMedia variant="icon"><ClipboardList class="w-6 h-6" /></EmptyMedia>
                  <EmptyTitle>{{ __('No attendance records') }}</EmptyTitle>
                  <EmptyDescription>{{ __('No records found for the selected date range.') }}</EmptyDescription>
                </EmptyHeader>
              </Empty>
            </TableCell>
          </TableRow>
        </TableBody>
      </Table>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import { ClipboardList } from 'lucide-vue-next'

definePageMeta({ middleware: 'auth' })

const { apiFetch } = useApi()

const attendance = ref<any>({ data: [] })
const dateFrom = ref('')
const dateTo = ref('')
const loading = ref(true)

onMounted(async () => {
  await loadAttendance()
  loading.value = false
})

const loadAttendance = async () => {
  const params = new URLSearchParams()
  if (dateFrom.value) params.set('filter[date_from]', dateFrom.value)
  if (dateTo.value) params.set('filter[date_to]', dateTo.value)
  try { attendance.value = await apiFetch(`/student/attendance?${params.toString()}`) } catch {}
}

const records = computed(() => attendance.value?.data ?? [])

const stats = computed(() => {
  const data = records.value
  const total = data.length
  const present = data.filter((r: any) => r.status === 'present').length
  const absent = data.filter((r: any) => r.status === 'absent').length
  const late = data.filter((r: any) => r.status === 'late').length
  const rate = total ? Math.round((present / total) * 100) : 0
  return { total, present, absent, late, rate }
})

const statusConfig: Record<string, string> = {
  present: 'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-200',
  absent:  'bg-rose-50 text-rose-700 ring-1 ring-rose-200',
  late:    'bg-amber-50 text-amber-700 ring-1 ring-amber-200',
  excused: 'bg-sky-50 text-sky-700 ring-1 ring-sky-200',
}

const { locale } = useI18n()
const dateLocale = computed(() => ({ en: 'en-US', km: 'km-KH', zh: 'zh-CN' } as Record<string, string>)[locale.value] ?? 'en-US')
const formatDate = (d: string | null | undefined) => {
  if (!d) return '-'
  const date = new Date(d)
  if (isNaN(date.getTime())) return d
  return date.toLocaleDateString(dateLocale.value, { year: 'numeric', month: 'short', day: 'numeric' })
}
</script>
