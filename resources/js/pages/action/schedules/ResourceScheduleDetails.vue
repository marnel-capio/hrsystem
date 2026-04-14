<script setup lang="ts">
import { Head, useForm, usePage } from '@inertiajs/vue3';
import { Users } from 'lucide-vue-next';
import { ref, computed, onMounted, watch } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';

const page = usePage();

const successMessage = ref((page.props.flash as any)?.success || '');
const showSuccess = ref(!!successMessage.value);

// 2️⃣ Error handling
const errorMessage = ref((page.props.flash as any)?.error || '');
const showError = ref(!!errorMessage.value);


// Delete form and modal state
const deleteForm = useForm({});
const showDeleteModal = ref(false);
const deleting = ref(false);

function confirmDelete() {
  showDeleteModal.value = true;
}

function deleteSchedule() {
  deleting.value = true;

  deleteForm.delete(`/action/schedules/${props.schedule.id}`, {
    preserveScroll: false,
    preserveState: false,
    onSuccess: () => {
      showDeleteModal.value = false;
    },
    onError: () => {
      errorMessage.value = "Failed to delete the record.";
      showError.value = true;
      setTimeout(() => (showError.value = false), 5000);
    },
    onFinish: () => {
      deleting.value = false;
    },
  });
}

const notificationSent = ref(false);
const sendingNotification = ref(false);
const notificationForm = useForm({});

function sendNotification() {
  sendingNotification.value = true;
  notificationForm.post(`/action/schedules/${props.schedule.id}/send-notification`, {
    onSuccess: (page) => {
      sendingNotification.value = false;
      notificationSent.value = true;

      // Dynamic success message
      successMessage.value = page.props.flash?.success ||
        "Notification emails sent to all active HR recruiters successfully.";
      showSuccess.value = true;

      setTimeout(() => (showSuccess.value = false), 5000);
    },
    onError: () => {
      sendingNotification.value = false;
      errorMessage.value = "An error occurred while sending the email/s. Please try again.";
      showError.value = true;
      setTimeout(() => (showError.value = false), 5000);
    },
  });
}

// Define props to receive schedule data from the backend
const props = defineProps<{
    flash?: {
        success?: string
        error?: string
    }
    projection: Record<string, any>
    schedule: {
        id: number;
        batch_name: string;
        prev_batch_name?: string;
        target_location: string;
        target_trainees: number;
        deployment_date: string;
        contact_schools?: string;
        screening?: string;
        initial_interview?: string;
        final_interview?: string;
        job_offer?: string;
        job_acceptance?: string;
        remarks?: string;
        created_by?: string;
        created_time?: string;
        updated_by?: string;
        updated_by_name?: string;
        updated_time?: string;
    }
    userPermissions: number;
}>();

const projection = ref(props.projection);

watch(() => props.projection, (newVal) => {
  projection.value = newVal;
});

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
    training: "Start of Training",
  };
  return names[key] || key;
}

// Initialize ganttForm with existing WBS data
const ganttForm = ref(
  Object.fromEntries(
    ganttActivities.map(a => [
      a,
      {
        start: props.schedule.wbs?.[a]?.start || "",
        end: props.schedule.wbs?.[a]?.end || "",
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
  if (!weekStr) return '';
  const [year, isoWeek] = weekStr.split('-W').map(Number);

  // Compute the Monday of this ISO week
  const jan4 = new Date(year, 0, 4);
  const dayOffset = (isoWeek - 1) * 7;
  const weekStart = new Date(jan4.getTime() + dayOffset * 86400000);

  const month = weekStart.toLocaleString('en-US', { month: 'short' });

  // week-in-month calculation where week starts wih 1 for every new month
  const firstDayOfMonth = new Date(weekStart.getFullYear(), weekStart.getMonth(), 1);
  const firstDayWeekday = firstDayOfMonth.getDay() === 0 ? 7 : firstDayOfMonth.getDay(); // Sunday=7
  const weekInMonth = Math.ceil((weekStart.getDate() + firstDayWeekday - 1) / 7);

  return `${month} W${weekInMonth}`;
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

watch(successMessage, (newVal) => {
  if (newVal) {
    showSuccess.value = true;
    setTimeout(() => {
      showSuccess.value = false;
      successMessage.value = '';
    }, 5000);
  }
}, { immediate: true });

watch(errorMessage, (newVal) => {
  if (newVal) {
    showError.value = true;
    setTimeout(() => {
      showError.value = false;
      errorMessage.value = '';
    }, 5000);
  }
}, { immediate: true });

// Build WBS payload
const wbsPayload: Record<string, { start: string; end: string }> = {};
ganttActivities.forEach(a => {
  wbsPayload[a] = {
    start: ganttForm.value[a].start,
    end: ganttForm.value[a].end
  };
});

editForm.wbs = wbsPayload;

</script>
<template>
  <Head :title="`${schedule.batch_name} - Resource Schedule`" />

  <AppLayout :breadcrumbs="breadcrumbs">



    <!-- SUCCESS ALERT -->
<div v-if="showSuccess" class="full-width-alert">
  <div class="alert-banner alert-success-banner">
    <div class="alert-body">{{ successMessage }}</div>
    <button type="button" class="close-btn" @click="showSuccess = false">×</button>
  </div>
</div>

<!-- ERROR ALERT -->
<div v-if="showError" class="full-width-alert">
  <div class="alert-banner alert-error-banner">
    <div class="alert-body">{{ errorMessage }}</div>
    <button type="button" class="close-btn" @click="showError = false">×</button>
  </div>
</div>

    <div class="flex flex-1 flex-col gap-6 p-8 bg-zinc-50/50 dark:bg-zinc-950 min-h-screen">

      <!-- Header -->
      <div class="flex items-center justify-between">
        <h1 class="text-3xl font-bold text-zinc-900 dark:text-zinc-100">
          Resource Schedule Details
        </h1>
        <div class="flex gap-3">



<button
    v-if="props.userPermissions !== 3"
    @click.prevent="sendNotification"
    :disabled="sendingNotification || notificationSent"
    class="btn-send"
  >
    <span v-if="!sendingNotification && !notificationSent">Send Notification</span>
    <span v-else-if="sendingNotification">Sending...</span>
    <span v-else>Sent</span>
  </button>

  <a
    v-if="props.userPermissions !== 3"
    :href="`/action/schedules/${props.schedule.id}/edit`"
    class="btn-edit"
  >
    Edit
  </a>

  <button
    v-if="props.userPermissions !== 3"
    @click="confirmDelete"
    class="btn-delete"
  >
    Delete
  </button>
        </div>
      </div>

      <!-- DELETE CONFIRMATION MODAL -->
<div v-if="showDeleteModal" class="fixed inset-0 z-50 flex items-center justify-center">
  <!-- Backdrop -->
  <div
    class="absolute inset-0 bg-black/50 backdrop-blur-sm"
    @click="showDeleteModal = false"
  ></div>

  <!-- Modal Content -->
  <div class="relative bg-white dark:bg-zinc-900 rounded-2xl shadow-2xl p-8 max-w-md w-full mx-4">
    <!-- Title -->
    <h3 class="text-xl font-bold text-center text-red-600 mb-2">
    Delete Resource Schedule?
    </h3>

    <!-- Description -->
<p class="text-zinc-700 dark:text-zinc-700 text-center mb-6">
  Are you sure you want to delete <strong>"{{ schedule.batch_name }}"</strong>?<br>
  This action cannot be undone. <br>
    <span class="text-[0.7rem]">This action will send an email notifying all HR managers and recruiters.</span><br>

</p>

    <!-- Buttons -->
    <div class="flex gap-3">
      <button
        @click="showDeleteModal = false"
        :disabled="deleting"
        class="btn-primary"
      >
        Cancel
      </button>
      <button
        @click="deleteSchedule"
        :disabled="deleting"
        class="btn-primary"
      >
        <span v-if="!deleting">Delete</span>
        <span v-else>Deleting...</span>
      </button>
    </div>
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
        <td class="px-3 py-2 border">{{ projection.accepted.plan_no || '-' }}</td>
      </tr>

      <tr>
        <td class="font-semibold px-3 py-2 border">Job Offer</td>
        <td class="px-3 py-2 border">{{ projection.job_offer.plan_no || '-' }}</td>
      </tr>

      <tr>
        <td class="font-semibold px-3 py-2 border">Final Interview</td>
        <td class="px-3 py-2 border">{{ projection.final_interview.plan_no || '-' }}</td>
      </tr>

      <tr>
        <td class="font-semibold px-3 py-2 border">Initial Interview</td>
        <td class="px-3 py-2 border">{{ projection.initial_interview.plan_no || '-' }}</td>
      </tr>

      <tr>
        <td class="font-semibold px-3 py-2 border">Screening</td>
        <td class="px-3 py-2 border">{{ projection.examinees.plan_no || '-' }}</td>
      </tr>

      <!-- <tr>
        <td class="font-semibold px-3 py-2 border">Contact Schools</td>
        <td class="px-3 py-2 border">{{ schedule.contact_schools || '-' }}</td>
      </tr> -->
    </tbody>
  </table>
</div>

      </div>

<!-- ROW 2: RECRUITMENT PROJECTION -->
<div class="bg-white dark:bg-zinc-900 p-6 rounded-xl border border-zinc-200 dark:border-zinc-800 shadow">
    <h2 class="text-lg font-bold mb-4">Recruitment Projection</h2>

    <div class="overflow-x-auto">
        <table class="w-full text-sm border-collapse border">
            <thead>
                <!-- First row: Stage + group headers -->
                <tr class="bg-zinc-100 dark:bg-zinc-800 text-center font-bold">
                    <th rowspan="2" class="border px-3 py-2">Stage</th>
                    <th colspan="2" class="border px-3 py-2">{{ schedule.batch_name }} (Actual)</th>
                    <th colspan="2" class="border px-3 py-2">{{ schedule.prev_batch_name || 'No Previous Batch' }} (Plan)</th>
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
                    <!-- EXAMINEES -->
<tr>
  <td class="border px-3 py-2 font-semibold">Examinees</td>

  <td class="border px-3 py-2 text-center">{{ projection.examinees.actual_no }}</td>
  <td class="border px-3 py-2 text-center">-</td> <!-- No percent for examinees -->

  <td class="border px-3 py-2 text-blue-600 text-center">{{ projection.examinees.plan_no }}</td>
  <td class="border px-3 py-2 text-center">-</td> <!-- No percent for examinees -->
</tr>

                <!-- INITIAL INTERVIEW -->
                <tr>
                  <td class="border px-3 py-2 font-semibold">Undergone Initial Interview</td>

                  <td class="border px-3 py-2 text-center">{{ projection.initial_interview.actual_no }}</td>
                  <td class="border px-3 py-2 text-center">{{ projection.initial_interview.actual_pct }}%</td>

                  <td class="border px-3 py-2 text-blue-600 text-center">{{ projection.initial_interview.plan_no }}</td>
                  <td class="border px-3 py-2 text-center">{{ projection.initial_interview.plan_pct }}%</td>
                </tr>

                <!-- FINAL INTERVIEW -->
                <tr>
                  <td class="border px-3 py-2 font-semibold">Undergone Final Interview</td>

                  <td class="border px-3 py-2 text-center">{{ projection.final_interview.actual_no }}</td>
                  <td class="border px-3 py-2 text-center">{{ projection.final_interview.actual_pct }}%</td>

                  <td class="border px-3 py-2 text-blue-600 text-center">{{ projection.final_interview.plan_no }}</td>
                  <td class="border px-3 py-2 text-center">{{ projection.final_interview.plan_pct }}%</td>
                </tr>

                <!-- JOB OFFER -->
                <tr>
                  <td class="border px-3 py-2 font-semibold">Job Offer</td>

                  <td class="border px-3 py-2 text-center">{{ projection.job_offer.actual_no }}</td>
                  <td class="border px-3 py-2 text-center">{{ projection.job_offer.actual_pct }}%</td>

                  <td class="border px-3 py-2 text-blue-600 text-center">{{ projection.job_offer.plan_no }}</td>
                  <td class="border px-3 py-2 text-center">{{ projection.job_offer.plan_pct }}%</td>
                </tr>

                <!-- ACCEPTED -->
                <tr>
                  <td class="border px-3 py-2 pl-8">Accepted Job Offer</td>

                  <td class="border px-3 py-2 text-center">{{ projection.accepted.actual_no }}</td>
                  <td class="border px-3 py-2 text-center">{{ projection.accepted.actual_pct }}%</td>

                  <td class="border px-3 py-2 text-blue-600 text-center">{{ projection.accepted.plan_no }}</td>
                  <td class="border px-3 py-2 text-center">{{ projection.accepted.plan_pct }}%</td>
                </tr>

                <!-- DECLINED -->
                <tr>
                  <td class="border px-3 py-2 pl-8">Declined Job Offer</td>

                  <td class="border px-3 py-2 text-center">{{ projection.declined.actual_no }}</td>
                  <td class="border px-3 py-2 text-center">{{ projection.declined.actual_pct }}%</td>

                  <td class="border px-3 py-2 text-blue-600 text-center">{{ projection.declined.plan_no }}</td>
                  <td class="border px-3 py-2 text-center">{{ projection.declined.plan_pct }}%</td>
                </tr>

                <!-- TRAINEES FROM MANILA -->
                <tr>
                  <td class="border px-3 py-2 font-semibold">Trainees from Manila</td>

                  <td class="border px-3 py-2 text-center">{{ projection.trainees_manila.actual_no }}</td>
                  <td class="border px-3 py-2 text-center">{{ projection.trainees_manila.actual_pct }}%</td>

                  <td class="border px-3 py-2 text-blue-600 text-center">{{ projection.trainees_manila.plan_no }}</td>
                  <td class="border px-3 py-2 text-center">{{ projection.trainees_manila.plan_pct }}%</td>
                </tr>

                <!-- TRAINEES FROM CEBU -->
                <tr>
                  <td class="border px-3 py-2 font-semibold">Trainees from Cebu</td>

                  <td class="border px-3 py-2 text-center">{{ projection.trainees_cebu.actual_no }}</td>
                  <td class="border px-3 py-2 text-center">{{ projection.trainees_cebu.actual_pct }}%</td>

                  <td class="border px-3 py-2 text-blue-600 text-center">{{ projection.trainees_cebu.plan_no }}</td>
                  <td class="border px-3 py-2 text-center">{{ projection.trainees_cebu.plan_pct }}%</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Placeholder note -->
    <p class="text-xs text-zinc-500 mt-4 text-center">
        <em>Recruitment data will be populated from Action Applications when available.</em>
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


<!-- Remarks Field (Details Page) -->
<div class="space-y-2 mt-6">
  <label class="text-sm font-semibold">Remarks</label>
  <textarea
    class="w-full bg-zinc-50 border rounded-lg p-2.5 resize-none"
    rows="4"
    readonly
  >{{ schedule.remarks || '' }}</textarea>
</div>

<!-- Updated Info -->
  <div class="text-xs text-zinc-500 mt-1">
    <span>Last updated by: {{ schedule.updated_by_name }}</span>
    <span class="ml-8">Last updated at: {{ schedule.updated_time }}</span>
  </div>


    </div>


</AppLayout> </template>
