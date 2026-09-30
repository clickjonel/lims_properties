<script setup>
    import { ref } from 'vue';
    import MainLayout from '@/layouts/MainLayout.vue';
    import { router, useForm } from '@inertiajs/vue3';
    import { Icon } from '@iconify/vue';

    defineOptions({
        layout: MainLayout
    })

    const props = defineProps({
        property: Object,
        personnel: Array,
        categories: Array,
        measurements: Array,
        status: Array
    })

    const form = useForm({
        property_no         : props.property.property_no,
        measurement_unit    : props.property.measurement_unit,
        particulars         : props.property.particulars,
        unit_cost           : props.property.unit_cost,
        status              : props.property.status,
        remarks             : props.property.remarks,
        category             : props.property.category,
        mr_to               : props.property.mr_to,
        acquisition_date    : props.property.acquisition_date,
        specs               : props.property.specs
    })

    const showSuccess = ref(false)
    const countdown = ref(3)
    let countdownTimer = null

    const clearCountdown = () => {
        if (countdownTimer) {
            clearInterval(countdownTimer)
            countdownTimer = null
        }
    }

    const startRedirectCountdown = () => {
        clearCountdown()
        countdown.value = 5
        showSuccess.value = true

        countdownTimer = setInterval(() => {
            countdown.value--

            if (countdown.value <= 0) {
                clearCountdown()
                router.visit('/properties')
            }
        }, 1000)
    }

    const submit = () => {
        clearCountdown()
        showSuccess.value = false

        form.put(`/properties/${props.property.id}`, {
            preserveScroll: true,
            onSuccess: () => {
                startRedirectCountdown()
            },
            onError: (err) => {
                console.log(err)
            },
        })
    }

</script>


<template>

<section class="w-full flex flex-col gap-8 p-6">
        <header class="flex items-center gap-3 text-[20px] font-semibold text-[#16241F]">
            <button @click="router.visit('/properties')" type="button" class="p-1.5 border border-stone-200 cursor-pointer text-[#5E7369] hover:bg-black/[0.04] hover:text-[#16241F]">
                <Icon icon="famicons:arrow-back-circle-outline" class="text-[18px]" />
            </button>
            <span>Edit Property Details</span>
        </header>

        <div v-if="form.hasErrors" class="border border-red-800/30 bg-red-50 px-4 py-3 text-[13px] text-red-800">
            <span>Something went wrong, check form entries and try again or contact system administrator.</span>
        </div>

        <div v-if="showSuccess" class="border border-emerald-800/30 bg-emerald-50 px-4 py-3 text-[13px] text-emerald-800 flex items-center justify-between">
            <span>Property details updated successfully.</span>
            <span class="font-medium">Redirecting in {{ countdown }}...</span>
        </div>

        <form class="w-full flex flex-col gap-8" @submit.prevent="submit">
            <div class="border border-black/[0.08] p-6 flex flex-col gap-5">
                <p class="text-[13px] font-medium text-[#16241F] pb-3 border-b border-black/[0.08]">General Information</p>

                <div class="grid grid-cols-2 gap-6">
                    <div class="flex flex-col gap-1.5">
                        <label class="text-[11px] uppercase tracking-wide text-[#5E7369]">MR To</label>
                        <select v-model="form.mr_to" class="border px-3 py-2.5 text-[13px] bg-white" :class="form.errors.mr_to ? 'border-red-800' : 'border-black/[0.12]'" disabled>
                            <option value="">Select</option>
                            <option v-for="user in props.personnel" :key="user.id" :value="user.user_id">{{ user.full_name }}</option>
                        </select>
                        <span v-if="form.errors.mr_to" class="text-[11px] text-red-800">{{ form.errors.mr_to }}</span>
                    </div>
                    <div class="flex flex-col gap-1.5">
                        <label class="text-[11px] uppercase tracking-wide text-[#5E7369]">Measurement Unit</label>
                        <select v-model="form.measurement_unit" class="border px-3 py-2.5 text-[13px] bg-white" :class="form.errors.measurement_unit ? 'border-red-800' : 'border-black/[0.12]'">
                            <option value="">Select</option>
                            <option v-for="unit in props.measurements" :key="unit.id" :value="unit.name">{{ unit.name }}</option>
                        </select>
                        <span v-if="form.errors.measurement_unit" class="text-[11px] text-red-800">{{ form.errors.measurement_unit }}</span>
                    </div>
                    <div class="flex flex-col gap-1.5">
                        <label class="text-[11px] uppercase tracking-wide text-[#5E7369]">Category</label>
                        <select v-model="form.category" class="border px-3 py-2.5 text-[13px] bg-white" :class="form.errors.category ? 'border-red-800' : 'border-black/[0.12]'">
                            <option value="">Select</option>
                            <option v-for="category in props.categories" :key="category.id" :value="category.name">{{ category.name }}</option>
                        </select>
                        <span v-if="form.errors.category" class="text-[11px] text-red-800">{{ form.errors.category }}</span>
                    </div>
                    <div class="flex flex-col gap-1.5">
                        <label class="text-[11px] uppercase tracking-wide text-[#5E7369]">Status</label>
                        <select v-model="form.status" class="border px-3 py-2.5 text-[13px] bg-white" :class="form.errors.status ? 'border-red-800' : 'border-black/[0.12]'">
                            <option value="">Select</option>
                            <option value="Active">Active</option>
                            <option value="Inactive">Inactive</option>
                            <option value="For Repair">For Repair</option>
                        </select>
                        <span v-if="form.errors.status" class="text-[11px] text-red-800">{{ form.errors.status }}</span>
                    </div>
                </div>

                <div class="grid grid-cols-4 gap-6 text-stone-900">
                    <div class="flex flex-col gap-1.5">
                        <label class="text-[11px] uppercase tracking-wide text-[#5E7369]">Property No</label>
                        <input v-model="form.property_no" type="text" class="border px-3 py-2.5 text-[13px]" :class="form.errors.property_no ? 'border-red-800' : 'border-black/[0.12]'">
                        <span v-if="form.errors.property_no" class="text-[11px] text-red-800">{{ form.errors.property_no }}</span>
                    </div>
                    <div class="flex flex-col gap-1.5">
                        <label class="text-[11px] uppercase tracking-wide text-[#5E7369]">Unit Cost</label>
                        <input v-model="form.unit_cost" type="text" inputmode="decimal" class="border px-3 py-2.5 text-[13px]" :class="form.errors.unit_cost ? 'border-red-800' : 'border-black/[0.12]'">
                        <span v-if="form.errors.unit_cost" class="text-[11px] text-red-800">{{ form.errors.unit_cost }}</span>
                    </div>
                    <div class="flex flex-col gap-1.5">
                        <label class="text-[11px] uppercase tracking-wide text-[#5E7369]">Acquisition Date</label>
                        <input v-model="form.acquisition_date" type="date" class="border px-3 py-2.5 text-[13px]" :class="form.errors.acquisition_date ? 'border-red-800' : 'border-black/[0.12]'">
                        <span v-if="form.errors.acquisition_date" class="text-[11px] text-red-800">{{ form.errors.acquisition_date }}</span>
                    </div>
                    <div class="flex flex-col gap-1.5">
                        <label class="text-[11px] uppercase tracking-wide text-[#5E7369]">Remarks</label>
                        <input v-model="form.remarks" type="text" class="border px-3 py-2.5 text-[13px]" :class="form.errors.remarks ? 'border-red-800' : 'border-black/[0.12]'">
                        <span v-if="form.errors.remarks" class="text-[11px] text-red-800">{{ form.errors.remarks }}</span>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-6 text-stone-900">
                    <div class="flex flex-col gap-1.5">
                        <label class="text-[11px] uppercase tracking-wide text-[#5E7369]">Particulars</label>
                        <textarea v-model="form.particulars" rows="3" class="border px-3 py-2.5 text-[13px]" :class="form.errors.particulars ? 'border-red-800' : 'border-black/[0.12]'"></textarea>
                        <span v-if="form.errors.particulars" class="text-[11px] text-red-800">{{ form.errors.particulars }}</span>
                    </div>
                    <div class="flex flex-col gap-1.5">
                        <label class="text-[11px] uppercase tracking-wide text-[#5E7369]">Specifications</label>
                        <textarea v-model="form.specs" rows="3" class="border px-3 py-2.5 text-[13px]" :class="form.errors.specs ? 'border-red-800' : 'border-black/[0.12]'"></textarea>
                        <span v-if="form.errors.specs" class="text-[11px] text-red-800">{{ form.errors.specs }}</span>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <button
                    type="submit"
                    :disabled="form.processing"
                    class="px-5 py-2.5 bg-[#16241F] text-white text-[13px] font-medium hover:bg-[#16241F]/90 disabled:opacity-50 disabled:cursor-not-allowed"
                >
                    {{ form.processing ? 'Saving...' : 'Submit' }}
                </button>
                <button
                    type="button"
                    @click="router.visit('/properties')"
                    class="px-5 py-2.5 border border-black/[0.12] text-[#16241F] text-[13px] font-medium hover:bg-black/[0.04]"
                >
                    Back to List
                </button>
            </div>
        </form>
    </section>

</template>