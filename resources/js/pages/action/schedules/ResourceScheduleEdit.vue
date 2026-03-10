<script setup lang="ts">
import { Head, useForm, usePage, Link } from '@inertiajs/vue3';
import { ref, computed, onMounted, watch } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';

const props = defineProps<{
  schedule: any,
  newBatches: Record<number, string>,
  prevBatches: Record<number, string>,
  errorMessages: Record<string, string>,
}>();

// Form initialization with existing schedule
const form = useForm({
  action_batch_id: props.schedule.action_batch_id || '',
  prev_batch_id: props.schedule.prev_batch_id || '',
  target_location: props.schedule.target_location || '',
  target_trainees: props.schedule.target_trainees || '',
  deployment_date: props.schedule.deployment_date || '',
  remarks: props.schedule.remarks || '',
});

// Gantt activities
const ganttActivities = [
  "contact_schools",
  "source_testing",
  "initial_interviews",
  "final_interviews",
  "contract_offers",
  "requirements",
  "training"
];

// Reactive Gantt inputs
const ganttForm = ref(
  Object.fromEntries(
    ganttActivities.map(a => [a, {
      start: props.schedule[`${a}_startdate`] || '',
      end: props.schedule[`${a}_enddate`] || '',
      error: ''
    }])
  )
);

// Watch for prop changes to update ganttForm if needed
watch(() => props.schedule, (newSchedule) => {
  if (newSchedule) {
    ganttActivities.forEach(act => {
      ganttForm.value[act].start = newSchedule[`${act}_startdate`] || '';
      ganttForm.value[act].end = newSchedule[`${act}_enddate`] || '';
    });
  }
}, { deep: true });

// Gantt computed helpers
function weekToKey(weekStr: string) { if(!weekStr) return null; const [y,w]=weekStr.split("-W").map(Number); return y*100+w; }
function formatWeekLabel(weekStr: string){ if(!weekStr) return ''; const [y,w]=weekStr.split("-W").map(Number); const jan4=new Date(y,0,4); const weekStart=new Date(jan4.getTime()+(w-1)*7*86400000); const month=weekStart.toLocaleString("en-US",{month:"short"}); return `${month} W${w}`; }

const ganttWeeks = computed(() => {
  const keys:number[]=[]; ganttActivities.forEach(act=>{
    const {start,end}=ganttForm.value[act]; if(!start||!end) return; let y=Math.floor(weekToKey(start)!/100); let w=weekToKey(start)!%100; const endY=Math.floor(weekToKey(end)!/100); const endW=weekToKey(end)!%100; while(y<endY||(y===endY&&w<=endW)){ keys.push(y*100+w); w++; if(w>52){w=1;y++;}}}); return Array.from(new Set(keys)).sort((a,b)=>a-b).map(k=>{ const year=Math.floor(k/100); const week=k%100; return `${year}-W${String(week).padStart(2,'0')}`;});
});

const monthSpans = computed(() => {
  const spans:any[]=[]; let current:any=null; ganttWeeks.value.forEach(w=>{
    const label=formatWeekLabel(w); const month=label.split(" ")[0];
    if(!current||current.month!==month){ current={month,count:1}; spans.push(current);} else {current.count++;}
  }); return spans;
});

const ganttRows = computed(() => ganttActivities.map(act=>{
  const {start,end}=ganttForm.value[act];
  return {activity:act,startIndex:ganttWeeks.value.indexOf(start),endIndex:ganttWeeks.value.indexOf(end)};
}));

// WBS colors
const wbsColors:Record<string,string>={
  contact_schools:'#166534',
  source_testing:'#dc2626',
  initial_interviews:'#f97316',
  final_interviews:'#2563eb',
  contract_offers:'#7c3aed',
  requirements:'#ec4899',
  training:'#84cc16'
};

// Flash & error handling
const page=usePage(); const errorMessage=ref((page.props.flash as any)?.error||''); const showError=ref(Boolean(errorMessage.value));
onMounted(()=>{if(errorMessage.value){setTimeout(()=>showError.value=false,5000);}}); function closeError(){showError.value=false;}

// Format activity labels
function formatActivityName(key:string){ const names:Record<string,string>={contact_schools:"Contact Schools",source_testing:"Sourcing & Testing",initial_interviews:"Initial Interviews",final_interviews:"Final Interviews",contract_offers:"Contract Offers",requirements:"Requirements",training:"Training"}; return names[key]||key;}

// Submit
function submitForm(){
  ganttActivities.forEach(act=>{
    (form as any)[`${act}_startdate`]=ganttForm.value[act].start;
    (form as any)[`${act}_enddate`]=ganttForm.value[act].end;
  });
  form.put(`/action/schedules/${props.schedule.id}`,{ preserveScroll:true, onSuccess:()=>{}, onError:(errors)=>{ ganttActivities.forEach(act=>{ganttForm.value[act].error=errors[`${act}_startdate`]||errors[`${act}_enddate`]||''; }); }});
}
</script>

<template>
  <Head title="Edit Resource Schedule" />

  <AppLayout>
    <div class="max-w-5xl mx-auto w-full space-y-10 p-8">
      <h1 class="text-3xl font-bold mb-6">Edit Resource Schedule</h1>

      <!-- Error Notification -->
      <div v-if="showError" class="fixed top-4 left-1/2 -translate-x-1/2 z-50 w-full max-w-full px-4">
        <div class="relative bg-red-500 border-red-200 rounded-lg shadow-md p-4 flex items-center gap-4 animate-slide-down">
          <div class="flex-1 flex justify-start items-center gap-3">
            <p class="text-white text-m font-medium text-left">{{ errorMessage }}</p>
          </div>
          <button 
            style="all: unset; cursor: pointer; display: flex; align-items: center; justify-content: center; width: 28px; height: 28px; border-radius: 50%; background-color: rgba(0, 0, 0, 0.3); color: white; font-weight: bold; font-size: 1rem;"
            @click="closeError"
          >
            X
          </button>
        </div>
      </div>

      <div class="bg-white dark:bg-zinc-900 p-10 rounded-2xl border shadow-xl space-y-10">
        <form @submit.prevent="submitForm">

          <!-- Basic Info -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Batch Name -->
            <div>
              <label class="text-sm font-semibold">Batch Name</label>
              <select v-model="form.action_batch_id" class="w-full bg-zinc-50 border rounded-lg p-2.5">
                <option value="">Select</option>
                <option v-for="batch in newBatches" :key="batch.id" :value="batch.id">
                  {{ batch.action_batch }}
                </option>
              </select>


              <p v-if="form.errors.action_batch_id" class="text-red-600 text-xs mt-1">{{ form.errors.action_batch_id }}</p>
            </div>

            <!-- Target Location -->
            <div>
              <label class="text-sm font-semibold">Target Location</label>
              <select v-model="form.target_location" class="w-full bg-zinc-50 border rounded-lg p-2.5">
                <option value="">Select</option>
                <option value="1">Manila</option>
                <option value="2">Cebu</option>
              </select>
              <p v-if="form.errors.target_location" class="text-red-600 text-xs mt-1">{{ form.errors.target_location }}</p>
            </div>

            <!-- Previous Batch -->
            <div>
              <label class="text-sm font-semibold">Compare with Previous Batch</label>
              <select v-model="form.prev_batch_id" class="w-full bg-zinc-50 border rounded-lg p-2.5">
                <option value="">Select</option>
                <option v-for="batch in prevBatches" :key="batch.id" :value="batch.id">
                  {{ batch.action_batch }}
                </option>
              </select>
              <p v-if="form.errors.prev_batch_id" class="text-red-600 text-xs mt-1">{{ form.errors.prev_batch_id }}</p>
            </div>

            <!-- Target Trainees -->
            <div>
              <label class="text-sm font-semibold">Target Trainees</label>
              <input v-model="form.target_trainees" type="number" disabled class="w-full bg-zinc-50 border rounded-lg p-2.5" />
              <p v-if="form.errors.target_trainees" class="text-red-600 text-xs mt-1">{{ form.errors.target_trainees }}</p>
            </div>

            <!-- Date of Deployment -->
            <div>
              <label class="text-sm font-semibold">Date of Deployment</label>
              <input v-model="form.deployment_date" readonly class="w-full bg-zinc-50 border rounded-lg p-2.5" />
              <p v-if="form.errors.deployment_date" class="text-red-600 text-xs mt-1">{{ form.errors.deployment_date }}</p>
            </div>
          </div>

          <!-- Gantt Section -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-10 mt-10">
            <template v-for="act in ganttActivities" :key="act">
              <div>
                <label class="font-semibold">{{ formatActivityName(act) }}</label>
                <div class="grid grid-cols-2 gap-3 mt-1 mb-1">
                  <div>
                    <label class="text-xs text-zinc-500 block mb-1">Start</label>
                    <input type="week" v-model="ganttForm[act].start" class="w-full bg-zinc-50 border rounded-lg p-2" />
                  </div>
                  <div>
                    <label class="text-xs text-zinc-500 block mb-1">End</label>
                    <input type="week" v-model="ganttForm[act].end" class="w-full bg-zinc-50 border rounded-lg p-2" />
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
                  <div :style="`grid-column: span ${m.count}`" class="text-center font-bold text-base p-2 bg-blue-50 border-b">
                    {{ m.month }}
                  </div>
                </template>
                <div></div>
                <template v-for="w in ganttWeeks" :key="w">
                  <div class="text-[11px] text-center p-1 bg-blue-50 border-b">{{ formatWeekLabel(w) }}</div>
                </template>

                <template v-for="row in ganttRows" :key="row.activity">
                  <div class="font-semibold py-2 border-r pr-2">{{ formatActivityName(row.activity) }}</div>
                  <template v-for="(_, i) in ganttWeeks" :key="i">
                    <div class="border h-7 relative">
                      <div v-if="i >= row.startIndex && i <= row.endIndex && row.startIndex !== -1"
                        class="absolute inset-0 rounded-sm"
                        :style="`background-color: ${wbsColors[row.activity] || '#000'}; opacity: 0.8;`">
                      </div>
                    </div>
                  </template>
                </template>
              </div>
            </div>
            <div v-else class="text-sm text-zinc-500">Select weeks to generate preview.</div>
          </div>

          <!-- Remarks -->
          <div class="mt-10">
            <label class="text-sm font-semibold">Remarks</label>
            <textarea v-model="form.remarks" class="w-full bg-zinc-50 border rounded-lg p-2.5 mt-1" rows="4"></textarea>
            <p v-if="form.errors.remarks" class="text-red-600 text-xs mt-1">{{ form.errors.remarks }}</p>
          </div>

          <!-- Buttons -->
          <div class="form-actions">
            <Link href="/action/schedules" class="btn btn-secondary">Cancel</Link>
            <button type="submit" :disabled="form.processing" class="btn btn-primary">
              {{ form.processing ? 'Updating…' : 'Update' }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </AppLayout>
</template>



<style scoped>
@keyframes slide-down { from {opacity:0; transform:translateY(-20px);} to {opacity:1; transform:translateY(0);} }
.animate-slide-down { animation: slide-down 0.3s ease-out; }
.form-actions { margin-top:2rem; display:flex; justify-content:flex-end; align-items:center; gap:0.5rem;}
.form-actions button,.form-actions a{flex:0 0 auto; width:auto;}
button[type="submit"],.btn-secondary{padding:0.5rem 1.2rem;font-size:0.85rem;border-radius:5px;font-weight:500;white-space:nowrap;transition:background 0.15s ease;}
button[type="submit"]{border:none; background:var(--ats-primary); color:#fff; cursor:pointer;}
button[type="submit"]:hover:not(:disabled){background:var(--ats-accent);}
button[type="submit"]:disabled{opacity:0.6; cursor:not-allowed;}
.btn-secondary{border:1px solid #d1d5db; background:#f3f4f6; color:#374151; text-decoration:none;}
.btn-secondary:hover{background:#e5e7eb;}
</style>