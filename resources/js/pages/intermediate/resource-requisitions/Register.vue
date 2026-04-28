<script setup lang="ts">
import { ref, onMounted, nextTick, watch, computed  } from 'vue'
import { router, usePage, Head } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'
import axios from 'axios';


const page = usePage<any>()
const loading = ref(false)
const props = defineProps<{
  errorMessages: Record<string, { errorCode: string; errorMessage: string }>;
  newProjects: { id: number; project_name: string; project_description: string; }[];
  custom_location_name: string | null;
  businessUnits: { id: number; business_unit: string }[];
}>();

const today = new Date().toISOString().slice(0, 10)  

const form = ref({
  engagement_type: '',
  sourcing_type: '',
  request_type: '',
  replacement_due_to:'',
  person_to_replace: '',
  location_assignment: '',
  custom_location: '', 
  project_id: null as number | null,
  business_unit_id: '' as number | string | null,
  resource: '',
  practice: '',
  no_resources_needed: '',
  start_date: '',
  duration_project_engagement: '',
  required_skills: '',
  preferred_skills: '',
  role: '',
  expected_salary_range: '',
  remarks: '',
  project_description: '', 
  processing: false,
});

const person_to_replaceError = ref('')
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
const project_descriptionError = ref('')


const maxperson_to_replace = 80 
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
const maxproject_description = 1024


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


const validateproject_description = () => {
  project_descriptionError.value = form.value.project_description.length > maxproject_description
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
  const twoDaysAhead = new Date(today);
  twoDaysAhead.setDate(twoDaysAhead.getDate() + 2); 
  const startDate = new Date(form.value.start_date); 

  start_dateError.value = startDate && startDate < twoDaysAhead
    ? 'The selected date must be at least 2 days ahead.'
    : '';
}

watch(() => form.value.project_id, (newId) => {
  const project = props.newProjects.find(p => p.id === Number(newId));

  if (project) {
    form.value.project_description = project.project_description; 
  } else {
    form.value.project_description = ''; 
  }
});


watch(() => form.value.request_type, async (newRequestType) => {
  if (newRequestType === '1') {
    form.value.replacement_due_to = '';
    await nextTick(); 
    console.log('Dropdown should reset now.');
  }
});


watch(() => form.value.request_type, (newRequestType) => {
  if (newRequestType === '1') {  
    form.value.person_to_replace = '';  
    form.value.replacement_due_to = '';  
  }
});

watch(() => form.value.engagement_type, (newEngagementType) => {
  if (newEngagementType === '3') {  
    form.value.expected_salary_range = '';  
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


onMounted(() => {
  if (form.value.project_id) {
    const project = props.newProjects.find(
      p => p.id === Number(form.value.project_id)
    )

    if (project) {
      form.value.project_description = project.project_description
    }
  }
})


const submit = () => {
  person_to_replaceError.value = ''
  custom_locationError.value = ''
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
  project_descriptionError.value =''

  form.value.processing = true
  loading.value = true

  router.post('/intermediate/resource-requisitions', form.value, {
    onSuccess: () => {
    },
    onFinish: () => {
      form.value.processing = false
      loading.value = false
    }
  })
}

const tomorrow = new Date();
tomorrow.setDate(tomorrow.getDate() + 2); 
const tomorrowISOString = tomorrow.toISOString().slice(0, 10); 





const isProjectDropdownOpen = ref(false)
const projectSearchQuery = ref('')

const toggleProjectDropdown = () => {
  isProjectDropdownOpen.value = !isProjectDropdownOpen.value
}

const selectProject = (project: any) => {
  form.value.project_id = project.id
  isProjectDropdownOpen.value = false   
  projectSearchQuery.value = ''        
}

// label for selected project
const selectedProjectLabel = computed(() => {
  const project = projects.value.find(
    (p) => p.id === Number(form.value.project_id)
  )

  return project ? project.project_name : ''
})

// filtered list
const filteredProjects = computed(() => {
  if (!projectSearchQuery.value) return projects.value

  return projects.value.filter((p) =>
    (p?.project_name ?? '')
      .toLowerCase()
      .includes(projectSearchQuery.value.toLowerCase())
  )
})



//BUSINESS Unit
const isBusinessUnitDropdownOpen = ref(false)
const businessUnitSearchQuery = ref('')

const toggleBusinessUnitDropdown = () => {
  isBusinessUnitDropdownOpen.value = !isBusinessUnitDropdownOpen.value
}

const selectBusinessUnit = (businessUnit: any) => {
  form.value.business_unit_id = businessUnit.id
  isBusinessUnitDropdownOpen.value = false   
  businessUnitSearchQuery.value = ''        
}

// label for selected bu
const selectedBusinessUnitLabel = computed(() => {
  const businessUnit = businessUnits.value.find(
    (p) => p.id === Number(form.value.business_unit_id)
  )

  return businessUnit ? businessUnit.business_unit : ''
})

// filtered list
const filteredBusinessUnit = computed(() => {
  if (!businessUnitSearchQuery.value) return businessUnits.value

  return businessUnits.value.filter((p) =>
    (p?.business_unit ?? '')
      .toLowerCase()
      .includes(businessUnitSearchQuery.value.toLowerCase())
  )
})








//BUSINESS UNIT
const businessUnits = computed(() => props.businessUnits)





//ADD PROJECT THINGS:
const modalVisible = ref(false);
const newProject = ref({
  project_name: '',
  project_description: ''
});
const projects = ref([...props.newProjects])

const addProjectErrors = ref<any>({})
  

const validateNewProject = () => {
  addProjectErrors.value = {};
  if (!newProject.value.project_name) {
    addProjectErrors.value.project_name = "This field is required.";
  }
  if (newProject.value.project_name.length > 20) {
    addProjectErrors.value.project_name = "This field exceeds the maximum allowed length.";
  }
  if (newProject.value.project_description.length > 1024) {
    addProjectErrors.value.project_description = "This field exceeds the maximum allowed length.";
  }
  return Object.keys(addProjectErrors.value).length === 0;
};


const addNewProject = () => {
  if (!validateNewProject()) {
    return; 
  }

  router.post('/intermediate/projects', {
    project_name: newProject.value.project_name,
    project_description: newProject.value.project_description,
  }, {
    preserveScroll: true,

    onSuccess: async (page: any) => {
      const project = page.props.project;

      if (!project || !project.project_name) return;

      form.value.project_id = project.id;
      form.value.project_description = project.project_description;

      newProject.value.project_name = '';
      newProject.value.project_description = '';
      
      await fetchProjects();

      projectSearchQuery.value = '';

      nextTick(() => {
        projectSearchQuery.value = project.project_name;
      });
    },

    onFinish: () => {
      form.value.processing = false;
      loading.value = false;
      const hasBackendErrors = page.props.errors && Object.keys(page.props.errors).length > 0;
      const hasFrontendErrors = Object.keys(addProjectErrors.value).length > 0;

      if (!hasBackendErrors && !hasFrontendErrors) {
        modalVisible.value = false;
      }
    },

    onError: (errors) => {
      addProjectErrors.value = errors;
      
      modalVisible.value = true;
    },
  });
};


const fetchProjects = async () => {
  const response = await axios.get('/intermediate/projects/list')
  projects.value = response.data.projects
}
</script>

<template>
  <Head title="Resource Requisition Register" />

  <AppLayout :errors="page.props.errors">

  <div class="w-3/4 mx-auto">

    <div class="mb-3">
      <h2 class="text-xl font-bold">Create Resource Requisition</h2>
    </div>

    <!-- Form container -->
    <div class="text-xs overflow-x-auto mt-6 p-6 bg-white shadow-lg rounded-lg border">
      <p class="text-red-500 mb-10 mt-4"><b>Note:</b> Resource Requisition must already be approved by SR Manager.</p>
      
      <!-- PROJECT MODAL -->
      <div
        v-if="modalVisible"
        class="fixed inset-0 bg-black/20 flex items-center justify-center z-50 text-sm"
      >
        <div class="bg-white w-1/3 rounded-lg shadow-lg p-6 relative">

          <span class="absolute top-2 right-3 text-black cursor-pointer text-sm" @click="modalVisible = false">
            ✕
          </span>

          <h2 class="text-lg font-bold mb-4">Add Project</h2>

          <div class="mb-4">
            <label class="text-sm font-bold">Project Name <label class="text-red-500">*</label></label>
            <input
                    v-model="newProject.project_name"
                    class=" modal-input border p-2 rounded w-full"
                    placeholder="Project Name"
                />
            <span v-if="addProjectErrors.project_name" class="text-red-500 text-xs">
              {{ addProjectErrors.project_name }}
            </span>
          </div>

          <div class="mb-4">
            <label class="text-sm">Project Description</label>
            <textarea
                    v-model="newProject.project_description"
                    class="modal-textarea border p-2 rounded w-full"
                    placeholder="Project Description (optional)"
                ></textarea>
            <span v-if="addProjectErrors.project_description" class="text-red-500 text-xs">
              {{ addProjectErrors.project_description }}
            </span>
          </div>

          <div class="flex justify-end gap-2 mt-6">
            <button class="btn-secondary" @click="modalVisible = false">
              Cancel
            </button>

            <button
              class="btn btn-primary"
              @click="addNewProject"
            >Add 
            </button>
          </div>

        </div>
      </div>
      
      
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
          <select v-model="form.replacement_due_to" class="border p-2 rounded w-full" 
          :disabled="form.request_type !== '2'" 
          :class="{'bg-gray-200 cursor-not-allowed': form.request_type !== '2'}">
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
          <label class="text-sm mb-1" :class="{'font-bold': form.location_assignment === '6'}">Custom Location
            <span v-if="form.location_assignment === '6'" class="text-red-500">*</span>
          </label>
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
        <div class="form-field">
          <label class="text-sm font-bold mb-1">Project<label class="text-red-500">*</label></label>
          <div class="custom-select-wrapper"
              :class="{ 'is-open': isProjectDropdownOpen }">
              <div class="border p-2 rounded w-full flex items-center justify-between cursor-pointer bg-white"
                   @click="toggleProjectDropdown" tabindex="0">
                  <span class="custom-select-value text-sm">
                      {{ selectedProjectLabel || 'Select Project' }}
                  </span>
                  <svg class="custom-select-arrow" fill="none" stroke="currentColor"
                      viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M19 9l-7 7-7-7"></path>
                  </svg>
              </div>
              <div class="custom-select-dropdown border border-1-black border p-2 rounded-sm max-h-40 overflow-y-auto" v-show="isProjectDropdownOpen">
                  <div class="dropdown-search">
                      <input type="text" v-model="projectSearchQuery"
                          placeholder="Search/Input project..."
                          class="text-sm border border-1-black border p-2 rounded-sm w-full" @click.stop />

                      
                  </div>
                  <div class="dropdown-options-list requisition-list">
                      <div v-for="project in filteredProjects" :key="project.id"
                          class="dropdown-option-item" :class="{ 'is-selected': form.project_id === project.id }"
                          @click="selectProject(project)">
                        <div class="option-main">{{ project.project_name }}</div>
                      </div><br>
                      <div v-if="filteredProjects.length === 0"
                          class="dropdown-empty-item text-gray-500 flex justify-center items-center">
                          No project found
                      </div>
                      <div 
                        class="mt-2 mb-3 !text-white bg-[#1C7BA5] option-main flex justify-center items-center cursor-pointer rounded-md w-23 max-w-xs px-.5 py-1.5 mx-auto"
                        @click="modalVisible = true">
                        Add Project
                      </div><br>
                  </div>
              </div>
          </div>
          <span v-if="page.props.errors?.project_id" class="text-red-600 text-xs mt-1">
            {{ page.props.errors.project_id }}
          </span>
        </div>





        <!-- Business Unit -->
<div class="form-field">
  <label class="text-sm font-semibold mb-1 text-bold">
    Business Unit <label class="text-red-500">*</label>
  </label>

  <div class="custom-select-wrapper" :class="{ 'is-open': isBusinessUnitDropdownOpen }">
    
    <!-- Dropdown Button -->
    <div
      class="border p-2 rounded w-full flex items-center justify-between cursor-pointer bg-white"
      @click="toggleBusinessUnitDropdown"
      tabindex="0"
    >
      <span class="custom-select-value text-sm">
        {{ selectedBusinessUnitLabel || 'Select Business Unit' }}
      </span>

      <svg class="custom-select-arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
          d="M19 9l-7 7-7-7"></path>
      </svg>
    </div>

    <!-- Dropdown -->
    <div
      class="custom-select-dropdown border p-2 rounded-sm max-h-40 overflow-y-auto"
      v-show="isBusinessUnitDropdownOpen"
    >

      <!-- (Optional search input if you want later) -->
      <div class="dropdown-search">
        <input
          type="text"
          v-model="businessUnitSearchQuery"
          placeholder="Search business unit..."
          class="text-sm border p-2 rounded-sm w-full"
          @click.stop
        />
      </div>

      <!-- Options -->
      <div class="dropdown-options-list requisition-list">
        
        <div
          v-for="unit in filteredBusinessUnit"
          :key="unit.id"
          class="dropdown-option-item"
          :class="{ 'is-selected': form.business_unit_id === unit.id }"
          @click="selectBusinessUnit(unit)"
        >
          <div class="option-main">
            {{ unit.business_unit }}
          </div>
        </div><br>

        <!-- Empty state -->
        <div
          v-if="filteredBusinessUnit.length === 0"
          class="dropdown-empty-item text-gray-500 flex justify-center items-center"
        >
          No business unit found
        </div>

      </div>
    </div>
  </div>

  <!-- Error -->
  <span v-if="page.props.errors?.business_unit_id" class="text-red-600 text-xs mt-1">
    {{ page.props.errors.business_unit_id }}
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
          <label class="text-sm mb-1 font-bold">Resource <label class="text-red-500">*</label></label>
          <input
            v-model="form.resource"
            @input="validateresource"
            type="text"
            placeholder="Indicate position title or service required."
            class="border p-2 rounded w-full"
          />
          <span v-if="page.props.errors?.resource" class="text-red-600 text-xs mt-1">
            {{ page.props.errors.resource }}
          </span>
          <span v-if="resourceError" class="text-red-600 text-xs mt-1">
            {{ resourceError }}
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
          <label class="text-sm mb-1  font-bold">No. of Resources Needed <label class="text-red-500">*</label></label>
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
          <label class="text-sm mb-1 font-bold">Required Skills/Experience  <label class="text-red-500">*</label></label>
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
  </div>
  </AppLayout>
</template>


<style scoped>

.custom-select-arrow {
  width: 16px;
  height: 16px;
  flex-shrink: 0;
}
.option-main {
    font-weight: 500;
    color: #111827;
    margin-bottom: 2px;
}

.option-details {
    display: flex;
    gap: 0.5rem;
    font-size: 0.75rem;
    color: #6b7280;
}

.option-project {
    background: #eff6ff;
    color: #1e40af;
    padding: 0.125rem 0.375rem;
    border-radius: 0.25rem;
}

.option-location {
    background: #f0fdf4;
    color: #166534;
    padding: 0.125rem 0.375rem;
    border-radius: 0.25rem;
}

.dropdown-option-item {
    padding: 0.625rem 0.875rem;
    border-bottom: 1px solid #f3f4f6;
    cursor: pointer;
    transition: background-color 0.15s ease;
}

.dropdown-option-item:last-child {
    border-bottom: none;
}

.dropdown-option-item:hover {
    background: #e6ecf1;
}

.dropdown-option-item.is-selected {
    background: #eff6ff;
    border-left: 3px solid #3b82f6;
}

/* Rest of your existing styles remain the same */
.position-preferences-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 0.75rem;
    margin-bottom: 1.5rem;
}

.position-input {
    height: 36px !important;
    padding: 0.375rem 0.5rem !important;
    font-size: 0.875rem !important;
}

.position-label {
    font-size: 0.8125rem !important;
    line-height: 1.3 !important;
    margin-bottom: 0.25rem !important;
}

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