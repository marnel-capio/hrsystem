<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue'
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'
import { usePage } from '@inertiajs/vue3'
import { Head } from '@inertiajs/vue3'

const page = usePage<any>()
const loading = ref(false);
const form = ref({
    action_batch: '',
    target_trainees: '',
    target_date: '',
    remarks: ''
})

const errors = ref({
    action_batch: '',
    target_trainees: '',
    target_date: '',
    remarks: ''

})

const validate = () => {
    errors.value.action_batch = ''
    errors.value.target_trainees = ''
    errors.value.target_date = ''
    errors.value.remarks = ''


    let isValid = true;

    if (!form.value.action_batch.trim()) {
        errors.value.action_batch = 'This field is required.'
        isValid = false
    } else {
        const abvalue = form.value.action_batch.trim()
        if (abvalue.length > 20) {
            errors.value.action_batch = 'Invalid input. This field must not exceed 20 characters.'
            isValid = false
        }
    }

    if (!form.value.target_trainees.toString().trim()) {
        errors.value.target_trainees = 'This field is required.'
        isValid = false
    } else {
        const targetTraineesValue = form.value.target_trainees.toString().trim()
        if (targetTraineesValue.length > 2) {
            errors.value.target_trainees = 'Invalid input. This field must not exceed 2 characters.'
            isValid = false
        }
    }

    if (!form.value.target_date.trim()) {
        errors.value.target_date = 'This field is required.'
        isValid = false
    } else {
        const selectedDate = new Date(form.value.target_date);
        const today = new Date();
        today.setHours(0, 0, 0, 0);
        if (selectedDate <= today) {
            errors.value.target_date = 'Please choose a valid date.'
            isValid = false
        }
    }

    const remarksValue = form.value.target_trainees.toString().trim()
    if (remarksValue.length > 2) {
            errors.value.remarks = 'Invalid input. This field must not exceed 1024 characters.'
            isValid = false
        }

    return isValid;
}

const formatToUppercase = () => {
    form.value.action_batch = form.value.action_batch.toUpperCase()
}

const submit = () => {
    if (!validate()) return;

    loading.value = true;

    router.post('/action/batches/store', form.value, {
        onFinish: () => {
            loading.value = false; 
        }
    });
};
</script>

<template>
    <Head title="Action Batch Register"/>

    <AppLayout>
        <div class="flex justify-between items-center mx-5 mb-3">
            <h2 class="text-xl font-bold">Create Action Batch</h2>
        </div>

        <div class="text-xs overflow-x-auto mt-6 mr-4 p-6 bg-white shadow-lg rounded-lg border ml-5">

            <div class="grid grid-cols-2 gap-4">
                <div class="flex flex-col col-span-2">
                    <label class="text-xs font-semibold mb-1">Action Batch</label>
                    <input
                        v-model="form.action_batch"
                        @input="formatToUppercase"
                        placeholder="Action batch"
                        class="border p-2 rounded w-full"
                    />
                    <span v-if="page.props.errors?.action_batch || errors.action_batch" class="text-red-600 text-xs mt-1">
                        {{ page.props.errors?.action_batch || errors.action_batch }}
                    </span>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 mt-5">
                <div class="flex flex-col col-span-2">
                    <label class="text-xs font-semibold mb-1">Target Trainees</label>
                    <input
                        v-model="form.target_trainees"
                        placeholder="Target Trainees"
                        type="number"
                        class="border p-2 rounded w-full"
                    />
                    <span v-if="page.props.errors?.target_trainees || errors.target_trainees" class="text-red-600 text-xs mt-1">
                        {{ page.props.errors?.target_trainees || errors.target_trainees }}
                    </span>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 mt-5">
                <div class="flex flex-col col-span-2">
                    <label class="text-xs font-semibold mb-1">Target Start Date</label>
                    <input
                        v-model="form.target_date"
                        type="month"
                        placeholder="Target Start Date"
                        class="border p-2 rounded w-full"
                    />
                    <span v-if="page.props.errors?.target_date || errors.target_date" class="text-red-600 text-xs mt-1">
                        {{ page.props.errors?.target_date || errors.target_date }}
                    </span>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 mt-5">
                <div class="flex flex-col col-span-2">
                    <label class="text-xs font-semibold mb-1">Remarks</label>
                    <textarea
                        v-model="form.remarks"
                        rows="6"
                        placeholder="Remarks"
                        class="border p-2 rounded w-full"
                    />
                    <span v-if="page.props.errors?.remarks" class="text-red-600 text-xs mt-1">
                        {{ page.props.errors?.remarks }}
                    </span>
                </div>
            </div>

            <div class="mt-10 w-full flex justify-end space-x-2">
                <span
                    class="px-4 text-xs cursor-pointer border py-2 rounded hover:bg-gray-200"
                    @click="$inertia.get('/action/batches')"
                >
                    Cancel
                </span>

                <span
                    :class="{'cursor-wait': loading}"
                    class="px-4 text-xs cursor-pointer py-2 bg-[#2176ff] text-white rounded hover:bg-blue-400"
                    @click="submit"
                >
                    Create
                </span>
            </div>
        </div>
    </AppLayout>
</template>
