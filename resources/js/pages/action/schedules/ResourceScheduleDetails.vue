<script setup lang="ts">
import { Head, useForm, usePage} from '@inertiajs/vue3';
import { Users } from 'lucide-vue-next';
import { ref, computed } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';

const page = usePage();
const flashMessage = computed(() => (page.props as any).flash?.success || '');


// Define props to receive schedule data from the backend
const props = defineProps<{
  schedule: {
    id: number;
    batch_name: string;
    target_location: string;
    target_trainees: number;
    deployment_date: string;
    wbs: Record<string, { start: string; end: string }>;
    created_by?: string;
    created_time?: string;
    updated_by?: string;
    updated_time?: string;
  };
}>();

// Breadcrumbs
const breadcrumbs: BreadcrumbItem[] = [
  { title: 'Resource Schedule', href: '/schedules' },
  { title: props.schedule.batch_name, href: '#' },
];

// Edit modal state
const showEditModal = ref(false);

// Edit form state - populate with existing data
const editForm = useForm({
  batchName: props.schedule.batch_name,
  location: props.schedule.target_location,
  targetTrainees: props.schedule.target_trainees,
  deploymentDate: props.schedule.deployment_date,
  wbs: { ...props.schedule.wbs },
});

// WBS activities
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

// Initialize ganttForm with existing WBS data
const ganttForm = ref(
  Object.fromEntries(
    ganttActivities.map(a => [
      a,
      {
        start: props.schedule.wbs?.[a]?.start ?? "",
        end: props.schedule.wbs?.[a]?.end ?? "",
        error: ""
      }
    ])
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

// Gantt preview colors
const wbsColors: Record<string, string> = {
  contact_schools: '#166534',   // dark green
  sourcing_testing: '#dc2626',  // red 
  initial_interviews: '#f97316', // orange
  final_interviews: '#2563eb',  // blue
  contract_offers: '#7c3aed',   // purple
  requirements: '#ec4899',      // pink
  training: '#84cc16',          // light green
};


// Format deployment date
function formatDeploymentDate(dateStr: string) {
  if (!dateStr) return '';
  try {
    const date = new Date(dateStr + '-01');
    return date.toLocaleDateString('en-US', { year: 'numeric', month: 'long' });
  } catch {
    return dateStr;
  }
}

// Close modal function
function closeModal() {
  showEditModal.value = false;
}

// Validate WBS ranges
function validateWBS() {
  let hasErrors = false;
  let firstErrorAct: string | null = null;
  ganttActivities.forEach(act => {
    const row = ganttForm.value[act];
    row.error = "";
    if (weekToKey(row.start)! > weekToKey(row.end)!) {
      row.error = "End date must be on or after start date.";
      hasErrors = true;
      if (!firstErrorAct) firstErrorAct = act;
    }
  });
  if (hasErrors && firstErrorAct) {
    const errorElement = document.getElementById('error-edit-' + firstErrorAct);
    if (errorElement) {
      errorElement.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
  }
  return hasErrors;
}

// Save function
function saveAllEdits() {
  if (validateWBS()) {
    return;
  }

  // Build WBS payload
  const wbsPayload: Record<string, { start: string; end: string }> = {};
  ganttActivities.forEach(a => {
    wbsPayload[a] = {
      start: ganttForm.value[a].start,
      end: ganttForm.value[a].end
    };
  });

  editForm.wbs = wbsPayload;

  // Submit using Inertia
  editForm.put(`/action/schedules/${props.schedule.id}`, {
    onSuccess: () => {
      closeModal();
    },
    onError: (errors) => {
      console.log('Update errors:', errors);
    },
  });
}
</script>

<template>
  <Head :title="`${schedule.batch_name} - Resource Schedule`" />

  <AppLayout :breadcrumbs="breadcrumbs">
    
      <div v-if="flashMessage" class="p-4 mb-4 bg-green-100 text-green-800 rounded">
    {{ flashMessage }}
  </div>

    <div class="flex flex-1 flex-col gap-6 p-8 bg-zinc-50/50 dark:bg-zinc-950 min-h-screen">
      
      <!-- Header -->
      <div class="flex items-center justify-between">
        <h1 class="text-3xl font-bold text-zinc-900 dark:text-zinc-100">
          Resource Schedule Details
        </h1>
        <div class="flex gap-3">
          <a 
            href="/action/schedules" 
            class="px-4 py-2 text-zinc-600 hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-zinc-100"
          >
            ← Back to List
          </a>
          <button 
            @click="showEditModal = true"
            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-semibold"
            style="background-color: #1C7BA5;"
          >
            Edit Resource Schedule
          </button>
        </div>
      </div>

      <!-- ROW 1: BATCH TITLE + TARGET TRAINEES + MASTER SCHEDULE -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        <!-- LEFT COLUMN: Batch Title + Target Trainees -->
        <div class="flex flex-col gap-4 h-full">
          
          <!-- Batch Title Card -->
          <div 
            class="flex-1 text-white px-5 py-4 rounded-xl shadow-md flex flex-col items-center justify-center text-center"
            style="background-color: #2f359e;"
          >
            <h2 class="text-2xl font-extrabold tracking-wide drop-shadow">
              {{ schedule.batch_name }}
            </h2>
            <p class="text-sm opacity-90 mt-1">
              Recruitment Batch Overview
            </p>
          </div>

          <!-- Target Trainees Card -->
          <div class="flex-1 bg-white dark:bg-zinc-900 p-5 rounded-xl border border-zinc-200 dark:border-zinc-800 shadow flex flex-col justify-center">
            <h3 class="text-sm font-bold text-zinc-700 dark:text-zinc-200 uppercase tracking-wide mb-4 text-center">
              Target Number of Trainees
            </h3>
            <div class="flex flex-col gap-3 text-base font-medium w-full">
              <div class="flex justify-between items-center bg-zinc-100 dark:bg-zinc-800 px-4 py-3 rounded-lg border text-lg w-full">
                <span class="font-semibold">{{ schedule.target_location }}</span>
                <div class="flex items-center gap-2">
                  <span class="font-extrabold text-blue-600">{{ schedule.target_trainees }}</span>
                  <Users class="w-5 h-5 text-blue-600" />
                </div>
              </div>
            </div>
          </div>

        </div>

        <!-- RIGHT COLUMN: Master Schedule -->
        <div class="md:col-span-2 bg-white dark:bg-zinc-900 p-6 rounded-xl border border-zinc-200 dark:border-zinc-800 shadow h-full">
          <h2 class="text-lg font-bold mb-4">Master Schedule</h2>
          <table class="w-full text-sm border">
           <tbody>
  <tr>
    <td class="font-semibold px-3 py-2 border">Deployment Date</td>
    <td class="font-semibold px-3 py-2 border">{{ formatDeploymentDate(schedule.deployment_date) }}</td>
  </tr>
  <tr>
    <td class="font-semibold px-3 py-2 border">Job Acceptance</td>
    <!-- fields from job_acceptance til contact_schools are still not used. waiting for action batches code. -->
    <td class="px-3 py-2 border">{{ schedule.job_acceptance || '-' }}</td> 
  </tr>
  <tr>
    <td class="font-semibold px-3 py-2 border">Job Offer</td>
    <td class="px-3 py-2 border">{{ schedule.job_offer || '-' }}</td> 
  </tr>
  <tr>
    <td class="font-semibold px-3 py-2 border">Final Interview</td>
    <td class="px-3 py-2 border">{{ schedule.final_interview || '-' }}</td>
  </tr>
  <tr>
    <td class="font-semibold px-3 py-2 border">Initial Interview</td>
    <td class="px-3 py-2 border">{{ schedule.initial_interview || '-' }}</td>
  </tr>
  <tr>
    <td class="font-semibold px-3 py-2 border">Screening</td>
    <td class="px-3 py-2 border">{{ schedule.screening || '-' }}</td>
  </tr>
  <tr>
    <td class="font-semibold px-3 py-2 border">Contact Schools</td>
    <td class="px-3 py-2 border">{{ schedule.contact_schools || '-' }}</td>
  </tr>
</tbody>
          </table>
        </div>

      </div>

<!-- ROW 2: RECRUITMENT PROJECTION -->
<!-- TODO: Connect to Action Batches data when feature is implemented -->
<div class="bg-white dark:bg-zinc-900 p-6 rounded-xl border border-zinc-200 dark:border-zinc-800 shadow">
    <h2 class="text-lg font-bold mb-4">Recruitment Projection</h2>

    <div class="overflow-x-auto">
        <table class="w-full text-sm border-collapse border">
            <thead>
                <!-- First row: Stage + group headers -->
                <tr class="bg-zinc-100 dark:bg-zinc-800 text-center font-bold">
                    <th rowspan="2" class="border px-3 py-2">Stage</th>
                    <th colspan="2" class="border px-3 py-2">{{ schedule.batch_name }} (Actual)</th>
                    <th colspan="2" class="border px-3 py-2">Next Batch (Plan)</th>
                </tr>
                <!-- Second row: sub-columns -->
                <tr class="bg-zinc-50 dark:bg-zinc-700 text-center font-semibold">
                    <th class="border px-3 py-2">No.</th>
                    <th class="border px-3 py-2">%</th>
                    <th class="border px-3 py-2">No.</th>
                    <th class="border px-3 py-2">%</th>
                </tr>
            </thead>

            <tbody>
                <tr>
                    <td class="border px-3 py-2 font-semibold">Examinees</td>
                    <td class="border px-3 py-2 font-semibold text-center">-</td>
                    <td class="border px-3 py-2 text-center">-</td>
                    <td class="border px-3 py-2 font-semibold text-blue-600 text-center">-</td>
                    <td class="border px-3 py-2 text-center">-</td>
                </tr>

                <tr>
                    <td class="border px-3 py-2 font-semibold">Undergone Initial Interview</td>
                    <td class="border px-3 py-2 text-center">-</td>
                    <td class="border px-3 py-2 text-center">-</td>
                    <td class="border px-3 py-2 font-semibold text-blue-600 text-center">-</td>
                    <td class="border px-3 py-2 text-center">-</td>
                </tr>

                <tr>
                    <td class="border px-3 py-2 font-semibold">Undergone Final Interview</td>
                    <td class="border px-3 py-2 text-center">-</td>
                    <td class="border px-3 py-2 text-center">-</td>
                    <td class="border px-3 py-2 font-semibold text-blue-600 text-center">-</td>
                    <td class="border px-3 py-2 text-center">-</td>
                </tr>

                <tr>
                    <td class="border px-3 py-2 font-semibold">Job Offer</td>
                    <td class="border px-3 py-2 text-center">-</td>
                    <td class="border px-3 py-2 text-center">-</td>
                    <td class="border px-3 py-2 font-semibold text-blue-600 text-center">-</td>
                    <td class="border px-3 py-2 text-center">-</td>
                </tr>

                <tr>
                    <td class="border px-3 py-2 pl-6">Accepted</td>
                    <td class="border px-3 py-2 text-center">-</td>
                    <td class="border px-3 py-2 text-center">-</td>
                    <td class="border px-3 py-2 font-semibold text-blue-600 text-center">-</td>
                    <td class="border px-3 py-2 text-center">-</td>
                </tr>

                <tr>
                    <td class="border px-3 py-2 pl-6">Declined</td>
                    <td class="border px-3 py-2 text-center">-</td>
                    <td class="border px-3 py-2 text-center">-</td>
                    <td class="border px-3 py-2 font-semibold text-blue-600 text-center">-</td>
                    <td class="border px-3 py-2 text-center">-</td>
                </tr>

                <tr>
                    <td class="border px-3 py-2 font-semibold">Trainees from Manila</td>
                    <td class="border px-3 py-2 text-center">-</td>
                    <td class="border px-3 py-2 text-center">-</td>
                    <td class="border px-3 py-2 font-semibold text-blue-600 text-center">-</td>
                    <td class="border px-3 py-2 text-center">-</td>
                </tr>

                <tr>
                    <td class="border px-3 py-2 font-semibold">Trainees from Cebu</td>
                    <td class="border px-3 py-2 text-center">-</td>
                    <td class="border px-3 py-2 text-center">-</td>
                    <td class="border px-3 py-2 font-bold text-blue-600 text-center">-</td>
                    <td class="border px-3 py-2 text-center">-</td>
                </tr>
            </tbody>
        </table>
    </div>
    
    <!-- Placeholder note -->
    <p class="text-xs text-zinc-500 mt-4 text-center">
        <em>Recruitment data will be populated from Action Batches when available.</em>
    </p>
</div>

      <!-- ROW 3: WBS TIMELINE -->
      <div class="bg-white dark:bg-zinc-900 p-6 rounded-xl border border-zinc-200 dark:border-zinc-800 shadow">
        <h2 class="text-lg font-bold mb-4">WBS Timeline</h2>

        <div v-if="ganttWeeks.length" class="overflow-x-auto">
          <div class="overflow-x-auto border p-4 rounded-lg">
            <div 
              class="grid gap-0.5"
              :style="`grid-template-columns: 220px repeat(${ganttWeeks.length}, 1fr)`"
            >
              <!-- Header: Activity -->
              <div class="p-2 bg-zinc-50 dark:bg-zinc-800 font-bold border-b">
                Activity
              </div>

              <!-- Header: Month spans -->
              <template v-for="m in monthSpans" :key="m.month">
                <div 
                  class="text-center font-bold text-base p-2 bg-blue-50 dark:bg-blue-900/20 border-b"
                  :style="`grid-column: span ${m.count}`"
                >
                  {{ m.month }}
                </div>
              </template>

              <!-- Header: Weeks -->
              <div></div>
              <template v-for="w in ganttWeeks" :key="w">
                <div class="text-[11px] text-center p-1 bg-blue-50 dark:bg-blue-900/20 border-b">
                  {{ formatWeekLabel(w) }}
                </div>
              </template>

              <!-- Rows: Activity bars -->
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
        </div>

        <div v-else class="text-sm text-zinc-500">
          No WBS data available.
        </div>
      </div>

      

    </div>

    <!-- Edit Modal -->
<!-- Edit Modal -->
<div
  v-if="showEditModal"
  class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4"
>
  <div
    class="bg-white dark:bg-zinc-900 rounded-xl shadow-xl w-full max-w-5xl p-6 overflow-y-auto max-h-[90vh] relative"
  >
    <!-- Close Button -->
    <button
      class="absolute top-3 right-3 text-zinc-500 hover:text-zinc-900 dark:hover:text-white"
      @click="closeModal"
    >
      ✕
    </button>

    <h2 class="text-2xl font-bold mb-6">Edit Resource Schedule</h2>

    <!-- BASIC FORM -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-10">

      <!-- Batch Name -->
      <div class="space-y-2">
        <label class="text-sm font-semibold">Batch Name</label>
        <select 
          v-model="editForm.batchName" 
          class="w-full bg-zinc-50 border rounded-lg p-2.5"
          required
        >
          <option value="">Select</option>
          <option value="ACTION 39">ACTION 39</option>
          <option value="ACTION 40">ACTION 40</option>
          <option value="ACTION 41">ACTION 41</option>
        </select>
        <p v-if="editForm.errors.batchName" class="text-red-600 text-xs mt-1">
          {{ editForm.errors.batchName }}
        </p>
      </div>

      <!-- Location -->
      <div class="space-y-2">
        <label class="text-sm font-semibold">Target Location</label>
        <select 
          v-model="editForm.location" 
          class="w-full bg-zinc-50 border rounded-lg p-2.5"
          required
        >
          <option value="">Select</option>
          <option value="Cebu">Cebu</option>
          <option value="Manila">Manila</option>
        </select>
        <p v-if="editForm.errors.location" class="text-red-600 text-xs mt-1">
          {{ editForm.errors.location }}
        </p>
      </div>

      <!-- Target Trainees -->
      <div class="space-y-2">
        <label class="text-sm font-semibold">Target Trainees</label>
        <input 
          v-model="editForm.targetTrainees" 
          type="number"
          class="w-full bg-zinc-50 border rounded-lg p-2.5"
          required
        />
        <p v-if="editForm.errors.targetTrainees" class="text-red-600 text-xs mt-1">
          {{ editForm.errors.targetTrainees }}
        </p>
      </div>

      <!-- Deployment Date -->
      <div class="space-y-2">
        <label class="text-sm font-semibold">Date of Deployment</label>
        <input 
          v-model="editForm.deploymentDate" 
          type="month"
          class="w-full bg-zinc-50 border rounded-lg p-2.5"
          required
        />
        <p v-if="editForm.errors.deploymentDate" class="text-red-600 text-xs mt-1">
          {{ editForm.errors.deploymentDate }}
        </p>
      </div>

    </div> <!-- END BASIC FORM -->

    <!-- WBS TITLE -->
    <h2 class="text-xl font-bold mb-4 mt-10">Work Breakdown Schedule (WBS)</h2>

    <!-- WBS FORM GRID -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
      <template v-for="act in ganttActivities" :key="act">
        <div>
          <label class="font-semibold">{{ formatActivityName(act) }}</label>

          <div class="grid grid-cols-2 gap-3 mt-1 mb-1">
            <div>
              <label class="text-xs text-zinc-500 block mb-1">Start</label>
              <input type="week" v-model="ganttForm[act].start" class="w-full bg-zinc-50 border rounded-lg p-2" required>
            </div>

            <div>
              <label class="text-xs text-zinc-500 block mb-1">End</label>
              <input type="week" v-model="ganttForm[act].end" class="w-full bg-zinc-50 border rounded-lg p-2" required>
            </div>
          </div>

          <p
            v-if="ganttForm[act].error"
            :id="'error-edit-' + act"
            class="text-red-600 text-xs"
          >
            {{ ganttForm[act].error }}
          </p>
        </div>
      </template>
    </div>

    <!-- GANTT PREVIEW -->
    <div class="mt-12">
      <h2 class="font-bold text-xl mb-4">WBS Preview</h2>

      <div v-if="ganttWeeks.length" class="overflow-x-auto border p-6 rounded-xl shadow-sm">
        <div class="grid gap-0.5" :style="`grid-template-columns: 220px repeat(${ganttWeeks.length}, 1fr)`">

          <!-- Header Left -->
          <div class="p-2 bg-zinc-50 font-bold border-b">Activity</div>

          <!-- Months -->
          <template v-for="m in monthSpans" :key="m.month">
            <div
              :style="`grid-column: span ${m.count}`"
              class="text-center font-bold text-base p-2 bg-blue-50 border-b"
            >
              {{ m.month }}
            </div>
          </template>

          <div></div>

          <!-- Weeks -->
          <template v-for="w in ganttWeeks" :key="w">
            <div class="text-[11px] text-center p-1 bg-blue-50 border-b">
              {{ formatWeekLabel(w) }}
            </div>
          </template>

          <!-- Bars -->
          <template v-for="row in ganttRows" :key="row.activity">
            <div class="font-semibold py-2 border-r pr-2">{{ formatActivityName(row.activity) }}</div>

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

      <div v-else class="text-sm text-zinc-500">
        Select weeks to generate preview.
      </div>
    </div>

    <!-- BUTTONS -->
    <div class="flex justify-end gap-3 mt-6">
      <button 
        @click="closeModal"
        class="px-6 py-2.5 text-sm font-medium text-zinc-600 hover:text-zinc-900"
      >
        Cancel
      </button>
      <button 
        @click="saveAllEdits"
        :disabled="editForm.processing"
        class="px-8 py-2.5 bg-blue-600 hover:bg-blue-700 disabled:bg-blue-400 text-white rounded-lg font-bold shadow-lg"
      >
        Update
      </button>
    </div>

  </div>
</div>

</AppLayout> </template>