<script setup lang="ts">
import { ref, onMounted, watch } from 'vue'
import { router, usePage, Head } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'



const page = usePage<any>()
const loading = ref(false)
const props = defineProps<{
  requisition: any;
  errorMessages: Record<string, { errorCode: string; errorMessage: string }>;
  newProjects: { id: number; project_name: string; project_description: string }[];
  custom_location_name: string | null;
}>();
const today = new Date().toISOString().slice(0, 10)  


const form = ref({
  id: props.requisition?.id || null,

  engagement_type: props.requisition?.engagement_type || '',
  sourcing_type: props.requisition?.sourcing_type || '',
  request_type: props.requisition?.request_type || '',
  replacement_due_to: props.requisition?.replacement_due_to || '',
  person_to_replace: props.requisition?.person_to_replace || '',

  location_assignment: props.requisition?.location_assignment?.toString() || '',
  custom_location: props.requisition?.custom_location || '',

  project_id: props.requisition?.project_id
  ? Number(props.requisition.project_id)
  : '',
  business_unit: props.requisition?.business_unit || '',

  resource: props.requisition?.resource || '',
  practice: props.requisition?.practice || '',
  no_resources_needed: props.requisition?.no_resources_needed || '',

  start_date: props.requisition?.start_date
  ? props.requisition.start_date.slice(0, 10)
  : '',
  duration_project_engagement: props.requisition?.duration_project_engagement || '',

  required_skills: props.requisition?.required_skills || '',
  preferred_skills: props.requisition?.preferred_skills || '',
  role: props.requisition?.role || '',

  expected_salary_range: props.requisition?.expected_salary_range || '',
  remarks: props.requisition?.remarks || '',

  project_description: '',

  processing: false,
});

const person_to_replaceError = ref('')
const business_unitError = ref('')
const resourceError = ref('')
const practiceError = ref('')
const no_resources_neededError = ref('')
const duration_project_engagementError = ref('')
const required_skillsError = ref('')
const preferred_skillsError = ref('')
const roleError = ref('')
const expected_salary_rangeError = ref('')
const remarksError = ref('')
const start_dateError = ref('')
const custom_locationError = ref('')


const maxperson_to_replace = 80 
const maxbusiness_unit = 20 
const maxresource = 1024 
const maxpractice = 1024  
const maxno_resources_needed = 100
const maxduration_project_engagement = 20 
const maxrequired_skills = 1024
const maxpreferred_skills = 1024  
const maxrole = 1024  
const maxexpected_salary_range = 80  
const maxremarks = 1024  
const maxcustom_location = 1024  


const validateperson_to_replace = () => {
  person_to_replaceError.value = form.value.person_to_replace.length > maxperson_to_replace
    ? `This field exceeds the maximum allowed length.`
    : ''
}

const validatecustom_location = () => {
  custom_locationError.value = form.value.custom_location.length > maxcustom_location
    ? `This field exceeds the maximum allowed length.`
    : ''
}

const validatebusiness_unit = () => {
  business_unitError.value = form.value.business_unit.length > maxbusiness_unit
    ? `This field exceeds the maximum allowed length.`
    : ''
}

const validateresource = () => {
  resourceError.value = form.value.resource.length > maxresource
    ? `This field exceeds the maximum allowed length.`
    : ''
}

const validatepractice = () => {
  practiceError.value = form.value.practice.length > maxpractice
    ? `This field exceeds the maximum allowed length.`
    : ''
}

const validateno_resources_needed = () => {
  const value = Number(form.value.no_resources_needed);

  if (value <= 0) {
    form.value.no_resources_needed = ''; 
  } else {
    no_resources_neededError.value = value > maxno_resources_needed
      ? `This field exceeds the maximum allowed length.`
      : '';
  }
}

const validateduration_project_engagement = () => {
  duration_project_engagementError.value = form.value.duration_project_engagement.length > maxduration_project_engagement
    ? `This field exceeds the maximum allowed length.`
    : ''
}

const validaterequired_skills = () => {
  required_skillsError.value = form.value.required_skills.length > maxrequired_skills
    ? `This field exceeds the maximum allowed length.`
    : ''
}

const validatepreferred_skills = () => {
  preferred_skillsError.value = form.value.preferred_skills.length > maxpreferred_skills
    ? `This field exceeds the maximum allowed length.`
    : ''
}

const validaterole = () => {
  roleError.value = form.value.role.length > maxrole
    ? `This field exceeds the maximum allowed length.`
    : ''
}

const validateexpected_salary_range = () => {
  expected_salary_rangeError.value = form.value.expected_salary_range.length > maxexpected_salary_range
    ? `This field exceeds the maximum allowed length.`
    : ''
}

const validateremarks = () => {
  remarksError.value = form.value.remarks.length > maxremarks
    ? `This field exceeds the maximum allowed length.`
    : ''
}
const validateStartDate = () => {
  start_dateError.value = form.value.start_date && form.value.start_date < today
    ? 'The selected date must be in the future.'
    : ''
}



const updateProjectDescription = (projectId: string) => {
  const project = props.newProjects.find((p: any) => p.id === Number(projectId)); 
  if (project) {
    form.value.project_description = project.project_description;
  } else {
    form.value.project_description = '';
  }
};

watch(() => form.value.project_id, (newId) => {
  const project = props.newProjects.find(p => p.id === Number(newId));

  if (project) {
    form.value.project_description = project.project_description; 
  } else {
    form.value.project_description = ''; 
  }
});


const submit = () => {
  person_to_replaceError.value = ''
  custom_locationError.value = ''
  business_unitError.value = ''
  resourceError.value = ''
  practiceError.value = ''
  no_resources_neededError.value = ''
  duration_project_engagementError.value = ''
  required_skillsError.value = ''
  preferred_skillsError.value = ''
  roleError.value = ''
  expected_salary_rangeError.value = ''
  remarksError.value = ''
  start_dateError.value = ''

  if (form.value.location_assignment === '6' && !form.value.custom_location) {
    custom_locationError.value = 'Custom location is required if you select "Other".';
    form.value.processing = false;
    loading.value = false;
    return;
  }

  form.value.processing = true
  loading.value = true

  router.post(`/intermediate/resource-requisitions/${form.value.id}/update`, form.value, {
    onSuccess: () => {
    },
    onFinish: () => {
      form.value.processing = false
      loading.value = false
    }
  })
}

console.log('project_id:', form.value.project_id)
console.log('projects:', props.newProjects)

watch(() => form.value.request_type, (val) => {
  if (val !== '2') {
    form.value.replacement_due_to = '';
    form.value.person_to_replace = '';
  }
});

watch(
  () => form.value.location_assignment,
  (val) => {
    if (val !== '6') {
      form.value.custom_location = ''
    }
  },
  { immediate: true }
)
watch(
  () => form.value.location_assignment,
  (val) => {
    if (val !== '6') {
      form.value.custom_location = '';  
    }
  }
)

const tomorrow = new Date();
tomorrow.setDate(tomorrow.getDate() + 1); 
const tomorrowISOString = tomorrow.toISOString().slice(0, 10); 
</script>

<template>
  <Head title="Resource Requisition Register" />

  <AppLayout :errors="page.props.errors">

  <div class="w-3/4 mx-auto">

    <div class="mb-3">
      <h2 class="text-xl font-bold">Edit Resource Requisition</h2>
    </div>

    <!-- Form container -->
    <div class="text-xs overflow-x-auto mt-6 p-6 bg-white shadow-lg rounded-lg border">
      <!-- <p class="text-red-500 mb-10 mt-4"><b>Note:</b> Resource Requisition must already be approved by SR Manager.</p> -->
      <!-- Engagement Type, Sourcing Type, Request Type -->
      <div class="grid grid-cols-3 gap-5">
        <!-- Engagement Type -->
        <div class="flex flex-col">
          <label class="text-sm font-semibold mb-1 text-bold">Engagement Type <label class="text-red-500">*</label></label>
          <select v-model="form.engagement_type" class="border p-2 rounded w-full">
            <option disabled value="">Select Engagement Type</option>
            <option value="1">Permanent</option>
            <option value="2">Temporary (Consultant)</option>
            <option value="3">OJT</option>
          </select>
          <span v-if="page.props.errors?.engagement_type" class="text-red-600 text-xs mt-1">
            {{ page.props.errors.engagement_type }}
          </span>
        </div>

        <!-- Sourcing Type -->
        <div class="flex flex-col">
          <label class="text-sm font-semibold mb-1 text-bold">Sourcing Type <label class="text-red-500">*</label></label>
          <select v-model="form.sourcing_type" class="border p-2 rounded w-full">
            <option disabled value="">Select Sourcing Type</option>
            <option value="1">Internal</option>
            <option value="2">External</option>
            <option value="3">Either</option>
          </select>
          <span v-if="page.props.errors?.sourcing_type" class="text-red-600 text-xs mt-1">
            {{ page.props.errors.sourcing_type }}
          </span>
        </div>

        <!-- Request Type -->
        <div class="flex flex-col">
          <label class="text-sm font-semibold mb-1 text-bold">Request Type <label class="text-red-500">*</label></label>
          <select v-model="form.request_type" class="border p-2 rounded w-full">
            <option disabled value="">Select Request Type</option>
            <option value="1">New Requirement</option>
            <option value="2">Replacement</option>
          </select>
          <span v-if="page.props.errors?.request_type" class="text-red-600 text-xs mt-1">
            {{ page.props.errors.request_type }}
          </span>
        </div>
      </div>

      <!-- If Replacement, Due To, Person to Replace, Location Assignment, and Custom Field -->
      <div class="grid grid-cols-4 gap-5 mt-5 w-full">
        <!-- If Replacement, Due To -->
        <div class="flex flex-col w-full">
          <label class="text-sm mb-1">If Replacement, Due To</label>
          <select v-model="form.replacement_due_to" class="border p-2 rounded w-full" :disabled="form.request_type !== '2'" :class="{'bg-gray-200 cursor-not-allowed': form.request_type !== '2'}">
            <option disabled value="">Select Reason</option>
            <option value="1">Promotion</option>
            <option value="2">Attrition</option>
            <option value="3">Backfill</option>
            <option value="4">Transfer</option>
          </select>
        </div>

        <!-- Person to Replace -->
        <div class="flex flex-col">
          <label class="text-sm mb-1">Person to Replace</label>
          <input
            v-model="form.person_to_replace"
            @input="validateperson_to_replace"
            type="text"
            placeholder="Person to Replace"
            class="border p-2 rounded w-full"
            :disabled="form.request_type !== '2'"
            :class="{'bg-gray-200 cursor-not-allowed': form.request_type !== '2'}"
          />
          <span v-if="page.props.errors?.person_to_replace" class="text-red-600 text-xs mt-1">
            {{ page.props.errors.person_to_replace }}
          </span>
          <span v-if="person_to_replaceError" class="text-red-600 text-xs mt-1">
            {{ person_to_replaceError }}
          </span>
        </div>

        <!-- Location Assignment -->
        <div class="flex flex-col">
          <label class="text-sm font-semibold mb-1 text-bold">Location Assignment <label class="text-red-500">*</label></label>
          <select v-model="form.location_assignment" class="border p-2 rounded w-full">
            <option disabled value="">Select Location</option>
            <option value="1">Alabang</option>
            <option value="2">Makati</option>
            <option value="3">Cebu</option>
            <option value="4">Japan</option>
            <option value="5">China</option>
            <option value="6">Other</option>
          </select>
          <span v-if="page.props.errors?.location_assignment" class="text-red-600 text-xs mt-1">
            {{ page.props.errors.location_assignment }}
          </span>
        </div>

        <!-- Custom Location Input -->
        <div class="flex flex-col">
          <label class="text-sm mb-1">Custom Location</label>
          <input
            v-model="form.custom_location"
            type="text"
            @input="validatecustom_location"
            :class="{'bg-gray-200 cursor-not-allowed': form.location_assignment !== '6'}"
            class="border p-2 rounded w-full"
            placeholder="Specify location"
            :disabled="form.location_assignment !== '6'"
          />
          <span v-if="page.props.errors?.custom_location" class="text-red-600 text-xs mt-1">
            {{ page.props.errors.custom_location }}
          </span>
          <span v-if="custom_locationError" class="text-red-600 text-xs mt-1">
            {{ custom_locationError }}
          </span>
        </div>
      </div>

      <!-- Project Name, Business Unit -->
      <div class="grid grid-cols-2 gap-5 mt-5">
        <!-- Project Name (Dropdown) -->
        <div class="flex flex-col">
          <label class="text-sm font-semibold mb-1 text-bold">Project <label class="text-red-500">*</label></label>
          <select v-model="form.project_id" class="border p-2 rounded w-full" @change="updateProjectDescription(form.project_id)">
            <option disabled value="">Select Project</option>
            <option v-for="project in props.newProjects" :key="project.id" :value="project.id">
              {{ project.project_name }}
            </option>
          </select>
          <span v-if="page.props.errors?.project_id" class="text-red-600 text-xs mt-1">
            {{ page.props.errors.project_id }}
          </span>
        </div>

        <!-- Business Unit -->
        <div class="flex flex-col">
          <label class="text-sm font-bold mb-1">Business Unit<label class="text-red-500">*</label></label>
          <input
            v-model="form.business_unit"
            @input="validatebusiness_unit"
            type="text"
            placeholder="Business Unit"
            class="border p-2 rounded w-full"
          />
          <span v-if="page.props.errors?.business_unit" class="text-red-600 text-xs mt-1">
            {{ page.props.errors.business_unit }}
          </span>
          <span v-if="business_unitError" class="text-red-600 text-xs mt-1">
            {{ business_unitError }}
          </span>
        </div>
      </div>

      <div class="flex flex-col mt-5">
        <label class="text-sm mb-1">Project Description</label>
        <textarea
          v-model="form.project_description"
          placeholder="Project description will auto-fill based on Project selection"
          class="border p-2 rounded w-full bg-gray-200 cursor-not-allowed"
          readonly
        />
      </div>

      <!-- Resource, Practice -->
      <div class="grid grid-cols-2 gap-5 mt-5">
        <!-- Resource -->
        <div class="flex flex-col">
          <label class="text-sm mb-1">Resource</label>
          <input
            v-model="form.resource"
            @input="validateresource"
            type="text"
            placeholder="Indicate position title or service required."
            class="border p-2 rounded w-full"
          />
          <span v-if="resourceError" class="text-red-600 text-xs mt-1">
            {{ resourceError }}
          </span>
          <span v-if="page.props.errors?.resource" class="text-red-600 text-xs mt-1">
            {{ page.props.errors.resource }}
          </span>
        </div>
        
        <!-- Practice -->
        <div class="flex flex-col">
          <label class="text-sm mb-1">Practice</label>
          <input
            v-model="form.practice"
            @input="validatepractice"
            type="text"
            placeholder="Indicate JAVA, C, C++, Mobile, etc."
            class="border p-2 rounded w-full"
          />
          <span v-if="practiceError" class="text-red-600 text-xs mt-1">
            {{ practiceError }}
          </span>
          <span v-if="page.props.errors?.practice" class="text-red-600 text-xs mt-1">
            {{ page.props.errors.practice }}
          </span>
        </div>
      </div>

      <!-- No. of Resources Needed, Start Date, Duration -->
      <div class="grid grid-cols-3 gap-5 mt-5">
        <!-- No. of Resources Needed -->
        <div class="flex flex-col">
          <label class="text-sm mb-1">No. of Resources Needed</label>
          <input
            v-model="form.no_resources_needed"
            @input="validateno_resources_needed"
            type="number"
            placeholder="No. of Resources Needed"
            class="border p-2 rounded w-full"
            
          />
          <span v-if="no_resources_neededError" class="text-red-600 text-xs mt-1">
            {{ no_resources_neededError }}
          </span>
          <span v-if="page.props.errors?.no_resources_needed" class="text-red-600 text-xs mt-1">
            {{ page.props.errors.no_resources_needed }}
          </span>
        </div>

        <!-- Start Date -->
        <div class="flex flex-col">
          <label class="text-sm font-semibold mb-1 text-bold">Start Date <label class="text-red-500">*</label></label>
          <input
            v-model="form.start_date"
            type="date"
            :min="tomorrowISOString"
            @input="validateStartDate"
            class="border p-2 rounded w-full"
          />
          <span v-if="page.props.errors?.start_date" class="text-red-600 text-xs mt-1">
            {{ page.props.errors.start_date }}
          </span>
          <span v-if="start_dateError" class="text-red-600 text-xs mt-1">
            {{ start_dateError }}
          </span>
        </div>

        <!-- Duration -->
        <div class="flex flex-col">
          <label class="text-sm mb-1">Duration of Project Engagement</label>
          <input
            v-model="form.duration_project_engagement"
            @input="validateduration_project_engagement"
            type="text"
            placeholder="Duration of Project Engagement"
            class="border p-2 rounded w-full"
          />
          <span v-if="duration_project_engagementError" class="text-red-600 text-xs mt-1">
            {{ duration_project_engagementError }}
          </span>
          <span v-if="page.props.errors?.duration_project_engagement" class="text-red-600 text-xs mt-1">
            {{ page.props.errors.duration_project_engagement }}
          </span>
        </div>
      </div>

      <!-- Required Skills/Experience -->
      <div class="grid grid-cols-2 gap-5 mt-5">
        <div class="flex flex-col col-span-2">
          <label class="text-sm mb-1">Required Skills/Experience</label>
          <textarea v-model="form.required_skills" rows="6" @input="validaterequired_skills" class="border p-2 rounded w-full" placeholder="Required Skills/Experience"></textarea>
          <span v-if="required_skillsError" class="text-red-600 text-xs mt-1">
            {{ required_skillsError }}
          </span>
          <span v-if="page.props.errors?.required_skills" class="text-red-600 text-xs mt-1">
            {{ page.props.errors.required_skills }}
          </span>
        </div>
      </div>

      <!-- Preferred Skills/Experience -->
      <div class="grid grid-cols-2 gap-5 mt-5">
        <div class="flex flex-col col-span-2">
          <label class="text-sm mb-1">Preferred Skills/Experience</label>
          <textarea v-model="form.preferred_skills" rows="6" @input="validatepreferred_skills" class="border p-2 rounded w-full" placeholder="Preferred Skills/Experience"></textarea>
          <span v-if="preferred_skillsError" class="text-red-600 text-xs mt-1">
            {{ preferred_skillsError }}
          </span>
          <span v-if="page.props.errors?.preferred_skills" class="text-red-600 text-xs mt-1">
            {{ page.props.errors.preferred_skills }}
          </span>
        </div>
      </div>

      <!-- Role/Job Description -->
      <div class="grid grid-cols-2 gap-5 mt-5">
        <div class="flex flex-col col-span-2">
          <label class="text-sm mb-1">Role/Job Description</label>
          <textarea v-model="form.role" rows="6" @input="validaterole" class="border p-2 rounded w-full" placeholder="Role/Job Description"></textarea>
          <span v-if="roleError" class="text-red-600 text-xs mt-1">
            {{ roleError }}
          </span>
          <span v-if="page.props.errors?.role" class="text-red-600 text-xs mt-1">
            {{ page.props.errors.role }}
          </span>
        </div>
      </div>

      <!-- Expected Salary/Billing Range-->
      <div class="grid grid-cols-2 gap-5 mt-5">
        <div class="flex flex-col col-span-2">
          <label class="text-sm mb-1">Expected Salary/Billing Range</label>
          <input
            v-model="form.expected_salary_range"
            @input="validateexpected_salary_range"
            rows="6"
            class="border p-2 rounded w-full"
            placeholder="Expected Salary/Billing Range"
            :disabled="form.engagement_type !== '1' && form.engagement_type !== '2'"
            :class="{'bg-gray-200 cursor-not-allowed': form.engagement_type !== '1' && form.engagement_type !== '2'}"
          />
          <span v-if="expected_salary_rangeError" class="text-red-600 text-xs mt-1">
            {{ expected_salary_rangeError }}
          </span>
          <span v-if="page.props.errors?.expected_salary_range" class="text-red-600 text-xs mt-1">
            {{ page.props.errors.expected_salary_range }}
          </span>
        </div>
      </div>

      <!-- Remarks -->
      <div class="grid grid-cols-2 gap-5 mt-5">
        <div class="flex flex-col col-span-2">
          <label class="text-sm mb-1">Remarks</label>
          <textarea
            v-model="form.remarks"
            @input="validateremarks"
            rows="6"
            class="border p-2 rounded w-full"
            placeholder="Remarks"
          ></textarea>
          <span v-if="remarksError" class="text-red-600 text-xs mt-1">
            {{ remarksError }}
          </span>
          <span v-if="page.props.errors?.remarks" class="text-red-600 text-xs mt-1">
            {{ page.props.errors.remarks }}
          </span>
        </div>
      </div>

      <!-- Buttons -->
      <div class="form-actions">
        <button
          class="btn btn-secondary cursor-pointer"
          @click="$inertia.get('/intermediate/resource-requisitions/${form.value.id}')"
        >Cancel</button>

        <button
          type="button"
          class="btn btn-primary"
          @click="submit"
          :disabled="form.processing"
        >
          {{ form.processing ? 'Updating…' : 'Update' }}
        </button>
      </div>
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