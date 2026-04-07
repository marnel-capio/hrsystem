<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { router, usePage, Head } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'

const page = usePage<any>()
const loading = ref(false)

const form = ref({
  engagement_type: '',
  sourcing_type: '',
  request_type: '',
  replacement_due_to: '',
  person_to_replace: '',
  location_assignment: '',
  custom_location: '', 
  project_id: '', 
  business_unit: '',
  resource: '',
  practice: '',
  no_resources_needed: '',
  start_date: '',
  duration_project_engagement: '',
  required_skills: '',
  preferred_skills: '',
  role: '',
  remarks: '',
  project_description: '', 
  processing: false,
})

const projects = ref([]) 
const selectedProject = ref(null) 

const engagementTypeError = ref('')
const sourcingTypeError = ref('')
const requestTypeError = ref('')
const replacementDueToError = ref('')
const personToReplaceError = ref('')
const locationAssignmentError = ref('')
const startDateError = ref('')

const today = new Date().toISOString().slice(0, 10)

const validateEngagementType = () => {
  engagementTypeError.value = !form.value.engagement_type ? 'This field is required.' : ''
}

const validateSourcingType = () => {
  sourcingTypeError.value = !form.value.sourcing_type ? 'This field is required.' : ''
}

const validateRequestType = () => {
  requestTypeError.value = !form.value.request_type ? 'This field is required.' : ''
}

const validateReplacementDueTo = () => {
  replacementDueToError.value = form.value.request_type === '2' && !form.value.replacement_due_to
    ? 'This field is required when replacement is selected.'
    : ''
}

const validatePersonToReplace = () => {
  personToReplaceError.value = form.value.request_type === '2' && !form.value.person_to_replace
    ? 'Please provide the person to replace.'
    : ''
}

const validateLocationAssignment = () => {
  locationAssignmentError.value = !form.value.location_assignment ? 'This field is required.' : ''
}

const validateStartDate = () => {
  startDateError.value = form.value.start_date && form.value.start_date <= today
    ? 'The selected date must be in the future.'
    : ''
}

// Fetch projects from the backend
onMounted(async () => {
  const response = await fetch('/projects');
  const data = await response.json();
  projects.value = data; // Store projects in the reactive variable
});

// Update project description when a project is selected
const updateProjectDescription = (projectId: string) => {
  const project = projects.value.find((p: any) => p.id === projectId);
  if (project) {
    form.value.project_description = project.project_description;
  }
}

const submit = () => {
  // Clear frontend validation errors
  engagementTypeError.value = ''
  sourcingTypeError.value = ''
  requestTypeError.value = ''
  replacementDueToError.value = ''
  personToReplaceError.value = ''
  locationAssignmentError.value = ''
  startDateError.value = ''

  form.value.processing = true
  loading.value = true

  router.post('/resource/requisitions', form.value, {
    onFinish: () => {
      form.value.processing = false
      loading.value = false
    }
  })
}
</script>

<template>
  <Head title="Resource Requisition Register" />

  <AppLayout :errors="page.props.errors">
    <div class="flex justify-between items-center mx-5 mb-3">
      <h2 class="text-xl font-bold">Create Resource Requisition</h2>
    </div>

    <!-- Form -->
    <div class="text-xs overflow-x-auto mt-6 p-6 bg-white shadow-lg rounded-lg border w-3/4 mx-auto">
    <!-- <div class="bg-[#2811C2] text-white text-center py-1 mb-2 border-b-4 border-t-4 border-black w-full">
      <strong class="text-lg">RESOURCE REQUISITION FORM</strong>
    </div>-->
    <div class="text-xs mb-3 mt-2 text-red-600">
      <strong>Note:</strong> Resource Requisition must be already approved by SR Manager.
    </div><br><br>


      <!-- Engagement Type, Sourcing Type, Request Type (Beside Each Other) -->
      <div class="grid grid-cols-3 gap-5">
        <!-- Engagement Type -->
        <div class="flex flex-col">
          <label class="text-sm font-semibold mb-1">Engagement Type</label>
          <select v-model="form.engagement_type" @change="validateEngagementType" class="border p-2 rounded w-full">
            <option disabled value="">Select Engagement Type</option>
            <option value="1">Permanent</option>
            <option value="2">Temporary (Consultant)</option>
            <option value="3">OJT</option>
          </select>
          <span v-if="engagementTypeError" class="text-red-600 text-sm mt-1">{{ engagementTypeError }}</span>
        </div>

        <!-- Sourcing Type -->
        <div class="flex flex-col">
          <label class="text-sm font-semibold mb-1">Sourcing Type</label>
          <select v-model="form.sourcing_type" @change="validateSourcingType" class="border p-2 rounded w-full">
            <option disabled value="">Select Sourcing Type</option>
            <option value="1">Internal</option>
            <option value="2">External</option>
            <option value="3">Either</option>
          </select>
          <span v-if="sourcingTypeError" class="text-red-600 text-sm mt-1">{{ sourcingTypeError }}</span>
        </div>

        <!-- Request Type -->
        <div class="flex flex-col">
          <label class="text-sm font-semibold mb-1">Request Type</label>
          <select v-model="form.request_type" @change="validateRequestType" class="border p-2 rounded w-full">
            <option disabled value="">Select Request Type</option>
            <option value="1">New Requirement</option>
            <option value="2">Replacement</option>
          </select>
          <span v-if="requestTypeError" class="text-red-600 text-sm mt-1">{{ requestTypeError }}</span>
        </div>
      </div>

      <!-- If Replacement, Due To, Person to Replace, Location Assignment, and Custom Field in the 2nd Row -->
      <div class="grid grid-cols-4 gap-5 mt-5 w-full">
        <!-- If Replacement, Due To -->
        <div class="flex flex-col w-full">
          <label class="text-sm font-semibold mb-1">If Replacement, Due To</label>
          <select v-model="form.replacement_due_to" @change="validateReplacementDueTo" class="border p-2 rounded w-full" :disabled="form.request_type !== '2'" :class="{'bg-gray-200 cursor-not-allowed': form.request_type !== '2'}">
            <option disabled value="">Select Reason</option>
            <option value="1">Promotion</option>
            <option value="2">Attrition</option>
            <option value="3">Backfill</option>
            <option value="4">Transfer</option>
          </select>
          <span v-if="replacementDueToError" class="text-red-600 text-sm mt-1">{{ replacementDueToError }}</span>
        </div>

        <!-- Person to Replace -->
        <div class="flex flex-col">
          <label class="text-sm font-semibold mb-1">Person to Replace</label>
          <input
            v-model="form.person_to_replace"
            @input="validatePersonToReplace"
            type="text"
            placeholder="Person to Replace"
            class="border p-2 rounded w-full"
            :disabled="form.request_type !== '2'"
            :class="{'bg-gray-200 cursor-not-allowed': form.request_type !== '2'}"
          />
          <span v-if="personToReplaceError" class="text-red-600 text-sm mt-1">{{ personToReplaceError }}</span>
        </div>

        <!-- Location Assignment -->
        <div class="flex flex-col">
          <label class="text-sm font-semibold mb-1 w-full">Location Assignment</label>
          <select v-model="form.location_assignment" @change="validateLocationAssignment" class="border p-2 rounded w-full">
            <option disabled value="">Select Location</option>
            <option value="1">Alabang</option>
            <option value="2">Makati</option>
            <option value="3">Cebu</option>
            <option value="4">Japan</option>
            <option value="5">China</option>
            <option value="6">Other</option> <!-- Added "Other" option -->
          </select>
          <span v-if="locationAssignmentError" class="text-red-600 text-sm mt-1">{{ locationAssignmentError }}</span>
        </div>

        <!-- Custom Location Input -->
        <div class="flex flex-col">
          <label class="text-sm font-semibold mb-1">Custom Location</label>
          <input
            v-model="form.custom_location"
            type="text"
            :class="{'bg-gray-200 cursor-not-allowed': form.location_assignment !== '6'}"
            class="border p-2 rounded w-full"
            placeholder="Specify location"
            :disabled="form.location_assignment !== '6'"
          />
        </div>
      </div>

      <!-- Project Name, Business Unit -->
      <div class="grid grid-cols-2 gap-5 mt-5">
        <!-- Project Name (Dropdown) -->
        <div class="flex flex-col">
          <label class="text-sm font-semibold mb-1">Project Name</label>
          <select v-model="form.project_id" @change="updateProjectDescription(form.project_id)" class="border p-2 rounded w-full">
            <option disabled value="">Select Project</option>
            <option v-for="project in projects" :key="project.id" :value="project.id">{{ project.name }}</option>
          </select>
        </div>

        <!-- Business Unit -->
        <div class="flex flex-col">
          <label class="text-sm font-semibold mb-1">Business Unit</label>
          <input
            v-model="form.business_unit"
            type="text"
            placeholder="Business Unit"
            class="border p-2 rounded w-full"
          />
        </div>
      </div>
      <div class="flex flex-col mt-5">
          <label class="text-sm font-semibold mb-1">Project Description</label>
          <textarea
            v-model="form.project_description"
            type="text"
            placeholder="Project descrition will auto-fill based on Project selection"
            class="border p-2 rounded w-full bg-gray-200 cursor-not-allowed"
            readonly
          />
      </div>

      <!-- Resource, Practice -->
      <div class="grid grid-cols-2 gap-5 mt-5">
        <!-- Resource -->
        <div class="flex flex-col">
          <label class="text-sm font-semibold mb-1">Resource</label>
          <input
            v-model="form.resource"
            type="text"
            placeholder="Indicate Position Title or Service Required"
            class="border p-2 rounded w-full"
          />
        </div>

        <!-- Practice -->
        <div class="flex flex-col">
          <label class="text-sm font-semibold mb-1">Practice</label>
          <input
            v-model="form.practice"
            type="text"
            placeholder="Indicate JAVA, C, C++, Mobile, etc."
            class="border p-2 rounded w-full"
          />
        </div>
      </div>
        <!-- No. Res, Start Date, Duration -->
        <div class="grid grid-cols-3 gap-5 mt-5">
            <!-- No. of Resources Needed -->
            <div class="flex flex-col">
            <label class="text-sm font-semibold mb-1">No. of Resources Needed</label>
            <input
                v-model="form.no_resources_needed"
                type="number"
                placeholder="No. of Resources Needed"
                class="border p-2 rounded w-full"
            />
            </div>

            <div class="flex flex-col">
                <label class="text-sm font-semibold mb-1">Start Date</label>
                <input
                    v-model="form.start_date"
                    @input="validateStartDate"
                    type="date"
                    class="border p-2 rounded w-full"
                />
                <span v-if="startDateError" class="text-red-600 text-sm mt-1">{{ startDateError }}</span>
            </div>

            <!-- Duration -->
            <div class="flex flex-col">
            <label class="text-sm font-semibold mb-1">Duration of Project Engagement</label>
            <input
                v-model="form.duration_project_engagement"
                type="text"
                placeholder="Duration of Project Engagement"
                class="border p-2 rounded w-full"
            />
            </div>
        </div>
        <!-- Required Skills/Experience -->
        <div class="grid grid-cols-2 gap-5 mt-5">
            <div class="flex flex-col col-span-2">
            <label class="text-xs font-semibold mb-1">Required Skills/Experience</label>
            <textarea v-model="form.required_skills" rows="6" class="border p-2 rounded w-full" placeholder="Required Skills/Experience"></textarea>
            </div>
        </div>
        <!-- Preferred Skills/Experience -->
        <div class="grid grid-cols-2 gap-5 mt-5">
            <div class="flex flex-col col-span-2">
            <label class="text-xs font-semibold mb-1">Preferred Skills/Experience</label>
            <textarea v-model="form.preferred_skills" rows="6" class="border p-2 rounded w-full" placeholder="Preferred Skills/Experience"></textarea>
            </div>
        </div>

        <!-- Role/Job Description -->
        <div class="grid grid-cols-2 gap-5 mt-5">
            <div class="flex flex-col col-span-2">
            <label class="text-xs font-semibold mb-1">Role/Job Description</label>
            <textarea v-model="form.role" rows="6" class="border p-2 rounded w-full" placeholder="Role/Job Description"></textarea>
            </div>
        </div>

        <!-- Expected Salary/Billing Range-->
        <div class="grid grid-cols-2 gap-5 mt-5">
            <div class="flex flex-col col-span-2">
            <label class="text-xs font-semibold mb-1"> Expected Salary/Billing Range</label>
            <input v-model="form.remarks" rows="6" class="border p-2 rounded w-full" placeholder="Please write Billing range if Temporary resource">
            </div>
        </div>

        <!-- Designated Interviewer(s) from BU-->
        <div class="grid grid-cols-2 gap-5 mt-5">
            <div class="flex flex-col col-span-2">
            <label class="text-xs font-semibold mb-1"> Designated Interviewer(s) from BU</label>
            <textarea v-model="form.interviewers" rows="6" class="border p-2 rounded w-full" placeholder=" Designated Interviewer(s) from BU"></textarea>
            </div>
        </div>

        <!-- Remarks -->
        <div class="grid grid-cols-2 gap-5 mt-5">
            <div class="flex flex-col col-span-2">
            <label class="text-xs font-semibold mb-1">Remarks</label>
            <textarea v-model="form.remarks" rows="6" class="border p-2 rounded w-full" placeholder="Remarks"></textarea>
            </div>
        </div>

      <!-- Buttons -->
<div class="form-actions">
  <button
    class="btn btn-secondary cursor-pointer"
    @click="$inertia.get('/intermediate/resource-requisitions')"
  >Cancel</button>
  
  <button
    type="button"
    class="btn btn-primary"
    @click="submit"
    :disabled="form.processing"
  > 
    {{ form.processing ? 'Creating…' : 'Create' }} 
  </button>
</div>


    </div>
  </AppLayout>
</template>

<style scoped>
.form-actions {
  margin-top: 2rem;
  display: flex;
  justify-content: flex-end;
  align-items: center;
  gap: 1rem;
}

.form-actions button {
  padding: 0.5rem 1rem; 
  font-size: 0.85rem;
  border-radius: 5px;
  font-weight: 500;
  width: auto;
}

button[type="submit"] {
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
  background: #f3f4f6;
  color: #374151;
  border: 1px solid #d1d5db;
}

.btn-secondary:hover {
  background: #e5e7eb;
}
</style>