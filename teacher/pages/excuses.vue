<template>
  <div class="animate-fade-in">
    <div class="mb-7">
      <h2 class="text-2xl font-bold text-slate-900 tracking-tight">{{ __('Excuse Requests') }}</h2>
      <p class="text-sm text-slate-500 mt-1">{{ __('Review and approve student excuse requests') }}</p>
    </div>

    <!-- Filter tabs -->
    <div class="card p-1 mb-5 inline-flex gap-1">
      <button
        v-for="tab in tabs" :key="tab.value"
        @click="filter = tab.value; load()"
        class="cursor-pointer px-4 py-1.5 rounded-lg text-[13px] font-medium transition-colors"
        :class="filter === tab.value ? 'bg-slate-900 text-white' : 'text-slate-500 hover:text-slate-700'"
      >{{ __(tab.label) }}</button>
    </div>

    <div v-if="loading" class="card p-12 text-center text-slate-400 text-sm">{{ __('Loading...') }}</div>

    <div v-else class="card overflow-hidden">
      <Table>
        <TableHeader>
          <TableRow>
            <TableHead>{{ __('Student') }}</TableHead>
            <TableHead>{{ __('Date') }}</TableHead>
            <TableHead>{{ __('Class') }} / {{ __('Subject') }}</TableHead>
            <TableHead>{{ __('Reason') }}</TableHead>
            <TableHead>{{ __('Status') }}</TableHead>
            <TableHead>{{ __('Action') }}</TableHead>
          </TableRow>
        </TableHeader>
        <TableBody>
          <TableRow v-for="r in requests" :key="r.id">
            <TableCell class="font-semibold text-slate-900">{{ r.student?.name }}</TableCell>
            <TableCell class="text-slate-500">{{ formatDate(r.attendance?.date) }}</TableCell>
            <TableCell>
              <span class="font-medium text-slate-700">{{ r.attendance?.class_subject?.school_class?.name }}</span>
              <span class="text-slate-400"> · {{ r.attendance?.class_subject?.subject?.name }}</span>
            </TableCell>
            <TableCell class="max-w-[180px] truncate text-slate-500">{{ r.reason }}</TableCell>
            <TableCell><span class="badge" :class="statusConfig[r.status]">{{ __(statusLabel(r.status)) }}</span></TableCell>
            <TableCell>
              <Button
                v-if="r.status === 'pending'"
                variant="outline"
                size="sm"
                @click="openReview(r)"
              >{{ __('Review') }}</Button>
              <span v-else class="text-slate-400 text-[12px]">—</span>
            </TableCell>
          </TableRow>
          <TableRow v-if="!requests.length">
            <TableCell colspan="6" class="p-0">
              <Empty class="border-0 rounded-none">
                <EmptyHeader>
                  <EmptyMedia variant="icon"><FileText class="w-6 h-6" /></EmptyMedia>
                  <EmptyTitle>{{ __('No pending requests') }}</EmptyTitle>
                  <EmptyDescription>{{ __('All caught up.') }}</EmptyDescription>
                </EmptyHeader>
              </Empty>
            </TableCell>
          </TableRow>
        </TableBody>
      </Table>
    </div>

    <!-- Review dialog -->
    <Dialog :open="!!reviewing" @update:open="(v) => !v && (reviewing = null)">
      <DialogContent class="max-w-md">
        <DialogHeader>
          <DialogTitle>Review Excuse</DialogTitle>
          <DialogDescription>{{ reviewing?.student?.name }} — {{ formatDate(reviewing?.attendance?.date) }}</DialogDescription>
        </DialogHeader>
        <div class="bg-slate-50 rounded-xl p-4 text-[13px] text-slate-700">{{ reviewing?.reason }}</div>
        <div class="space-y-1.5">
          <Label class="text-[12px] font-semibold text-slate-500 uppercase tracking-wider">Note (optional)</Label>
          <RichTextEditor v-model="reviewNote" placeholder="Add a note..." />
        </div>
        <DialogFooter class="gap-2">
          <Button variant="outline" @click="reviewing = null">Cancel</Button>
          <Button variant="destructive" :disabled="submitting" @click="submitReview('rejected')">Reject</Button>
          <Button :disabled="submitting" @click="submitReview('approved')">Approve</Button>
        </DialogFooter>
      </DialogContent>
    </Dialog>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import { FileText } from 'lucide-vue-next'

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
