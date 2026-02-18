<!-- To add if login coding is finished:
        -User permission validation -->
<script setup lang="ts">
import { Head, useForm, usePage } from '@inertiajs/vue3';
import { ref, computed, onMounted } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';

// Define props to receive centralized error messages from the backend
const props = defineProps<{
  errorMessages: Record<string, { errorCode: string; errorMessage: string }>;
}>();

// Access Inertia page props for flash messages
const page = usePage();

// Initialize Inertia form with default values
const form = useForm({
  batchName: "",
  location: "",
  targetTrainees: "",
  deploymentDate: "",
  wbs: {},
});

// Define the list of WBS activities
const ganttActivities = [
  "contact_schools",
  "sourcing_testing",
  "initial_interviews",
  "final_interviews",
  "contract_offers",
  "requirements",
  "training"
];

// Format activity keys into readable names
function formatActivityName(key: string) {
  const names: Record<string, string> = {
    contact_schools: "Contact Schools",
    sourcing_testing: "Sourcing & Testing",
    initial_interviews: "Initial Interviews",
    final_interviews: "Final Interviews",
    contract_offers: "Contract Offers",
    requirements: "Requirements",
    training: "Training",
  };
  return names[key] || key;
}

// Initialize reactive state for WBS form with default week ranges
const ganttForm = ref(
  Object.fromEntries(
    ganttActivities.map(a => [a, { start: "", end: "", error: "" }])
  )
);

// Helper to convert week string to a numerical key for comparison
function weekToKey(weekStr: string) {
  if (!weekStr) return null;
  const [year, wk] = weekStr.split("-W").map(Number);
  return year * 100 + wk;
}

// Format week string into a display label (e.g., "Jan W1")
function formatWeekLabel(weekStr: string) {
  const [year, weekNum] = weekStr.split("-W").map(Number);
  const jan4 = new Date(year, 0, 4);
  const weekStart = new Date(jan4.getTime() + (weekNum - 1) * 7 * 86400000);
  const month = weekStart.toLocaleString("en-US", { month: "short" });
  return `${month} W${weekNum}`;
}

// Compute the list of unique weeks for the Gantt chart based on WBS ranges
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

// Compute month spans for the Gantt chart header
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

// Compute rows for the Gantt chart bars
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

// Compute success message from flash data
const successMessage = computed(() => (page.props.flash as any)?.success || '');

// Auto-clear success message after 5 seconds on component mount
onMounted(() => {
  if (successMessage.value) {
    setTimeout(() => {
      (page.props.flash as any).success = '';
    }, 5000);
  }
});

// Validate WBS ranges and display errors using centralized messages
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
    if (errorElement) {
      errorElement.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
  }
  return hasErrors;
}

// Handle form submission with WBS validation and API call
function createResourceSchedule() {
  if (validateWBS()) {
    return;
  }

  const wbsPayload: Record<string, { start: string; end: string }> = {};
  ganttActivities.forEach(a => {
    wbsPayload[a] = {
      start: ganttForm.value[a].start,
      end: ganttForm.value[a].end
    };
  });

  form.wbs = wbsPayload;

  form.post('/action/schedules', {
    onSuccess: (page) => {
      console.log('Inertia onSuccess: Page props:', page.props);
    },
    onError: (errors) => {
      console.log('Inertia onError: Errors:', errors);
    },
  });
}
</script>

<template>
  <Head title="Create Resource Schedule" />

  <AppLayout>
    <div class="max-w-5xl mx-auto w-full space-y-10 p-8">
      <h1 class="text-3xl font-bold mb-6">Create Resource Schedule</h1>

      <!-- Display success message if present -->
      <div v-if="successMessage" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
        {{ successMessage }}
      </div>

      <div class="bg-white dark:bg-zinc-900 p-10 rounded-2xl border shadow-xl space-y-10">
        <form @submit.prevent="createResourceSchedule">
          <!-- Basic Information Section -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div>
              <label class="text-sm font-semibold">Batch Name</label>
              <select v-model="form.batchName" class="w-full bg-zinc-50 border rounded-lg p-2.5" required>
                <option value="">Select</option>
                <option value="ACTION 39">ACTION 39</option>
                <option value="ACTION 40">ACTION 40</option>
                <option value="ACTION 41">ACTION 41</option>
              </select>
              <p v-if="form.errors.batchName" class="text-red-600 text-xs mt-1">
                {{ form.errors.batchName }}
              </p>
            </div>

            <div>
              <label class="text-sm font-semibold">Target Location</label>
              <select v-model="form.location" class="w-full bg-zinc-50 border rounded-lg p-2.5" required>
                <option value="">Select</option>
                <option value="Cebu">Cebu</option>
                <option value="Manila">Manila</option>
              </select>
              <p v-if="form.errors.location" class="text-red-600 text-xs mt-1">
                {{ form.errors.location }}
              </p>
            </div>

            <div>
              <label class="text-sm font-semibold">Target Trainees</label>
              <input v-model="form.targetTrainees" type="number" class="w-full bg-zinc-50 border rounded-lg p-2.5" required />
              <p v-if="form.errors.targetTrainees" class="text-red-600 text-xs mt-1">
                {{ form.errors.targetTrainees }}
              </p>
            </div>

            <div>
              <label class="text-sm font-semibold">Date of Deployment</label>
              <input v-model="form.deploymentDate" type="month" class="w-full bg-zinc-50 border rounded-lg p-2.5" required />
              <p v-if="form.errors.deploymentDate" class="text-red-600 text-xs mt-1">
                {{ form.errors.deploymentDate }}
              </p>
            </div>
          </div>

          <!-- Work Breakdown Schedule Section -->
<h2 class="text-xl font-bold mb-4 mt-10">Work Breakdown Schedule (WBS)</h2>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
            <template v-for="act in ganttActivities" :key="act">
              <div>
                <label class="font-semibold">{{ formatActivityName(act) }}</label>

                <div class="grid grid-cols-2 gap-3 mt-1 mb-1">
                  <div>
                    <label class="text-xs text-zinc-500 block mb-1">Start</label>
                    <input type="week" v-model="ganttForm[act].start" class="w-full bg-zinc-50 border rounded-lg p-2" required />
                  </div>

                  <div>
                    <label class="text-xs text-zinc-500 block mb-1">End</label>
                    <input type="week" v-model="ganttForm[act].end" class="w-full bg-zinc-50 border rounded-lg p-2" required />
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
                <div class="p-2 bg-zinc-50 font-bold border-b">Activity</div>

                <template v-for="m in monthSpans" :key="m.month">
                  <div :style="`grid-column: span ${m.count}`" class="text-center font-bold text-xs p-1 bg-blue-50 border-b">
                    {{ m.month }}
                  </div>
                </template>

                <div></div>

                <template v-for="w in ganttWeeks" :key="w">
                  <div class="text-[11px] text-center p-1 bg-blue-50 border-b">
                    {{ formatWeekLabel(w) }}
                  </div>
                </template>

                <!-- Activity Bars -->
                <template v-for="row in ganttRows" :key="row.activity">
                  <div class="font-semibold py-2 border-r pr-2">
                    {{ formatActivityName(row.activity) }}
                  </div>

                  <template v-for="(_, i) in ganttWeeks" :key="i">
                    <div class="border h-7 relative">
                      <div v-if="i >= row.startIndex && i <= row.endIndex" class="absolute inset-0 bg-blue-500/80 rounded-sm"></div>
                    </div>
                  </template>
                </template>
              </div>
            </div>

            <div v-else class="text-sm text-zinc-500">Select weeks to generate preview.</div>
          </div>

          <div class="flex justify-end mt-6">
            <button type="submit" :disabled="form.processing" class="px-8 py-2.5 bg-blue-600 hover:bg-blue-700 disabled:bg-blue-400 text-white rounded-lg font-bold shadow-lg">
              Create
            </button>
          </div>
        </form>
      </div>
    </div>
  </AppLayout>
</template>
