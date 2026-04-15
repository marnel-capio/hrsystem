<script setup lang="ts">
import { Head, useForm, usePage, Link } from '@inertiajs/vue3';
import { ref, computed, onMounted, watch } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';

const props = defineProps<{
  errorMessages: Record<string, { errorCode: string; errorMessage: string }>;
  newBatches: { id: number; action_batch: string; target_trainees: number; target_date: string; }[];
  prevBatches: { id: number; action_batch: string }[];
}>();

const page = usePage();

const errorMessage = computed(() => (page.props.flash as any)?.error || '');
const successMessage = computed(() => (page.props.flash as any)?.success || '');

const showError = ref(false);
const showSuccess = ref(!!successMessage.value);

const form = useForm({
  action_batch_id: "",
  prev_batch_id: "",
  target_location: "",
  target_trainees: "",
  deployment_date: "",
  contact_schools_startdate: "",
  contact_schools_enddate: "",
  source_testing_startdate: "",
  source_testing_enddate: "",
  initial_interviews_startdate: "",
  initial_interviews_enddate: "",
  final_interviews_startdate: "",
  final_interviews_enddate: "",
  contract_offers_startdate: "",
  contract_offers_enddate: "",
  requirements_startdate: "",
  requirements_enddate: "",
  training_startdate: "",
  remarks: "",
});

const ganttActivities = [
  "contact_schools",
  "source_testing",
  "initial_interviews",
  "final_interviews",
  "contract_offers",
  "requirements",
  "training"
];

function formatActivityName(key: string) {
  const names: Record<string, string> = {
    contact_schools: "Contact Schools",
    source_testing: "Sourcing & Testing",
    initial_interviews: "Initial Interviews",
    final_interviews: "Final Interviews",
    contract_offers: "Contract Offers",
    requirements: "Requirements",
    training: "Start of Training",
  };
  return names[key] || key;
}

function previousActivityEnd(activity: string) {
  const index = ganttActivities.indexOf(activity);
  if (index <= 0) return "";
  const prev = ganttActivities[index - 1];
  return ganttForm.value[prev].end || "";
}

function endMinWeek(activity: string) {
  if (activity === 'training') return "";

  const ownStart = ganttForm.value[activity].start || "";
  const prevEnd = previousActivityEnd(activity);

  if (ownStart && prevEnd) {
    return ownStart > prevEnd ? ownStart : prevEnd;
  }

  return ownStart || prevEnd || deploymentMinWeek.value;
}

const selectedBatchName = computed(() => {
  const batch = props.newBatches.find(b => b.id === Number(form.action_batch_id));
  return batch?.action_batch || 'Batch';
});

const ganttForm = ref(
  Object.fromEntries(
    ganttActivities.map(a => [a, { start: "", end: "", error: "" }])
  ) as Record<string, { start: string; end: string; error: string }>
);

function weekToKey(weekStr: string) {
  if (!weekStr) return null;
  const [year, wk] = weekStr.split("-W").map(Number);
  return year * 100 + wk;
}

function formatWeekLabel(weekStr: string) {
  if (!weekStr) return '';
  const [year, isoWeek] = weekStr.split('-W').map(Number);

  const jan4 = new Date(year, 0, 4);
  const dayOffset = (isoWeek - 1) * 7;
  const weekStart = new Date(jan4.getTime() + dayOffset * 86400000);

  const month = weekStart.toLocaleString('en-US', { month: 'short' });

  const firstDayOfMonth = new Date(weekStart.getFullYear(), weekStart.getMonth(), 1);
  const firstDayWeekday = firstDayOfMonth.getDay() === 0 ? 7 : firstDayOfMonth.getDay();
  const weekInMonth = Math.ceil((weekStart.getDate() + firstDayWeekday - 1) / 7);

  return `${month} W${weekInMonth}`;
}

const deploymentMinWeek = computed(() => {
  if (!form.deployment_date) return "";

  const [year, month] = form.deployment_date.split("-").map(Number);
  const minDate = new Date(year, month - 1, 1);
  minDate.setMonth(minDate.getMonth() - 6);

  const temp = new Date(minDate);
  temp.setHours(0, 0, 0, 0);
  const dayNum = temp.getDay() || 7;
  temp.setDate(temp.getDate() + 4 - dayNum);

  const isoYear = temp.getFullYear();
  const yearStart = new Date(isoYear, 0, 1);
  const weekNo = Math.ceil((((temp.getTime() - yearStart.getTime()) / 86400000) + 1) / 7);

  return `${isoYear}-W${String(weekNo).padStart(2, '0')}`;
});

const deploymentMaxWeek = computed(() => {
  if (!form.deployment_date) return "";

  const [year, month] = form.deployment_date.split("-").map(Number);
  const lastDay = new Date(year, month, 0);

  const temp = new Date(lastDay);
  temp.setDate(temp.getDate() + 4 - (temp.getDay() || 7));
  const yearStart = new Date(temp.getFullYear(), 0, 1);
  const weekNo = Math.ceil((((temp.getTime() - yearStart.getTime()) / 86400000) + 1) / 7);

  return `${temp.getFullYear()}-W${String(weekNo).padStart(2, "0")}`;
});

const trainingStartMinWeek = computed(() => {
  if (!form.deployment_date) return "";

  const [year, month] = form.deployment_date.split("-").map(Number);
  const firstDay = new Date(year, month - 1, 1);

  const temp = new Date(firstDay);
  temp.setHours(0, 0, 0, 0);
  const dayNum = temp.getDay() || 7;
  temp.setDate(temp.getDate() + 4 - dayNum);

  const isoYear = temp.getFullYear();
  const yearStart = new Date(isoYear, 0, 1);
  const weekNo = Math.ceil((((temp.getTime() - yearStart.getTime()) / 86400000) + 1) / 7);

  return `${isoYear}-W${String(weekNo).padStart(2, '0')}`;
});

const trainingStartMaxWeek = computed(() => {
  if (!form.deployment_date) return "";

  const [year, month] = form.deployment_date.split("-").map(Number);
  const lastDay = new Date(year, month, 0);

  const temp = new Date(lastDay);
  temp.setHours(0, 0, 0, 0);
  const dayNum = temp.getDay() || 7;
  temp.setDate(temp.getDate() + 4 - dayNum);

  const isoYear = temp.getFullYear();
  const yearStart = new Date(isoYear, 0, 1);
  const weekNo = Math.ceil((((temp.getTime() - yearStart.getTime()) / 86400000) + 1) / 7);

  return `${isoYear}-W${String(weekNo).padStart(2, '0')}`;
});

function isBatchSelected() {
  return !!form.action_batch_id;
}

function getPreviousActivity(activity: string) {
  const index = ganttActivities.indexOf(activity);
  if (index <= 0) return null;
  return ganttActivities[index - 1];
}

function isCompleted(activity: string) {
  const row = ganttForm.value[activity];
  if (activity === 'training') return !!row?.start;
  return !!(row?.start && row?.end);
}

function isActivityEnabled(activity: string) {
  const previous = getPreviousActivity(activity);
  if (!previous) return true;
  return isCompleted(previous);
}

function previousActivityStart(activity: string) {
  const index = ganttActivities.indexOf(activity);
  if (index <= 0) return "";
  const prev = ganttActivities[index - 1];
  return ganttForm.value[prev].start || "";
}

function startMinWeek(activity: string) {
  if (activity === 'training') return trainingStartMinWeek.value;
  const prevStart = previousActivityStart(activity);
  return prevStart || deploymentMinWeek.value;
}

function canEditStart(activity: string) {
  if (!isBatchSelected()) return false;
  return isActivityEnabled(activity);
}

function canEditEnd(activity: string) {
  if (activity === 'training') return false;
  if (!isBatchSelected()) return false;
  if (!isActivityEnabled(activity)) return false;
  return !!ganttForm.value[activity].start;
}

const ganttWeeks = computed(() => {
  const keys: number[] = [];

  ganttActivities.forEach(act => {
    const { start, end } = ganttForm.value[act];
    if (!start) return;

    const effectiveEnd = act === 'training' ? start : end;
    if (!effectiveEnd) return;

    let y = Math.floor(weekToKey(start)! / 100);
    let w = weekToKey(start)! % 100;
    const endY = Math.floor(weekToKey(effectiveEnd)! / 100);
    const endW = weekToKey(effectiveEnd)! % 100;

    while (y < endY || (y === endY && w <= endW)) {
      keys.push(y * 100 + w);
      w++;
      if (w > 52) { w = 1; y++; }
    }
  });

  return Array.from(new Set(keys)).sort((a, b) => a - b).map(k => {
    const year = Math.floor(k / 100);
    const week = k % 100;
    return `${year}-W${String(week).padStart(2, "0")}`;
  });
});

const monthSpans = computed(() => {
  const spans: { month: string; count: number }[] = [];
  let current: { month: string; count: number } | null = null;

  ganttWeeks.value.forEach(w => {
    const label = formatWeekLabel(w);
    const month = label.split(" ")[0];
    if (!current || current.month !== month) {
      current = { month, count: 1 };
      spans.push(current);
    } else {
      current.count++;
    }
  });

  return spans;
});

const ganttRows = computed(() => {
  return ganttActivities.map(act => {
    const { start, end } = ganttForm.value[act];

    return {
      activity: act,
      startIndex: ganttWeeks.value.indexOf(start),
      endIndex: act === 'training'
        ? ganttWeeks.value.indexOf(start)
        : ganttWeeks.value.indexOf(end),
    };
  });
});

const wbsColors: Record<string, string> = {
  contact_schools: '#166534',
  source_testing: '#dc2626',
  initial_interviews: '#f97316',
  final_interviews: '#2563eb',
  contract_offers: '#7c3aed',
  requirements: '#ec4899',
  training: '#84cc16',
};

function createResourceSchedule() {
  ganttActivities.forEach(act => {
    (form as any)[`${act}_startdate`] = ganttForm.value[act].start;

    if (act !== 'training') {
      (form as any)[`${act}_enddate`] = ganttForm.value[act].end;
    }
  });

  form.post('/action/schedules', {
    onSuccess: () => { showSuccess.value = true; },
    onError: (errors) => {
      ganttActivities.forEach(act => {
        ganttForm.value[act].error = errors[`${act}_startdate`] || errors[`${act}_enddate`] || "";
      });
    },
  });
}

onMounted(() => {
  if (showSuccess.value) setTimeout(() => showSuccess.value = false, 5000);
  if (errorMessage.value) {
    showError.value = true;
    setTimeout(() => showError.value = false, 5000);
  }
});

watch(
  ganttForm,
  (newVal) => {
    ganttActivities.forEach((act) => {
      if (act === 'training') return;

      const row = newVal[act];

      if (row.end && endMinWeek(act) && row.end < endMinWeek(act)) {
        row.end = "";
      }

      if (row.start && startMinWeek(act) && row.start < startMinWeek(act)) {
        row.start = "";
        row.end = "";
      }
    });
  },
  { deep: true }
);

watch(errorMessage, (val) => {
  if (val) {
    showError.value = true;
    setTimeout(() => showError.value = false, 5000);
  }
});

watch(() => form.action_batch_id, (newId) => {
  const batch = props.newBatches.find(b => b.id === Number(newId));

  if (batch) {
    form.target_trainees = batch.target_trainees;
    form.deployment_date = batch.target_date
      ? (batch.target_date.length > 7 ? batch.target_date.substring(0, 7) : batch.target_date)
      : '';
  } else {
    form.target_trainees = '';
    form.deployment_date = '';

    ganttActivities.forEach((act) => {
      ganttForm.value[act].start = "";
      ganttForm.value[act].end = "";
      ganttForm.value[act].error = "";
    });
  }
});

watch(() => form.deployment_date, () => {
  ganttActivities.forEach((act) => {
    const row = ganttForm.value[act];

    if (act === 'training') {
      if (row.start && trainingStartMinWeek.value && row.start < trainingStartMinWeek.value) {
        row.start = "";
        row.error = "";
      }

      if (row.start && trainingStartMaxWeek.value && row.start > trainingStartMaxWeek.value) {
        row.start = "";
        row.error = "";
      }

      row.end = "";
      return;
    }

    if (row.start && deploymentMinWeek.value && row.start < deploymentMinWeek.value) {
      row.start = "";
      row.end = "";
      row.error = "";
    }

    if (row.start && deploymentMaxWeek.value && row.start > deploymentMaxWeek.value) {
      row.start = "";
      row.end = "";
      row.error = "";
    }

    if (row.end && deploymentMinWeek.value && row.end < deploymentMinWeek.value) {
      row.end = "";
      row.error = "";
    }

    if (row.end && deploymentMaxWeek.value && row.end > deploymentMaxWeek.value) {
      row.end = "";
      row.error = "";
    }
  });
});

watch(
  ganttForm,
  (newVal) => {
    ganttActivities.forEach((act, index) => {
      if (index === 0) return;

      const prev = ganttActivities[index - 1];

      if (!isCompleted(prev)) {
        newVal[act].start = "";
        newVal[act].end = "";
        newVal[act].error = "";
      }

      if (act === 'training') {
        newVal[act].end = "";
      }
    });
  },
  { deep: true }
);
</script>

<template>
  <Head title="Create Resource Schedule" />

  <AppLayout>
    <div class="max-w-5xl mx-auto w-full space-y-10 p-8">
      <h1 class="text-3xl font-bold mb-6">Create Resource Schedule</h1>

      <div v-if="showError" class="full-width-alert">
        <div class="alert-banner alert-error-banner">
          <div class="alert-body">{{ errorMessage }}</div>
          <button type="button" class="close-btn" @click="showError = false">×</button>
        </div>
      </div>

      <div class="bg-white dark:bg-zinc-900 p-10 rounded-2xl border shadow-xl space-y-10">
        <form @submit.prevent="createResourceSchedule">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div>
              <label class="text-sm required font-semibold">Batch Name</label>
              <select v-model="form.action_batch_id" class="w-full bg-zinc-50 border rounded-lg p-2.5">
                <option value="">Select</option>
                <option v-for="batch in props.newBatches" :key="batch.id" :value="batch.id">
                  {{ batch.action_batch }}
                </option>
              </select>
              <p v-if="form.errors.action_batch_id" class="text-red-600 text-xs mt-1">
                {{ form.errors.action_batch_id }}
              </p>
            </div>

            <div>
              <label class="text-sm required font-semibold">Target Location</label>
              <select v-model="form.target_location" class="w-full bg-zinc-50 border rounded-lg p-2.5">
                <option value="">Select</option>
                <option value="1">Manila</option>
                <option value="2">Cebu</option>
              </select>
              <p v-if="form.errors.target_location" class="text-red-600 text-xs mt-1">
                {{ form.errors.target_location }}
              </p>
            </div>

            <div>
              <label class="text-sm required font-semibold">Target Trainees</label>
              <input v-model="form.target_trainees" type="number" disabled class="w-full bg-zinc-50 border rounded-lg p-2.5" />
              <p v-if="form.errors.target_trainees" class="text-red-600 text-xs mt-1">
                {{ form.errors.target_trainees }}
              </p>
            </div>

            <div>
              <label class="text-sm required font-semibold">Date of Deployment</label>
              <input v-model="form.deployment_date" readonly class="w-full bg-zinc-50 border rounded-lg p-2.5" />
              <p v-if="form.errors.deployment_date" class="text-red-600 text-xs mt-1">
                {{ form.errors.deployment_date }}
              </p>
            </div>

            <div>
              <label class="text-sm text-gray-500 font-semibold">Compare with Previous Batch</label>
              <select v-model="form.prev_batch_id" class="w-full bg-zinc-50 border rounded-lg p-2.5">
                <option value="">Select</option>
                <option v-for="batch in props.prevBatches" :key="batch.id" :value="batch.id">
                  {{ batch.action_batch }}
                </option>
              </select>
              <p v-if="form.errors.prev_batch_id" class="text-red-600 text-xs mt-1">
                {{ form.errors.prev_batch_id }}
              </p>
            </div>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-10 mt-10">
            <template v-for="act in ganttActivities" :key="act">
              <div>
                <label class="text-sm required font-semibold">{{ formatActivityName(act) }}</label>

                <div v-if="act !== 'training'" class="grid grid-cols-2 gap-3 mt-1 mb-1">
                  <div>
                    <label class="text-xs text-zinc-500 block mb-1">Start</label>
                    <input
                      type="week"
                      v-model="ganttForm[act].start"
                      class="w-full bg-zinc-50 border rounded-lg p-2"
                      :disabled="!canEditStart(act)"
                      :min="startMinWeek(act)"
                      :max="deploymentMaxWeek"
                    />
                  </div>

                  <div>
                    <label class="text-xs text-zinc-500 block mb-1">End</label>
                    <input
                      type="week"
                      v-model="ganttForm[act].end"
                      class="w-full bg-zinc-50 border rounded-lg p-2"
                      :disabled="!canEditEnd(act)"
                      :min="endMinWeek(act)"
                      :max="deploymentMaxWeek"
                    />
                  </div>
                </div>

                <div v-else class="mt-1 mb-1">
                  <label class="text-xs text-zinc-500 block mb-1">Start</label>
                  <input
                    type="week"
                    v-model="ganttForm[act].start"
                    class="w-full bg-zinc-50 border rounded-lg p-2"
                    :disabled="!canEditStart(act)"
                    :min="trainingStartMinWeek"
                    :max="trainingStartMaxWeek"
                  />
                </div>

                <p v-if="ganttForm[act].error" :id="'error-' + act" class="text-red-600 text-xs">
                  {{ ganttForm[act].error }}
                </p>
              </div>
            </template>
          </div>

          <div class="mt-12">
            <h2 class="font-bold text-xl mb-4">WBS Preview</h2>

            <div v-if="ganttWeeks.length" class="overflow-x-auto border p-6 rounded-xl shadow-sm">
              <div class="grid gap-0.5" :style="`grid-template-columns: 220px repeat(${ganttWeeks.length}, 1fr)`">
                <div class="p-2 bg-zinc-50 font-bold border-b">Activity</div>

                <template v-for="m in monthSpans" :key="m.month">
                  <div :style="`grid-column: span ${m.count}`" class="text-center font-bold text-base p-2 bg-blue-50 border-b">
                    {{ m.month }}
                  </div>
                </template>

                <div></div>

                <template v-for="w in ganttWeeks" :key="w">
                  <div class="text-[11px] text-center p-1 bg-blue-50 border-b">
                    {{ formatWeekLabel(w) }}
                  </div>
                </template>

                <template v-for="row in ganttRows" :key="row.activity">
                  <div class="font-semibold py-2 border-r pr-2">
                    {{ formatActivityName(row.activity) }}
                  </div>

                  <template v-for="(_, i) in ganttWeeks" :key="`${row.activity}-${i}`">
                    <div class="border h-7 relative">
                      <div
                        v-if="i >= row.startIndex && i <= row.endIndex && row.startIndex !== -1"
                        class="absolute inset-0 rounded-sm"
                        :style="`background-color: ${wbsColors[row.activity] || '#000'}; opacity: 0.8;`"
                      ></div>
                    </div>
                  </template>
                </template>

                <div></div>

                <div
                  class="relative h-7 col-span-full"
                  :style="{ gridColumn: `2 / span ${ganttWeeks.length}` }"
                >
                  <div
                    v-if="ganttRows.find(r => r.activity === 'training')?.startIndex !== -1"
                    class="absolute text-xs text-black whitespace-nowrap"
                    :style="{
                      left: `calc(${(ganttRows.find(r => r.activity === 'training')?.startIndex ?? 0) * 100 / ganttWeeks.length}% + 0.25rem)`
                    }"
                  >
                    ▲ {{ selectedBatchName }} Start
                  </div>
                </div>
              </div>
            </div>

            <div v-else class="text-sm text-zinc-500">Select weeks to generate preview.</div>
          </div>

          <div class="mt-10">
            <label class="text-sm text-gray-500 font-semibold">Remarks</label>
            <textarea
              v-model="form.remarks"
              class="w-full bg-zinc-50 border rounded-lg p-2.5 mt-1"
              rows="4"
              placeholder="Enter any remarks here..."
            ></textarea>
            <p v-if="form.errors.remarks" class="text-red-600 text-xs mt-1">
              {{ form.errors.remarks }}
            </p>
          </div>

          <div class="form-actions">
            <Link href="/action/schedules" class="btn btn-secondary">
              Cancel
            </Link>

            <button type="submit" :disabled="form.processing" class="btn btn-primary">
              {{ form.processing ? 'Creating…' : 'Create' }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </AppLayout>
</template>

<style scoped>
.required-label {
  display: block;
  font-size: 0.875rem;
  font-weight: 600;
  color: #111827;
  margin-bottom: 0.25rem;
}

.optional-label {
  display: block;
  font-size: 0.875rem;
  font-weight: 400;
  color: #9ca3af;
  margin-bottom: 0.25rem;
}

.form-actions {
  margin-top: 2rem;
  display: flex;
  justify-content: flex-end;
  align-items: center;
  gap: 0.5rem;
}

.form-actions button,
.form-actions a {
  flex: 0 0 auto;
  width: auto;
}

button[type="submit"],
.btn-secondary {
  padding: 0.5rem 1.2rem;
  font-size: 0.85rem;
  border-radius: 5px;
  font-weight: 500;
  white-space: nowrap;
  transition: background 0.15s ease;
}

button[type="submit"] {
  border: none;
  background: var(--ats-primary);
  color: #fff;
  cursor: pointer;
}

button[type="submit"]:hover:not(:disabled) {
  background: var(--ats-accent);
}

button[type="submit"]:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}

.btn-secondary {
  border: 1px solid #d1d5db;
  background: #f3f4f6;
  color: #374151;
  text-decoration: none;
}

.btn-secondary:hover {
  background: #e5e7eb;
}

.required::after {
    content: '*';
    margin-left: 0.25rem;
    color: #da0f19;
}
</style>
