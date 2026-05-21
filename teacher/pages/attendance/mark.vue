<template>
  <div class="animate-fade-in">
    <div class="mb-8">
      <h2 class="text-2xl font-bold text-slate-900 tracking-tight">{{ __('Mark Attendance') }}</h2>
      <p class="text-sm text-slate-500 mt-1">{{ __('Record attendance for your class') }}</p>
    </div>

    <div v-if="success" class="mb-4 px-4 py-3 rounded-xl bg-emerald-50 border border-emerald-200 text-[13px] text-emerald-700 flex items-center gap-2">
      <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
      {{ success }}
    </div>
    <div v-if="error" class="mb-4 px-4 py-3 rounded-xl bg-rose-50 border border-rose-200 text-[13px] text-rose-700">{{ error }}</div>

    <!-- Selection Controls -->
    <div class="card p-4 mb-6">
      <div class="flex flex-wrap gap-3 items-end">
        <div class="min-w-[250px]">
          <label class="block text-[11px] font-semibold text-slate-400 uppercase tracking-wider mb-1.5">{{ __('Class') }} - {{ __('Subject') }}</label>
          <AppCombobox
            v-model="selectedClass"
            :options="classOptions"
            :placeholder="__('Select Class - Subject')"
            :search-placeholder="__('Search class...')"
            class="w-full"
            @update:model-value="loadStudents"
          />
        </div>
        <div>
          <label class="block text-[11px] font-semibold text-slate-400 uppercase tracking-wider mb-1.5">{{ __('Time Slot') }}</label>
          <AppCombobox
            v-model="selectedTimeSlot"
            :options="timeSlotOptions"
            :placeholder="__('Select Time Slot')"
            :search-placeholder="__('Search time slot...')"
          />
        </div>
        <div>
          <label class="block text-[11px] font-semibold text-slate-400 uppercase tracking-wider mb-1.5">{{ __('Date') }}</label>
          <AppDatePicker v-model="selectedDate" />
        </div>
        <Button v-if="students.length" variant="outline" @click="markAllPresent" class="flex items-center gap-2">
          <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
          {{ __('Mark all present') }}
        </Button>
      </div>
    </div>

    <!-- Student Table -->
    <div v-if="students.length" class="card overflow-hidden">
      <Table>
        <TableHeader>
          <TableRow>
            <TableHead>{{ __('Student') }}</TableHead>
            <TableHead>{{ __('Status') }}</TableHead>
            <TableHead>{{ __('Remarks') }}</TableHead>
          </TableRow>
        </TableHeader>
        <TableBody>
          <TableRow v-for="student in students" :key="student.id">
            <TableCell class="font-semibold text-slate-900">{{ student.name }}</TableCell>
            <TableCell>
              <RadioGroup v-model="attendances[student.id].status" class="flex gap-4">
                <div v-for="s in statusList" :key="s.value" class="flex items-center gap-1.5">
                  <RadioGroupItem :value="s.value" :id="`${s.value}-${student.id}`" :class="s.radioClass" />
                  <Label :for="`${s.value}-${student.id}`" class="text-[13px] cursor-pointer" :class="s.labelClass">{{ __(s.label) }}</Label>
                </div>
              </RadioGroup>
            </TableCell>
            <TableCell>
              <Input v-model="attendances[student.id].remarks" type="text" class="py-1.5 h-auto" :placeholder="__('Optional')" />
            </TableCell>
          </TableRow>
        </TableBody>
      </Table>
      <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50">
        <Button @click="submit" :disabled="submitting" class="flex items-center gap-2">
          <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M5 13l4 4L19 7"/></svg>
          {{ submitting ? __('Saving...') : __('Save Attendance') }}
        </Button>
      </div>
    </div>

    <div v-else-if="selectedClass && !loading" class="card p-12 text-center text-slate-400">
      {{ __('No students enrolled in this class.') }}
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue'

definePageMeta({ middleware: 'auth' })

const { apiFetch } = useApi()

const classes = ref<any[]>([])
const timeSlots = ref<any[]>([])
const students = ref<any[]>([])
const selectedClass = ref('')
const selectedTimeSlot = ref('')

const classOptions = computed(() =>
  classes.value.map((c: any) => ({
    value: String(c.id),
    label: `${c.school_class?.name} - ${c.subject?.name}`,
  }))
)
const timeSlotOptions = computed(() =>
  timeSlots.value.map((ts: any) => ({
    value: String(ts.id),
    label: `${ts.name} (${ts.start_time?.slice(0, 5)} - ${ts.end_time?.slice(0, 5)})`,
  }))
)
const selectedDate = ref(new Date().toISOString().split('T')[0])
const attendances = ref<Record<number, { status: string; remarks: string }>>({})
const loading = ref(true)
const submitting = ref(false)
const success = ref('')
const error = ref('')

onMounted(async () => {
  try {
    classes.value = await apiFetch('/teacher/classes')
    timeSlots.value = await apiFetch('/teacher/time-slots')
  } catch {}
  loading.value = false
})

const loadStudents = async () => {
  if (!selectedClass.value) return
  students.value = []
  try {
    students.value = await apiFetch(`/teacher/classes/${selectedClass.value}/students`)
    attendances.value = {}
    students.value.forEach((s: any) => {
      attendances.value[s.id] = { status: 'present', remarks: '' }
    })
  } catch {}
}

const markAllPresent = () => {
  Object.keys(attendances.value).forEach((id) => {
    attendances.value[Number(id)].status = 'present'
  })
}

const submit = async () => {
  submitting.value = true
  success.value = ''
  error.value = ''
  try {
    const payload = {
      class_subject_id: Number(selectedClass.value),
      date: selectedDate.value,
      time_slot_id: Number(selectedTimeSlot.value),
      attendances: Object.entries(attendances.value).map(([studentId, data]) => ({
        student_id: Number(studentId),
        status: data.status,
        remarks: data.remarks || null,
      })),
    }
    await apiFetch('/teacher/attendance', { method: 'POST', body: payload })
    success.value = __('Attendance saved')
  } catch (e: any) {
    error.value = e?.data?.message || ''
  }
  submitting.value = false
}

const statusList = [
  { value: 'present', label: 'Present', radioClass: 'border-emerald-500 text-emerald-500', labelClass: 'text-emerald-700' },
  { value: 'absent',  label: 'Absent',  radioClass: 'border-rose-500 text-rose-500',       labelClass: 'text-rose-700' },
  { value: 'late',    label: 'Late',    radioClass: 'border-amber-500 text-amber-500',      labelClass: 'text-amber-700' },
  { value: 'excused', label: 'Excused', radioClass: 'border-sky-500 text-sky-500',          labelClass: 'text-sky-700' },
]
</script>
