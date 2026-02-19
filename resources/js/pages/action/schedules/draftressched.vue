<script setup lang="ts">
import { ref, computed } from 'vue';
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { 
    Search, Plus, Calendar, Users, 
    BarChart3, CheckCircle2, Clock, 
    ChevronRight, ArrowRight 
} from 'lucide-vue-next';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Resource Schedule', href: '#' },
];

// Script setup
const searchQuery = ref("");
const searchLocation = ref("");
const showEditModal = ref(false);

const resourceSchedules = ref([
  { id: 1, batchName: "ACTION 40", targetTrainees: 15, deploymentDate: "January 2025", location: "Cebu" },
  { id: 2, batchName: "ACTION 41", targetTrainees: 20, deploymentDate: "October 2025", location: "Manila" },
  // ...add more
]);

const filteredSchedules = computed(() => {
  return resourceSchedules.value.filter(rs =>
    rs.batchName.toLowerCase().includes(searchQuery.value.toLowerCase()) &&
    rs.location.toLowerCase().includes(searchLocation.value.toLowerCase())
  );
});

// --- Modal states ---
const showEditMaster = ref(false);
const showEditTrainees = ref(false);
const showEditRecruitment = ref(false);
const showEditWBS = ref(false);

// Methods to open modals
function editMasterSchedule() { showEditMaster.value = true; }
function editTargetTrainees() { showEditTrainees.value = true; }
function editRecruitmentProjection() { showEditRecruitment.value = true; }
function editWBSTimeline() { showEditWBS.value = true; }

// Close function (reusable)
function closeModal(modalRef: Ref<boolean>) { modalRef.value = false; }

const recruitmentRows = ref([
  { stage: "Examinees", actualNo: 619, actualPercent: "—", planNo: 300, planPercent: "—" },
  { stage: "Undergone Initial Interview", actualNo: 137, actualPercent: "22%", planNo: 66, planPercent: "22%" },
  { stage: "Undergone Final Interview", actualNo: 65, actualPercent: "11%", planNo: 32, planPercent: "11%" },
  { stage: "Job Offer", actualNo: 55, actualPercent: "9%", planNo: 27, planPercent: "9%" },
  { stage: "Accepted", actualNo: 34, actualPercent: "5%", planNo: 16, planPercent: "5%" },
  { stage: "Declined", actualNo: 21, actualPercent: "3%", planNo: 10, planPercent: "3%" },
  { stage: "Trainees from Manila", actualNo: 2, actualPercent: "0.32%", planNo: 0, planPercent: "0%" },
  { stage: "Trainees from Cebu", actualNo: 34, actualPercent: "5%", planNo: 16, planPercent: "5%" },
]);

// --- LIST TAB DATA ---
const listRows = ref([
  { id: 1, name: "Juan Dela Cruz", location: "Cebu", batch: "ACTION 40", status: "Registered" },
  { id: 2, name: "Maria Santos", location: "Manila", batch: "ACTION 38", status: "Pending" },
  { id: 3, name: "Pedro Reyes", location: "Cebu", batch: "ACTION 40", status: "Registered" },
]);

const activeTab = ref('list'); // 'list' | 'register' | 'wbs'

// --- DATA FROM YOUR EXCEL SUMMARY ---
const summaryStats = [
    { label: 'Target Hires (Cebu)', actual: 14, target: 15, color: 'text-blue-600' },
    { label: 'Applicants/Exams', actual: 551, target: 300, color: 'text-emerald-600' },
    { label: 'Hiring Efficiency', actual: '5.49%', target: '6.0%', color: 'text-purple-600' },
];

// --- REGISTRATION FORM LOGIC ---
const form = ref({
    batchName: "ACTION 40",
    location: "Cebu",
    targetTrainees: 15,
    startDate: "",
    deploymentDate: "",
    wbs: {
        contactSchools: "",
        sourcingTesting: "",
        initialInterviews: "",
        finalInterviews: "",
        contractOffers: "",
        requirements: "",
        training: ""
    },

    recruitmentProjection: {
        examinees: "",
        initialInterview: "",
        finalInterview: "",
        jobOffer: "",
        jobOfferAccepted: "",
        jobOfferDeclined: "",
        traineesManila: "",
        traineesCebu: ""
    }
});

// Auto-calculation logic based on your Excel "Plan & WBS" ratios
const projections = computed(() => {
    const target = form.value.targetTrainees;
    return {
        examinees: Math.ceil(target / 0.0549), // 5.49% success rate from excel
        initialInterview: Math.ceil(target / 0.2213), 
        finalInterview: Math.ceil(target / 0.105),
    };
});

// --- WBS DATA SYSTEMATIZED ---
const wbsPhases = [
    { name: 'Sourcing & Testing', period: 'Jan W4 - Feb W4', progress: 100, status: 'Completed' },
    { name: 'Initial Interviews', period: 'Feb W1 - Mar W4', progress: 85, status: 'Ongoing' },
    { name: 'Final Interviews', period: 'Mar W1 - Apr W4', progress: 20, status: 'Ongoing' },
    { name: 'Contract & Training', period: 'May W1 - Jul W3', progress: 0, status: 'Pending' },
];

const wbsWeeks = [
    "Week 1", "Week 2", "Week 3", "Week 4",
    "Week 5", "Week 6", "Week 7", "Week 8",
    "Week 9", "Week 10", "Week 11", "Week 12"
];

// wbs gantt

// ------------------------------
// WBS GANTT CONFIG
// ------------------------------

// Build dynamic merged month spans based on ganttWeeks
const monthSpans = computed(() => {
    const spans: { name: string; count: number }[] = [];
    let current = null;

    ganttWeeks.forEach(w => {
        const [month] = w.split(" ");
        if (!current || current.name !== month) {
            current = { name: month, count: 1 };
            spans.push(current);
        } else {
            current.count++;
        }
    });

    return spans;
});

// Extract only week label ("1st", "2nd", "3rd", "", etc.)
const extractWeek = (val: string) => {
    const parts = val.split(" ");
    return parts.length > 1 ? parts[1] : "";
};

const ganttMonths = [
    "January", "February", "March", "April", "May", "June", "July"
];

// Generate Month + Week (W1–W4)
// -----------------------------------------------
// CUSTOM MONTH-WEEK MATRIX (Your exact structure)
// -----------------------------------------------
const ganttWeeks = [
    // January → 4th
    "January 4th",

    // February → , 1st, 2nd, 3rd, 4th
    "February 1st",
    "February 2nd",
    "February 3rd",
    "February 4th",

    // March → 1st, 2nd, 3rd, , 4th
    "March 1st",
    "March 2nd",
    "March 3rd",
    "March 4th",

    // April → 1st, 2nd, 3rd, 4th
    "April 1st",
    "April 2nd",
    "April 3rd",
    "April 4th",

    // May → 1st, 2nd, 3rd, 4th, 
    "May 1st",
    "May 2nd",
    "May 3rd",
    "May 4th",
    "May 5th",

    // June → 1st, 2nd, 3rd, 4th
    "June 1st",
    "June 2nd",
    "June 3rd",
    "June 4th",

    // July → 1st, 2nd, 3rd
    "July 1st",
    "July 2nd",
    "July 3rd",
];

const ganttActivities = [
    "Contact Schools",
    "Sourcing & Testing",
    "Initial Interviews",
    "Final Interviews",
    "Contract Offers",
    "Requirements",
    "Training"
];

const ganttForm = ref(
    Object.fromEntries(
        ganttActivities.map(a => [a, { start: "", end: "" }])
    )
);

const ganttRows = computed(() => {
    return ganttActivities.map(activity => {
        const { start, end } = ganttForm.value[activity];
        return {
            activity,
            startIndex: ganttWeeks.indexOf(start),
            endIndex: ganttWeeks.indexOf(end),
        };
    });
});


</script>

<template>

    <Head title="Resource Schedule Management" />

    <AppLayout :breadcrumbs="breadcrumbs">
                            <div class="flex bg-white dark:bg-zinc-900 p-1 rounded-lg border border-zinc-200 dark:border-zinc-800 shadow-sm">
                        <button @click="activeTab = 'details'" :class="['px-4 py-2 text-sm font-medium rounded-md transition-all', activeTab === 'details' ? 'bg-zinc-100 dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 shadow-sm' : 'text-zinc-500 hover:text-zinc-700']">Details</button>
                        <button @click="activeTab = 'register'" :class="['px-4 py-2 text-sm font-medium rounded-md transition-all', activeTab === 'register' ? 'bg-zinc-100 dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 shadow-sm' : 'text-zinc-500 hover:text-zinc-700']">Register</button>
                                            <button @click="activeTab = 'wbs'" :class="['px-4 py-2 text-sm font-medium rounded-md transition-all', activeTab === 'wbs' ? 'bg-zinc-100 dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 shadow-sm' : 'text-zinc-500 hover:text-zinc-700']">LIST</button>

                    </div>
        <div class="flex flex-1 flex-col gap-6 p-8 bg-zinc-50/50 dark:bg-zinc-950 min-h-screen">
            
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                
<!-- Full-width Header with Title and Register Button -->
<div class="flex items-center justify-between w-full mb-6">
  <!-- Left: Title -->
  <h1 class="text-3xl font-bold tracking-tight text-zinc-900 dark:text-zinc-100">
    Resource Schedule
  </h1>

  <!-- Right: Register Button -->
  <!-- <button
    @click="activeTab = 'register'"
    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-semibold"
  >
    Create Resource Schedule
  </button> -->

  <button 
    @click="showEditModal = true" 
    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-semibold"
    style="background-color: #1C7BA5; hover:bg-color: #242a7a;"
>
    Edit Resource Schedule
</button>
</div>



                
            </div>

<!-- Single Edit Modal -->
<!-- Unified Edit Modal -->
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
      @click="closeModal(showEditModal)"
    >
      ✕
    </button>

    <h2 class="text-2xl font-bold mb-6">Edit Resource Schedule</h2>

   <!-- =======================
     MASTER SCHEDULE SECTION
======================= -->
<div class="mb-8">
  <h3 class="text-xl font-semibold mb-4">Master Schedule</h3>

  <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <!-- Training Type -->
    <div class="space-y-2">
      <label class="text-sm font-semibold">Training Type</label>
      <input
        v-model="form.batchName"
        type="text"
        class="w-full p-2.5 rounded-lg bg-zinc-50 dark:bg-zinc-800 border"
      />
    </div>

    <!-- Location -->
    <div class="space-y-2">
      <label class="text-sm font-semibold">Location</label>
      <select
        v-model="form.location"
        class="w-full p-2.5 rounded-lg bg-zinc-50 dark:bg-zinc-800 border"
      >
        <option>Cebu</option>
        <option>Manila</option>
      </select>
    </div>

    <!-- Target Trainees -->
    <div class="space-y-2">
      <label class="text-sm font-semibold">Target Trainees</label>
      <input
        v-model="form.targetTrainees"
        type="number"
        class="w-full p-2.5 rounded-lg bg-zinc-50 dark:bg-zinc-800 border"
      />
    </div>

    <!-- Deployment Date -->
    <div class="space-y-2">
      <label class="text-sm font-semibold">Deployment Date</label>
      <input
        v-model="form.deploymentDate"
        type="month"
        class="w-full p-2.5 rounded-lg bg-zinc-50 dark:bg-zinc-800 border"
      />
    </div>

    <!-- Job Acceptance -->
    <div class="space-y-2">
      <label class="text-sm font-semibold">Job Acceptance</label>
      <input
        v-model="form.jobAcceptance"
        type="number"
        class="w-full p-2.5 rounded-lg bg-zinc-50 dark:bg-zinc-800 border"
      />
    </div>

    <!-- Job Offer -->
    <div class="space-y-2">
      <label class="text-sm font-semibold">Job Offer</label>
      <input
        v-model="form.jobOffer"
        type="number"
        class="w-full p-2.5 rounded-lg bg-zinc-50 dark:bg-zinc-800 border"
      />
    </div>

    <!-- Final Interview -->
    <div class="space-y-2">
      <label class="text-sm font-semibold">Final Interview</label>
      <input
        v-model="form.finalInterview"
        type="number"
        class="w-full p-2.5 rounded-lg bg-zinc-50 dark:bg-zinc-800 border"
      />
    </div>

    <!-- Initial Interview -->
    <div class="space-y-2">
      <label class="text-sm font-semibold">Initial Interview</label>
      <input
        v-model="form.initialInterview"
        type="number"
        class="w-full p-2.5 rounded-lg bg-zinc-50 dark:bg-zinc-800 border"
      />
    </div>

    <!-- Screening -->
    <div class="space-y-2">
      <label class="text-sm font-semibold">Screening</label>
      <input
        v-model="form.screening"
        type="number"
        class="w-full p-2.5 rounded-lg bg-zinc-50 dark:bg-zinc-800 border"
      />
    </div>

    <!-- Contact Schools -->
    <div class="space-y-2">
      <label class="text-sm font-semibold">Contact Schools</label>
      <input
        v-model="form.contactSchools"
        type="number"
        class="w-full p-2.5 rounded-lg bg-zinc-50 dark:bg-zinc-800 border"
      />
    </div>

  </div>



    </div>

    <!-- =======================
         WBS TIMELINE SECTION
         ======================= -->
    <div class="mb-6">
      <h3 class="text-xl font-semibold mb-4">WBS Timeline</h3>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <template v-for="act in ganttActivities" :key="act">
          <div class="space-y-2">
            <label class="text-sm font-semibold">{{ act }}</label>
            <div class="grid grid-cols-2 gap-3">
              <select
                v-model="ganttForm[act].start"
                class="w-full p-2 rounded-lg bg-zinc-50 dark:bg-zinc-800 border"
              >
                <option value="">Start</option>
                <option v-for="w in ganttWeeks" :key="w">{{ w }}</option>
              </select>
              <select
                v-model="ganttForm[act].end"
                class="w-full p-2 rounded-lg bg-zinc-50 dark:bg-zinc-800 border"
              >
                <option value="">End</option>
                <option v-for="w in ganttWeeks" :key="w">{{ w }}</option>
              </select>
            </div>
          </div>
        </template>
      </div>

      <!-- WBS Preview -->
      <div class="mt-8">
        <h4 class="font-bold text-lg mb-2">WBS Preview</h4>
        <div class="overflow-x-auto bg-white dark:bg-zinc-900 border p-4 rounded-xl shadow-sm">
          <div
            class="grid gap-0.5"
            :style="`grid-template-columns: 220px repeat(${ganttWeeks.length}, 1fr)`"
          >
            <div class="p-2 bg-zinc-50 dark:bg-zinc-800 font-bold border-b">Activity</div>
            <template v-for="month in monthSpans" :key="month.name">
              <div
                class="text-center font-bold text-[12px] p-1 bg-blue-50 dark:bg-blue-900/20 border-b"
                :style="`grid-column: span ${month.count}`"
              >
                {{ month.name }}
              </div>
            </template>

            <div></div>
            <template v-for="w in ganttWeeks" :key="w">
              <div
                class="text-[11px] text-center p-1 bg-blue-50 dark:bg-blue-900/20 border-b text-zinc-300"
              >
                {{ extractWeek(w) }}
              </div>
            </template>

            <template v-for="row in ganttRows" :key="row.activity">
              <div class="font-semibold py-2 border-r pr-2">{{ row.activity }}</div>
              <template v-for="(_, i) in ganttWeeks" :key="i">
                <div class="border h-7 relative">
                  <div
                    v-if="i >= row.startIndex && i <= row.endIndex"
                    class="absolute inset-0 bg-blue-500/80 rounded-sm"
                  ></div>
                </div>
              </template>
            </template>
          </div>
        </div>
      </div>
    </div>

    <!-- Buttons -->
    <div class="flex justify-end mt-6 gap-3">
      <button
        @click="closeModal(showEditModal)"
        class="px-6 py-2 bg-zinc-200 dark:bg-zinc-700 rounded-lg"
      >
        Cancel
      </button>
      <button
        @click="saveAllEdits()"
        class="px-6 py-2 bg-blue-600 text-white rounded-lg"
      >
        Save All
      </button>
    </div>
  </div>
</div>

            
<!-- LIST TAB -->
<div v-if="activeTab === 'details'" class="space-y-10 animate-in fade-in">
    

    <!-- ROW 1: TARGET TRAINEES + MASTER SCHEDULE -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

<!-- LEFT COLUMN (full height) -->
<div class="flex flex-col gap-4 h-full">

<!-- FANCY LARGE BATCH TITLE -->
<div class="flex-1 text-white px-5 py-4 rounded-xl shadow-md flex flex-col items-center justify-center text-center"
     style="background-color: #2f359e;">
    <h2 class="text-2xl font-extrabold tracking-wide drop-shadow">
        ACTION 40
    </h2>
    <p class="text-sm opacity-90">
        Recruitment Batch Overview
    </p>
<!-- OPEN SINGLE EDIT MODAL -->
<!-- OPEN SINGLE EDIT MODAL -->
<!-- <button 
    @click="showEditModal = true" 
    class="px-4 py-2 text-white rounded-lg mt-3"
    style="background-color: #1C7BA5; hover:bg-color: #242a7a;"
>
    Edit 
</button> -->

</div>


<!-- TARGET TRAINEES (full width) -->
<div class="flex-1 relative bg-white dark:bg-zinc-900 p-5 rounded-xl border border-zinc-200 dark:border-zinc-800 shadow flex flex-col justify-center">

    <h3 class="text-sm font-bold text-zinc-700 dark:text-zinc-200 uppercase tracking-wide mb-4 text-center">
        Target Number of Trainees
    </h3>

<!-- Table-like block (full width) -->
<div class="flex flex-col gap-3 text-base font-medium w-full">

    <div class="flex justify-between items-center bg-zinc-100 dark:bg-zinc-800 px-4 py-3 rounded-lg border text-lg w-full">
        <span class="font-semibold">Cebu</span>
        <div class="flex items-center gap-2">
            <span class="font-extrabold text-blue-600">15</span>
                <Users class="w-4 h-4 text-blue-600" />

        </div>
    </div>

</div>

</div>

</div>


    <!-- RIGHT COLUMN — MASTER SCHEDULE -->
    <div class="md:col-span-2 relative bg-white dark:bg-zinc-900 p-6 rounded-xl border border-zinc-200 dark:border-zinc-800 shadow h-full">
    

    <h2 class="text-lg font-bold mb-4">Master Schedule</h2>

        <table class="w-full text-sm border">
            <tbody>
                <tr>
                    <td class="font-semibold px-3 py-2 border">Training Type</td>
                    <td class="px-3 py-2 border font-semibold">ACTION 40</td>
                </tr>
                <tr>
                    <td class="font-semibold px-3 py-2 border">Target Trainees</td>
                    <td class="px-3 py-2 border font-semibold">15</td>
                </tr>
                <tr>
                    <td class="font-semibold px-3 py-2 border">Deployment</td>
                    <td class="px-3 py-2 border font-semibold">July 2025</td>
                </tr>
                <tr>
                    <td class="font-semibold px-3 py-2 border">Job Acceptance</td>
                    <td class="px-3 py-2 border">16</td>
                </tr>
                <tr>
                    <td class="font-semibold px-3 py-2 border">Job Offer</td>
                    <td class="px-3 py-2 border">27</td>
                </tr>
                <tr>
                    <td class="font-semibold px-3 py-2 border">Final Interview</td>
                    <td class="px-3 py-2 border">32</td>
                </tr>
                <tr>
                    <td class="font-semibold px-3 py-2 border">Initial Interview</td>
                    <td class="px-3 py-2 border">66</td>
                </tr>
                <tr>
                    <td class="font-semibold px-3 py-2 border">Screening</td>
                    <td class="px-3 py-2 border">300</td>
                </tr>
                <tr>
                    <td class="font-semibold px-3 py-2 border">Contact Schools</td>
                    <td class="px-3 py-2 border">—</td>
                </tr>
            </tbody>
        </table>
    </div>
    </div>

    <!-- ROW 2: RECRUITMENT PROJECTION -->
<div class="relative bg-white dark:bg-zinc-900 p-6 rounded-xl border border-zinc-200 dark:border-zinc-800 shadow">

    <h2 class="text-lg font-bold mb-4">Recruitment Projection</h2>

<table class="table-auto border-collapse border w-full text-sm">
  <thead>
    <!-- First row: Stage + group headers -->
    <tr class="bg-zinc-100 dark:bg-zinc-800 text-center font-bold">
      <th rowspan="2" class="border px-2 py-1">Stage</th>
      <th colspan="2" class="border px-2 py-1">ACTION 38 (Actual)</th>
      <th colspan="2" class="border px-2 py-1">ACTION 40 (Plan)</th>
    </tr>
    <!-- Second row: sub-columns -->
    <tr class="bg-zinc-50 dark:bg-zinc-700 text-center font-semibold">
      <th class="border px-2 py-1">No.</th>
      <th class="border px-2 py-1">%</th>
      <th class="border px-2 py-1">No.</th>
      <th class="border px-2 py-1">%</th>
    </tr>
  </thead>

            <tbody>
                <tr>
                    <td class="px-3 py-2 border font-semibold">Examinees</td>
                    <td class="px-3 py-2 border font-semibold">619</td>
                    <td class="px-3 py-2 border">—</td>
                    <td class="px-3 py-2 border font-semibold text-blue-600">300</td>
                    <td class="px-3 py-2 border">—</td>
                </tr>

                <tr>
                    <td class="px-3 py-2 border font-semibold">Undergone Initial Interview</td>
                    <td class="px-3 py-2 border">137</td>
                    <td class="px-3 py-2 border">22%</td>
                    <td class="px-3 py-2 border font-semibold text-blue-600">66</td>
                    <td class="px-3 py-2 border">22%</td>
                </tr>

                <tr>
                    <td class="px-3 py-2 border font-semibold">Undergone Final Interview</td>
                    <td class="px-3 py-2 border">65</td>
                    <td class="px-3 py-2 border">11%</td>
                    <td class="px-3 py-2 border font-semibold text-blue-600">32</td>
                    <td class="px-3 py-2 border">11%</td>
                </tr>

                <tr>
                    <td class="px-3 py-2 border font-semibold">Job Offer</td>
                    <td class="px-3 py-2 border">55</td>
                    <td class="px-3 py-2 border">9%</td>
                    <td class="px-3 py-2 border font-semibold text-blue-600">27</td>
                    <td class="px-3 py-2 border">9%</td>
                </tr>

                <tr>
                    <td class="px-3 py-2 border pl-6">Accepted</td>
                    <td class="px-3 py-2 border">34</td>
                    <td class="px-3 py-2 border">5%</td>
                    <td class="px-3 py-2 border font-semibold text-blue-600">16</td>
                    <td class="px-3 py-2 border">5%</td>
                </tr>

                <tr>
                    <td class="px-3 py-2 border pl-6">Declined</td>
                    <td class="px-3 py-2 border">21</td>
                    <td class="px-3 py-2 border">3%</td>
                    <td class="px-3 py-2 border font-semibold text-blue-600">10</td>
                    <td class="px-3 py-2 border">3%</td>
                </tr>

                <tr>
                    <td class="px-3 py-2 border font-semibold">Trainees from Manila</td>
                    <td class="px-3 py-2 border">2</td>
                    <td class="px-3 py-2 border">0.32%</td>
                    <td class="px-3 py-2 border font-semibold text-blue-600">0</td>
                    <td class="px-3 py-2 border">0%</td>
                </tr>

                <tr>
                    <td class="px-3 py-2 border font-semibold">Trainees from Cebu</td>
                    <td class="px-3 py-2 border">34</td>
                    <td class="px-3 py-2 border">5%</td>
                    <td class="px-3 py-2 border font-bold text-blue-600">16</td>
                    <td class="px-3 py-2 border">5%</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- ROW 3: WBS TIMELINE -->
<div class="relative bg-white dark:bg-zinc-900 p-6 rounded-xl border border-zinc-200 dark:border-zinc-800 shadow">

        <h2 class="text-lg font-bold mb-4">WBS Timeline</h2>

        <!-- REUSE YOUR CURRENT WBS GANTT PREVIEW -->
        <div class="overflow-x-auto">
            <div class="overflow-x-auto bg-white dark:bg-zinc-900 border p-6 rounded-xl shadow-sm">

        <div class="grid gap-0.5"
             :style="`grid-template-columns: 220px repeat(${ganttWeeks.length}, 1fr)`">

            <!-- MONTH HEADER -->
            <div class="p-2 bg-zinc-50 dark:bg-zinc-800 font-bold border-b">
                Activity
            </div>

            <template v-for="month in monthSpans" :key="month.name">
                <div
                    class="text-center font-bold text-[12px] p-1 bg-blue-50 dark:bg-blue-900/20 border-b"
                    :style="`grid-column: span ${month.count}`"
                >
                    {{ month.name }}
                </div>
            </template>

            <!-- WEEK HEADER -->
            <div></div>
            <template v-for="w in ganttWeeks" :key="w">
                <div class="text-[11px] text-center p-1 bg-blue-50 dark:bg-blue-900/20 border-b">
                    {{ extractWeek(w) }}
                </div>
            </template>

            <!-- ROWS -->
            <template v-for="row in ganttRows" :key="row.activity">
                <div class="font-semibold py-2 border-r pr-2">
                    {{ row.activity }}
                </div>

                <template v-for="(_,i) in ganttWeeks" :key="i">
                    <div class="border h-7 relative">
                        <div v-if="i >= row.startIndex && i <= row.endIndex"
                             class="absolute inset-0 bg-blue-500/80 rounded-sm">
                        </div>
                    </div>
                </template>
            </template>

            

        </div>
    </div>
        </div>
    </div>

</div>



           <div v-if="activeTab === 'register'" class="max-w-5xl mx-auto w-full space-y-10 animate-in fade-in zoom-in-95">

    <div class="bg-white dark:bg-zinc-900 p-10 rounded-2xl border border-zinc-200 dark:border-zinc-800 shadow-xl space-y-10">

        <!-- Title -->
        <h2 class="text-2xl font-bold flex items-center gap-2">
    Create Resource Schedule
</h2>

        <!-- Basic Form -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="space-y-2">
                <label class="text-sm font-semibold">Batch Name</label>
                <input v-model="form.batchName" type="text"
                       class="w-full bg-zinc-50 dark:bg-zinc-800 border rounded-lg p-2.5" />
            </div>
            <div class="space-y-2">
                <label class="text-sm font-semibold">Target Location</label>
                <select v-model="form.location"
                        class="w-full bg-zinc-50 dark:bg-zinc-800 border rounded-lg p-2.5">
                    <option>Cebu</option>
                    <option>Manila</option>
                </select>
            </div>
                <div class="space-y-2">
                    <label class="text-sm font-semibold">Target Trainees</label>
                    <input v-model="form.targetTrainees" type="number"
                        class="w-full bg-zinc-50 dark:bg-zinc-800 border rounded-lg p-2.5" />
                </div>
            <div class="space-y-2">
                <label class="text-sm font-semibold">Date of Deployment</label>
                <input v-model="form.deploymentDate" type="month"
                       class="w-full bg-zinc-50 dark:bg-zinc-800 border rounded-lg p-2.5" />
            </div>
        </div>

        <!-- Recruitment Projection Section -->
<!-- <div class="mt-12">
    <h2 class="text-xl font-bold mb-4 text-zinc-700 dark:text-zinc-200">
        Recruitment Plan
    </h2>

    <div class="bg-white dark:bg-zinc-900 p-6 rounded-xl border border-zinc-200 dark:border-zinc-800 shadow-sm space-y-6">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <!-- Examinees -->
            <!-- <div class="space-y-1">
                <label class="text-sm font-semibold">Examinees</label>
                <input v-model="form.recruitmentProjection.examinees"
                       type="number"
                       class="w-full p-2.5 rounded-lg bg-zinc-50 dark:bg-zinc-800 border" />
            </div> -->

            <!-- Initial Interview -->
            <!-- <div class="space-y-1">
                <label class="text-sm font-semibold">Undergone Initial Interview</label>
                <input v-model="form.recruitmentProjection.initialInterview"
                       type="number"
                       class="w-full p-2.5 rounded-lg bg-zinc-50 dark:bg-zinc-800 border" />
            </div> -->

            <!-- Final Interview -->
            <!-- <div class="space-y-1">
                <label class="text-sm font-semibold">Undergone Final Interview</label>
                <input v-model="form.recruitmentProjection.finalInterview"
                       type="number"
                       class="w-full p-2.5 rounded-lg bg-zinc-50 dark:bg-zinc-800 border" />
            </div> -->

            <!-- Job Offer Total -->
            <!-- <div class="space-y-1">
                <label class="text-sm font-semibold">Job Offer</label>
                <input v-model="form.recruitmentProjection.jobOffer"
                       type="number"
                       class="w-full p-2.5 rounded-lg bg-zinc-50 dark:bg-zinc-800 border" />
            </div> -->

            <!-- Job Offer Accepted -->
            <!-- <div class="space-y-1 pl-4 border-l-4 border-emerald-400">
                <label class="text-sm font-semibold">Job Offer — Accepted</label>
                <input v-model="form.recruitmentProjection.jobOfferAccepted"
                       type="number"
                       class="w-full p-2.5 rounded-lg bg-zinc-50 dark:bg-zinc-800 border" />
            </div> -->

            <!-- Job Offer Declined -->
            <!-- <div class="space-y-1 pl-4 border-l-4 border-red-400">
                <label class="text-sm font-semibold">Job Offer — Declined</label>
                <input v-model="form.recruitmentProjection.jobOfferDeclined"
                       type="number"
                       class="w-full p-2.5 rounded-lg bg-zinc-50 dark:bg-zinc-800 border" />
            </div> -->

            <!-- Trainees Manila
            <div class="space-y-1">
                <label class="text-sm font-semibold">Trainees from Manila</label>
                <input v-model="form.recruitmentProjection.traineesManila"
                       type="number"
                       class="w-full p-2.5 rounded-lg bg-zinc-50 dark:bg-zinc-800 border" />
            </div>

            Trainees Cebu
            <div class="space-y-1">
                <label class="text-sm font-semibold">Trainees from Cebu</label>
                <input v-model="form.recruitmentProjection.traineesCebu"
                       type="number"
                       class="w-full p-2.5 rounded-lg bg-zinc-50 dark:bg-zinc-800 border" />
            </div> -->

        <!-- </div> -->
    <!-- </div>
</div> -->

        <!-- WBS Title -->
        <h2 class="text-xl font-bold text-zinc-700 dark:text-zinc-200">
            Work Breakdown Schedule (WBS)
        </h2>

        <!-- WBS Form Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <template v-for="act in ganttActivities" :key="act">
                <div class="space-y-2">
                    <label class="text-sm font-semibold">{{ act }}</label>

                    <div class="grid grid-cols-2 gap-3">
                        <!-- Start -->
                        <select v-model="ganttForm[act].start"
                                class="w-full bg-zinc-50 dark:bg-zinc-800 border rounded-lg p-2">
                            <option value="">Start</option>
                            <option v-for="w in ganttWeeks" :key="w">{{ w }}</option>
                        </select>

                        <!-- End -->
                        <select v-model="ganttForm[act].end"
                                class="w-full bg-zinc-50 dark:bg-zinc-800 border rounded-lg p-2">
                            <option value="">End</option>
                            <option v-for="w in ganttWeeks" :key="w">{{ w }}</option>
                        </select>
                    </div>
                </div>
            </template>
        </div>

       <!-- Gantt Preview -->
<div class="mt-12">
    <h2 class="font-bold text-xl mb-4">WBS Preview</h2>

    <div class="overflow-x-auto bg-white dark:bg-zinc-900 border p-6 rounded-xl shadow-sm">

        <div class="grid gap-0.5"
             :style="`grid-template-columns: 220px repeat(${ganttWeeks.length}, 1fr)`">

            <!-- MONTH HEADER -->
            <div class="p-2 bg-zinc-50 dark:bg-zinc-800 font-bold border-b">
                Activity
            </div>

            <template v-for="month in monthSpans" :key="month.name">
                <div
                    class="text-center font-bold text-[12px] p-1 bg-blue-50 dark:bg-blue-900/20 border-b"
                    :style="`grid-column: span ${month.count}`"
                >
                    {{ month.name }}
                </div>
            </template>

            <!-- WEEK HEADER -->
            <div></div>
            <template v-for="w in ganttWeeks" :key="w">
                <div class="text-[11px] text-center p-1 bg-blue-50 dark:bg-blue-900/20 border-b">
                    {{ extractWeek(w) }}
                </div>
            </template>

            <!-- ROWS -->
            <template v-for="row in ganttRows" :key="row.activity">
                <div class="font-semibold py-2 border-r pr-2">
                    {{ row.activity }}
                </div>

                <template v-for="(_,i) in ganttWeeks" :key="i">
                    <div class="border h-7 relative">
                        <div v-if="i >= row.startIndex && i <= row.endIndex"
                             class="absolute inset-0 bg-blue-500/80 rounded-sm">
                        </div>
                    </div>
                </template>
            </template>

        </div>
    </div>
</div>


        <!-- Buttons -->
        <div class="flex justify-end gap-3">
            <button @click="activeTab = 'list'"
                    class="px-6 py-2.5 text-sm font-medium text-zinc-600 hover:text-zinc-900">
                Cancel
            </button>
            <button
                class="px-8 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-bold shadow-lg shadow-blue-500/20">
                Create
            </button>
        </div>

    </div>
</div>

<!--ACTUAL LIST TAB -->
<div v-if="activeTab === 'wbs'" class="space-y-10 animate-in fade-in">

  <!-- Search -->
  <div class="flex gap-4 mb-4">
<div class="relative w-full">
    <span class="absolute inset-y-0 left-3 flex items-center text-zinc-500">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M21 21l-4.35-4.35m0 0A7 7 0 1010.3 3a7 7 0 006.35 13.65z" />
        </svg>
    </span>

    <input
        v-model="searchQuery"
        type="text"
        placeholder="Search by Training Type, Deployment, or Location"
        class="w-full pl-10 pr-3 py-2 rounded-lg border bg-white dark:bg-zinc-900 dark:border-zinc-700"
    />
</div>
           
  </div>

  <!-- Resource Schedule Table -->
  <table class="w-full table-auto border-collapse border text-sm">
    <thead class="bg-zinc-100 dark:bg-zinc-800 text-left">
      <tr>
        <th class="border px-3 py-2">Training Type</th>
        <th class="border px-3 py-2">Target Trainees</th>
        <th class="border px-3 py-2">Deployment</th>
        <th class="border px-3 py-2">Location</th>
      </tr>
    </thead>
    <tbody class="bg-white dark:bg-zinc-900">
      <tr v-for="(rs,index) in filteredSchedules" :key="index">
        <td class="border px-3 py-2">
          <a :href="`/resource-schedule/${rs.id}`" target="_blank"
             class="text-blue-600 hover:underline">
            {{ rs.batchName }}
          </a>
        </td>
        <td class="border px-3 py-2">{{ rs.targetTrainees }}</td>
        <td class="border px-3 py-2">{{ rs.deploymentDate }}</td>
        <td class="border px-3 py-2">{{ rs.location }}</td>
      </tr>
    </tbody>
  </table>

</div>


        </div>
    </AppLayout>
</template>

<style scoped>
/* Custom transitions for a 'systematized' feel */
.fade-in { animation: fadeIn 0.3s ease-out; }
@keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
</style>

