<script setup lang="ts">
definePageMeta({ middleware: 'auth' })

const { apiFetch } = useApi()

const classes = ref<any[]>([])
const timeSlots = ref<any[]>([])
const students = ref<any[]>([])
const selectedClass = ref('')
const selectedTimeSlot = ref('')
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
    success.value = 'Attendance recorded successfully!'
  } catch (e: any) {
    error.value = e?.data?.message || 'Failed to record attendance.'
  }
  submitting.value = false
}
</script>

<template>
  <div class="animate-fade-in">
    <div class="mb-8">
      <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Mark Attendance</h2>
      <p class="text-sm text-slate-500 mt-1">Record attendance for your class</p>
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
          <label class="block text-[11px] font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Class - Subject</label>
          <select v-model="selectedClass" @change="loadStudents" class="select-modern w-full">
            <option value="" disabled>Select Class - Subject</option>
            <option v-for="c in classes" :key="c.id" :value="c.id">{{ c.school_class?.name }} - {{ c.subject?.name }}</option>
          </select>
        </div>
        <div>
          <label class="block text-[11px] font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Time Slot</label>
          <select v-model="selectedTimeSlot" class="select-modern">
            <option value="" disabled>Select Time Slot</option>
            <option v-for="ts in timeSlots" :key="ts.id" :value="ts.id">{{ ts.name }} ({{ ts.start_time?.slice(0,5) }} - {{ ts.end_time?.slice(0,5) }})</option>
          </select>
        </div>
        <div>
          <label class="block text-[11px] font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Date</label>
          <input v-model="selectedDate" type="date" class="input-modern" />
        </div>
        <button v-if="students.length" @click="markAllPresent" class="btn-secondary">
          <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
          Mark All Present
        </button>
      </div>
    </div>

    <!-- Student Table -->
    <div v-if="students.length" class="card overflow-hidden">
      <table class="modern-table">
        <thead>
          <tr>
            <th class="text-left">Student</th>
            <th class="text-center">Present</th>
            <th class="text-center">Absent</th>
            <th class="text-center">Late</th>
            <th class="text-center">Excused</th>
            <th class="text-left">Remarks</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="student in students" :key="student.id">
            <td class="font-semibold text-slate-900">{{ student.name }}</td>
            <td class="text-center"><input type="radio" :name="`status-${student.id}`" value="present" v-model="attendances[student.id].status" class="w-4 h-4 text-emerald-600 focus:ring-emerald-500" /></td>
            <td class="text-center"><input type="radio" :name="`status-${student.id}`" value="absent" v-model="attendances[student.id].status" class="w-4 h-4 text-rose-600 focus:ring-rose-500" /></td>
            <td class="text-center"><input type="radio" :name="`status-${student.id}`" value="late" v-model="attendances[student.id].status" class="w-4 h-4 text-amber-600 focus:ring-amber-500" /></td>
            <td class="text-center"><input type="radio" :name="`status-${student.id}`" value="excused" v-model="attendances[student.id].status" class="w-4 h-4 text-sky-600 focus:ring-sky-500" /></td>
            <td><input v-model="attendances[student.id].remarks" type="text" class="input-modern !py-1.5" placeholder="Optional" /></td>
          </tr>
        </tbody>
      </table>
      <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50">
        <button @click="submit" :disabled="submitting" class="btn-primary">
          <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M5 13l4 4L19 7"/></svg>
          {{ submitting ? 'Submitting...' : 'Submit Attendance' }}
        </button>
      </div>
    </div>

    <div v-else-if="selectedClass && !loading" class="card p-12 text-center text-slate-400">
      No students found for this class.
    </div>
  </div>
</template>
