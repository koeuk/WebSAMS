<template>
  <div class="animate-fade-in">
    <div class="mb-8">
      <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Attendance History</h2>
      <p class="text-sm text-slate-500 mt-1">View past attendance records for your classes</p>
    </div>

    <div class="card p-4 mb-6">
      <div class="flex flex-wrap gap-3 items-end">
        <div>
          <label class="block text-[11px] font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Class</label>
          <AppCombobox
            v-model="selectedClass"
            :options="classOptions"
            placeholder="All Classes"
            search-placeholder="Search class..."
            @update:model-value="loadAttendance"
          />
        </div>
        <div>
          <label class="block text-[11px] font-semibold text-slate-400 uppercase tracking-wider mb-1.5">From</label>
          <AppDatePicker v-model="dateFrom" placeholder="From date" @update:model-value="loadAttendance" />
        </div>
        <div>
          <label class="block text-[11px] font-semibold text-slate-400 uppercase tracking-wider mb-1.5">To</label>
          <AppDatePicker v-model="dateTo" placeholder="To date" @update:model-value="loadAttendance" />
        </div>
        <Button
          v-if="hasFilters"
          variant="outline"
          @click="clearFilters"
          class="self-end flex items-center gap-2 text-slate-500"
        >
          <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M18 6L6 18M6 6l12 12"/></svg>
          Clear Filters
        </Button>
      </div>
    </div>

    <div v-if="loading" class="card p-12 text-center text-slate-400 text-sm">Loading...</div>

    <div v-else class="card overflow-hidden">
      <Table>
        <TableHeader>
          <TableRow>
            <TableHead>Date</TableHead>
            <TableHead>Time Slot</TableHead>
            <TableHead>Student</TableHead>
            <TableHead>Class</TableHead>
            <TableHead>Subject</TableHead>
            <TableHead>Status</TableHead>
          </TableRow>
        </TableHeader>
        <TableBody>
          <TableRow v-for="record in attendance.data" :key="record.id">
            <TableCell class="font-medium text-slate-900">{{ record.date }}</TableCell>
            <TableCell>{{ record.time_slot?.name || '-' }}</TableCell>
            <TableCell class="font-semibold text-slate-900">{{ record.student?.name }}</TableCell>
            <TableCell>{{ record.class_subject?.school_class?.name }}</TableCell>
            <TableCell>{{ record.class_subject?.subject?.name }}</TableCell>
            <TableCell><span class="badge" :class="statusClass(record.status)">{{ record.status }}</span></TableCell>
          </TableRow>
          <TableRow v-if="!attendance.data?.length">
            <TableCell colspan="6" class="p-0">
              <Empty class="border-0 rounded-none">
                <EmptyHeader>
                  <EmptyMedia variant="icon"><ClipboardList class="w-6 h-6" /></EmptyMedia>
                  <EmptyTitle>No attendance records</EmptyTitle>
                  <EmptyDescription>Try adjusting your filters to find records.</EmptyDescription>
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
import { computed } from 'vue'

definePageMeta({ middleware: 'auth' })

const { apiFetch } = useApi()

const classes = ref<any[]>([])
const attendance = ref<any>({ data: [] })
const selectedClass = ref('')
const dateFrom = ref('')
const dateTo = ref('')
const loading = ref(true)

const classOptions = computed(() => [
  { value: '', label: 'All Classes' },
  ...classes.value.map((c: any) => ({
    value: String(c.id),
    label: `${c.school_class?.name} - ${c.subject?.name}`,
  })),
])

onMounted(async () => {
  try {
    classes.value = await apiFetch('/teacher/classes')
    await loadAttendance()
  } catch {}
  loading.value = false
})

const loadAttendance = async () => {
  const params = new URLSearchParams()
  if (selectedClass.value) params.set('filter[class_subject_id]', selectedClass.value)
  if (dateFrom.value) params.set('filter[date_from]', dateFrom.value)
  if (dateTo.value) params.set('filter[date_to]', dateTo.value)
  try {
    attendance.value = await apiFetch(`/teacher/attendance?${params.toString()}`)
  } catch {}
}

const hasFilters = computed(() => !!(selectedClass.value || dateFrom.value || dateTo.value))

const clearFilters = () => {
  selectedClass.value = ''
  dateFrom.value = ''
  dateTo.value = ''
  loadAttendance()
}

const statusClass = (status: string) => ({
  'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-200': status === 'present',
  'bg-rose-50 text-rose-700 ring-1 ring-rose-200': status === 'absent',
  'bg-amber-50 text-amber-700 ring-1 ring-amber-200': status === 'late',
  'bg-sky-50 text-sky-700 ring-1 ring-sky-200': status === 'excused',
})
</script>
