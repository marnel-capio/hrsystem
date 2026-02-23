<script setup lang="ts">
import { Head, useForm, usePage } from '@inertiajs/vue3';
import { ref, computed, onMounted } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';

// Props from backend
const props = defineProps<{
  errorMessages: Record<string, { errorCode: string; errorMessage: string }>;
  actionBatches: { id: number; action_batch: string }[];
}>();

// Inertia page
const page = usePage();

// Initialize form with all required fields
const form = useForm({
  action_batch_id: "",
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
  training_enddate: "",
  remarks: "",
});

// Gantt activity keys
const ganttActivities = [
  "contact_schools",
  "source_testing",
  "initial_interviews",
  "final_interviews",
  "contract_offers",
  "requirements",
  "training"
];

// UI labels
function formatActivityName(key: string) {
  const names: Record<string, string> = {
    contact_schools: "Contact Schools",
    source_testing: "Sourcing & Testing",
    initial_interviews: "Initial Interviews",
    final_interviews: "Final Interviews",
    contract_offers: "Contract Offers",
    requirements: "Requirements",
    training: "Training",
  };
  return names[key] || key;
}

// Reactive Gantt inputs
const ganttForm = ref(
  Object.fromEntries(
    ganttActivities.map(a => [a, { start: "", end: "", error: "" }])
  )
);

// Week helper functions
function weekToKey(weekStr: string) {
  if (!weekStr) return null;
  const [year, wk] = weekStr.split("-W").map(Number);
  return year * 100 + wk;
}

function formatWeekLabel(weekStr: string) {
  const [year, weekNum] = weekStr.split("-W").map(Number);
  const jan4 = new Date(year, 0, 4);
  const weekStart = new Date(jan4.getTime() + (weekNum - 1) * 7 * 86400000);
  const month = weekStart.toLocaleString("en-US", { month: "short" });
  return `${month} W${weekNum}`;
}

// Compute unique weeks for Gantt preview
const ganttWeeks = computed(() => {
  const keys: number[] = [];
  ganttActivities.forEach(act => {
    const { start, end } = ganttForm.value[act];
    if (!start || !end) return;
    let y = Math.floor(weekToKey(start)! / 100);
    let w = weekToKey(start)! % 100;
    const endY = Math.floor(weekToKey(end)! / 100);
    const endW = weekToKey(end)! % 100;
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

// Month spans
const monthSpans = computed(() => {
  const spans: any[] = [];
  let current: any = null;
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

// Gantt rows
const ganttRows = computed(() => {
  return ganttActivities.map(act => {
    const { start, end } = ganttForm.value[act];
    return {
      activity: act,
      startIndex: ganttWeeks.value.indexOf(start),
      endIndex: ganttWeeks.value.indexOf(end),
    };
  });
});

// Success message
const successMessage = computed(() => (page.props.flash as any)?.success || '');
const showSuccess = ref(successMessage.value);
onMounted(() => {
  if (showSuccess.value) setTimeout(() => showSuccess.value = false, 5000);
});

// Gantt colors
const wbsColors: Record<string, string> = {
  contact_schools: '#166534',
  source_testing: '#dc2626',
  initial_interviews: '#f97316',
  final_interviews: '#2563eb',
  contract_offers: '#7c3aed',
  requirements: '#ec4899',
  training: '#84cc16',
};

// Validate WBS ranges
function validateWBS() {
  let hasErrors = false;
  let firstErrorAct: string | null = null;
  ganttActivities.forEach(act => {
    const row = ganttForm.value[act];
    row.error = "";
    if (weekToKey(row.start)! > weekToKey(row.end)!) {
      const errorMsg = props.errorMessages.wbs_end_before_start.errorMessage;
      row.error = errorMsg.replace(':activity', formatActivityName(act));
      hasErrors = true;
      if (!firstErrorAct) firstErrorAct = act;
    }
  });
  if (hasErrors && firstErrorAct) {
    const errorElement = document.getElementById('error-' + firstErrorAct);
    if (errorElement) errorElement.scrollIntoView({ behavior: 'smooth', block: 'center' });
  }
  return hasErrors;
}

// Submit form
function createResourceSchedule() {
  if (validateWBS()) return;

  // Map ganttForm to form fields
ganttActivities.forEach(act => {
  (form as any)[`${act}_startdate`] = ganttForm.value[act].start;
  (form as any)[`${act}_enddate`] = ganttForm.value[act].end;
});

  form.post('/action/schedules', {
    onSuccess: () => { showSuccess.value = true },
    onError: (errors) => console.log(errors),
  });
}
</script>

<template>
  <Head title="Create Resource Schedule" />

  <AppLayout>
    <div class="max-w-5xl mx-auto w-full space-y-10 p-8">
      <h1 class="text-3xl font-bold mb-6">Create Resource Schedule</h1>

      <div v-if="showSuccess" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
        {{ successMessage }}
      </div>

      <div class="bg-white dark:bg-zinc-900 p-10 rounded-2xl border shadow-xl space-y-10">
        <form @submit.prevent="createResourceSchedule">

          <!-- Basic Info -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div>
              <label class="text-sm font-semibold">Batch Name</label>
              <select v-model="form.action_batch_id" class="w-full bg-zinc-50 border rounded-lg p-2.5" required>
                <option value="">Select</option>
                <option v-for="batch in props.actionBatches" :key="batch.id" :value="batch.id">
                  {{ batch.action_batch }}
                </option>
              </select>
              <p v-if="form.errors.action_batch_id" class="text-red-600 text-xs mt-1">
                {{ form.errors.action_batch_id }}
              </p>
            </div>

            <div>
              <label class="text-sm font-semibold">Target Location</label>
              <select v-model="form.target_location" class="w-full bg-zinc-50 border rounded-lg p-2.5" required>
                <option value="">Select</option>
                <option value="1">Manila</option>
                <option value="2">Cebu</option>
              </select>
              <p v-if="form.errors.target_location" class="text-red-600 text-xs mt-1">
                {{ form.errors.target_location }}
              </p>
            </div>

            <div>
              <label class="text-sm font-semibold">Target Trainees</label>
              <input v-model="form.target_trainees" type="number" class="w-full bg-zinc-50 border rounded-lg p-2.5" required />
              <p v-if="form.errors.target_trainees" class="text-red-600 text-xs mt-1">
                {{ form.errors.target_trainees }}
              </p>
            </div>

            <div>
              <label class="text-sm font-semibold">Date of Deployment</label>
              <input v-model="form.deployment_date" type="month" class="w-full bg-zinc-50 border rounded-lg p-2.5" required />
              <p v-if="form.errors.deployment_date" class="text-red-600 text-xs mt-1">
                {{ form.errors.deployment_date }}
              </p>
            </div>
          </div>

          <!-- Gantt Section -->
<!-- Work Breakdown Schedule Section -->
<h2 class="text-xl font-bold mb-4 mt-10">Work Breakdown Schedule (WBS)</h2>

<div class="grid grid-cols-1 md:grid-cols-2 gap-10">
  <template v-for="act in ganttActivities" :key="act">
    <div>
      <label class="font-semibold">{{ formatActivityName(act) }}</label>

      <div class="grid grid-cols-2 gap-3 mt-1 mb-1">
        <div>
          <label class="text-xs text-zinc-500 block mb-1">Start</label>
          <input
            type="week"
            v-model="ganttForm[act].start"
            class="w-full bg-zinc-50 border rounded-lg p-2"
            required
          />
        </div>

        <div>
          <label class="text-xs text-zinc-500 block mb-1">End</label>
          <input
            type="week"
            v-model="ganttForm[act].end"
            class="w-full bg-zinc-50 border rounded-lg p-2"
            required
          />
        </div>
      </div>

      <p v-if="ganttForm[act].error" :id="'error-' + act" class="text-red-600 text-xs">
        {{ ganttForm[act].error }}
      </p>
    </div>
  </template>
</div>

<!-- Gantt Chart Preview -->
<div class="mt-12">
  <h2 class="font-bold text-xl mb-4">WBS Preview</h2>

  <div v-if="ganttWeeks.length" class="overflow-x-auto border p-6 rounded-xl shadow-sm">
    <div class="grid gap-0.5" :style="`grid-template-columns: 220px repeat(${ganttWeeks.length}, 1fr)`">
      <!-- Header: Activity -->
      <div class="p-2 bg-zinc-50 font-bold border-b">Activity</div>

      <!-- Header: Month spans -->
      <template v-for="m in monthSpans" :key="m.month">
        <div
          :style="`grid-column: span ${m.count}`"
          class="text-center font-bold text-base p-2 bg-blue-50 border-b"
        >
          {{ m.month }}
        </div>
      </template>

      <div></div> <!-- spacer -->

      <!-- Header: Week labels -->
      <template v-for="w in ganttWeeks" :key="w">
        <div class="text-[11px] text-center p-1 bg-blue-50 border-b">
          {{ formatWeekLabel(w) }}
        </div>
      </template>

      <!-- Activity Rows -->
      <template v-for="row in ganttRows" :key="row.activity">
        <div class="font-semibold py-2 border-r pr-2">
          {{ formatActivityName(row.activity) }}
        </div>

        <template v-for="(_, i) in ganttWeeks" :key="i">
          <div class="border h-7 relative">
            <div
              v-if="i >= row.startIndex && i <= row.endIndex && row.startIndex !== -1"
              class="absolute inset-0 rounded-sm"
              :style="`background-color: ${wbsColors[row.activity] || '#000'}; opacity: 0.8;`"
            ></div>
          </div>
        </template>
      </template>
    </div>
  </div>

  <div v-else class="text-sm text-zinc-500">Select weeks to generate preview.</div>
</div>

          <!-- Buttons -->
          <div class="flex justify-end gap-3 mt-6">
            <button type="button" @click="$inertia.visit('/action/schedules')" class="px-6 py-2.5 text-sm font-medium text-zinc-600 hover:text-zinc-900">
              Cancel
            </button>
            <button type="submit" :disabled="form.processing" class="px-8 py-2.5 bg-blue-600 hover:bg-blue-700 disabled:bg-blue-400 text-white rounded-lg font-bold shadow-lg">
              Create
            </button>
          </div>

        </form>
      </div>
    </div>
  </AppLayout>
</template>