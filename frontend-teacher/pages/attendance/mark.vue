<script setup lang="ts">
definePageMeta({ middleware: 'auth' })

const { apiFetch } = useApi()

const classes = ref<any[]>([])
const students = ref<any[]>([])
const selectedClass = ref('')
const selectedDate = ref(new Date().toISOString().split('T')[0])
const attendances = ref<Record<number, { status: string; remarks: string }>>({})
const loading = ref(true)
const submitting = ref(false)
const success = ref('')
const error = ref('')

onMounted(async () => {
  try {
    classes.value = await apiFetch('/teacher/classes')
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
  <div>
    <h2 class="text-2xl font-bold text-gray-900 mb-6">Mark Attendance</h2>

    <div v-if="success" class="mb-4 px-4 py-3 rounded-md bg-green-50 border border-green-200 text-sm text-green-800">{{ success }}</div>
    <div v-if="error" class="mb-4 px-4 py-3 rounded-md bg-red-50 border border-red-200 text-sm text-red-800">{{ error }}</div>

    <!-- Select class and date -->
    <div class="flex gap-4 mb-6">
      <select v-model="selectedClass" @change="loadStudents" class="px-3 py-2 border border-gray-300 rounded-md text-sm">
        <option value="" disabled>Select Class - Subject</option>
        <option v-for="c in classes" :key="c.id" :value="c.id">
          {{ c.school_class?.name }} - {{ c.subject?.name }}
        </option>
      </select>
      <input v-model="selectedDate" type="date" class="px-3 py-2 border border-gray-300 rounded-md text-sm" />
      <button v-if="students.length" @click="markAllPresent" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
        Mark All Present
      </button>
    </div>

    <!-- Student list -->
    <div v-if="students.length" class="bg-white rounded-lg border border-gray-200 overflow-hidden">
      <table class="w-full">
        <thead>
          <tr class="border-b border-gray-200 bg-gray-50">
            <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Student</th>
            <th class="text-center px-6 py-3 text-xs font-medium text-gray-500 uppercase">Present</th>
            <th class="text-center px-6 py-3 text-xs font-medium text-gray-500 uppercase">Absent</th>
            <th class="text-center px-6 py-3 text-xs font-medium text-gray-500 uppercase">Late</th>
            <th class="text-center px-6 py-3 text-xs font-medium text-gray-500 uppercase">Excused</th>
            <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Remarks</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="student in students" :key="student.id" class="border-b border-gray-100">
            <td class="px-6 py-3 text-sm font-medium text-gray-900">{{ student.name }}</td>
            <td class="px-6 py-3 text-center">
              <input type="radio" :name="`status-${student.id}`" value="present" v-model="attendances[student.id].status" />
            </td>
            <td class="px-6 py-3 text-center">
              <input type="radio" :name="`status-${student.id}`" value="absent" v-model="attendances[student.id].status" />
            </td>
            <td class="px-6 py-3 text-center">
              <input type="radio" :name="`status-${student.id}`" value="late" v-model="attendances[student.id].status" />
            </td>
            <td class="px-6 py-3 text-center">
              <input type="radio" :name="`status-${student.id}`" value="excused" v-model="attendances[student.id].status" />
            </td>
            <td class="px-6 py-3">
              <input v-model="attendances[student.id].remarks" type="text" class="w-full px-2 py-1 border border-gray-300 rounded text-sm" placeholder="Optional" />
            </td>
          </tr>
        </tbody>
      </table>

      <div class="px-6 py-4 border-t border-gray-200">
        <button @click="submit" :disabled="submitting" class="px-6 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 disabled:opacity-50">
          {{ submitting ? 'Submitting...' : 'Submit Attendance' }}
        </button>
      </div>
    </div>

    <div v-else-if="selectedClass && !loading" class="bg-white rounded-lg border border-gray-200 p-8 text-center text-sm text-gray-500">
      No students found for this class.
    </div>
  </div>
</template>
