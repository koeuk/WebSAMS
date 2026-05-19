<template>
  <div class="animate-fade-in">
    <div class="mb-7">
      <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Excuse Requests</h2>
      <p class="text-sm text-slate-500 mt-1">Review and manage student excuse submissions</p>
    </div>

    <!-- Filter tabs -->
    <div class="card p-1 mb-5 inline-flex gap-1">
      <button
        v-for="tab in ['pending', 'approved', 'rejected']" :key="tab"
        @click="filter = tab; load()"
        class="px-4 py-1.5 rounded-lg text-[13px] font-medium capitalize transition-colors"
        :class="filter === tab ? 'bg-slate-900 text-white' : 'text-slate-500 hover:text-slate-700'"
      >{{ tab }}</button>
    </div>

    <div v-if="loading" class="card p-12 text-center text-slate-400 text-sm">Loading...</div>

    <div v-else class="card overflow-hidden">
      <table class="modern-table">
        <thead>
          <tr>
            <th class="text-left">Student</th>
            <th class="text-left">Date</th>
            <th class="text-left">Class / Subject</th>
            <th class="text-left">Reason</th>
            <th class="text-left">Status</th>
            <th class="text-left">Action</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="r in requests" :key="r.id">
            <td class="font-semibold text-slate-900">{{ r.student?.name }}</td>
            <td class="text-slate-500">{{ formatDate(r.attendance?.date) }}</td>
            <td>
              <span class="font-medium text-slate-700">{{ r.attendance?.class_subject?.school_class?.name }}</span>
              <span class="text-slate-400"> · {{ r.attendance?.class_subject?.subject?.name }}</span>
            </td>
            <td class="max-w-[180px] truncate text-slate-500">{{ r.reason }}</td>
            <td><span class="badge" :class="statusConfig[r.status]">{{ r.status }}</span></td>
            <td>
              <button
                v-if="r.status === 'pending'"
                @click="openReview(r)"
                class="btn-secondary py-1 px-3 text-[12px]"
              >Review</button>
              <span v-else class="text-slate-400 text-[12px]">—</span>
            </td>
          </tr>
          <tr v-if="!requests.length">
            <td colspan="6" class="!text-center !py-12 text-slate-400">No {{ filter }} excuse requests.</td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Review modal -->
    <Teleport to="body">
      <Transition name="modal">
        <div v-if="reviewing" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-sm" @click.self="reviewing = null">
          <div class="w-full max-w-md bg-white rounded-2xl shadow-2xl p-6">
            <h3 class="text-lg font-bold text-slate-900 mb-1">Review Excuse</h3>
            <p class="text-[13px] text-slate-500 mb-4">{{ reviewing.student?.name }} — {{ formatDate(reviewing.attendance?.date) }}</p>
            <div class="bg-slate-50 rounded-xl p-4 mb-4 text-[13px] text-slate-700">{{ reviewing.reason }}</div>
            <label class="block text-[12px] font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Note (optional)</label>
            <textarea v-model="reviewNote" rows="2" class="input-modern w-full resize-none mb-5" placeholder="Add a note..."></textarea>
            <div class="flex gap-3">
              <button @click="submitReview('approved')" :disabled="submitting" class="btn-primary flex-1 justify-center py-2.5 text-[13px]">
                Approve
              </button>
              <button @click="submitReview('rejected')" :disabled="submitting" class="flex-1 py-2.5 rounded-xl text-[13px] font-semibold bg-rose-500 text-white hover:bg-rose-600 transition-colors">
                Reject
              </button>
              <button @click="reviewing = null" class="btn-secondary py-2.5 px-4 text-[13px]">Cancel</button>
            </div>
          </div>
        </div>
      </Transition>
    </Teleport>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'

definePageMeta({ middleware: 'auth' })

const { apiFetch } = useApi()
const data = ref<any>({ data: [] })
const loading = ref(true)
const filter = ref('pending')

const reviewing = ref<any>(null)
const reviewNote = ref('')
const submitting = ref(false)

onMounted(async () => { await load() })

const load = async () => {
  loading.value = true
  try { data.value = await apiFetch(`/teacher/excuse-requests?filter[status]=${filter.value}`) } catch {}
  loading.value = false
}

const requests = computed(() => data.value?.data ?? [])

const openReview = (r: any) => { reviewing.value = r; reviewNote.value = '' }

const submitReview = async (status: 'approved' | 'rejected') => {
  if (!reviewing.value) return
  submitting.value = true
  try {
    await apiFetch(`/teacher/excuse-requests/${reviewing.value.id}/review`, {
      method: 'PUT',
      body: { status, reviewer_note: reviewNote.value },
    })
    reviewing.value = null
    await load()
  } catch {}
  submitting.value = false
}

const statusConfig: Record<string, string> = {
  pending:  'bg-amber-50 text-amber-700 ring-1 ring-amber-200',
  approved: 'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-200',
  rejected: 'bg-rose-50 text-rose-700 ring-1 ring-rose-200',
}

const formatDate = (d: string) => d ? new Date(d).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' }) : '-'
</script>

<style scoped>
.modal-enter-active, .modal-leave-active { transition: opacity 0.2s; }
.modal-enter-from, .modal-leave-to { opacity: 0; }
</style>
