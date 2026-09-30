<script setup>
    import { ref, watch } from 'vue';
    import MainLayout from '@/layouts/MainLayout.vue';
    import { router, useForm } from '@inertiajs/vue3';
    import { Icon } from '@iconify/vue';
    import { debounce } from 'lodash'
    import Multiselect from 'vue-multiselect'

    defineOptions({
        layout: MainLayout
    })

    const props = defineProps({
        properties: Array,
        personnel: Array
    })

    const properties = ref([]);
    const searchValue = ref('')
    const selectedMrTo = ref(null)

    const form = useForm({
        properties: [],
        personnel: null
    })

    // Keep form.personnel synced with whatever the multiselect holds
    watch(selectedMrTo, (value) => {
        form.personnel = value?.user_id ?? null
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
        countdown.value = 3
        showSuccess.value = true

        countdownTimer = setInterval(() => {
            countdown.value--

            if (countdown.value <= 0) {
                clearCountdown()
                router.visit('/properties')
            }
        }, 1000)
    }

    const search = debounce((value) => {
        router.get('/properties/transfer', { search: value }, {
            preserveState: true,
            preserveScroll: true,
            replace: true,
            only: ['properties'],
            onSuccess: () => {
                searchValue.value = ''
            }
        })
    }, 500)

    const selectProperty = (property, index) => {
        properties.value.push(property)
        form.properties.push(property.id)
        props.properties.splice(index, 1)
    }

    const removeProperty = (property, index) => {
        props.properties.push(property)
        properties.value.splice(index, 1)
        form.properties = form.properties.filter(id => id !== property.id)
    }

    const submit = () => {
        clearCountdown()
        showSuccess.value = false

        form.post('/properties/transfer', {
            preserveScroll: true,
            onSuccess: () => {
                properties.value = []
                selectedMrTo.value = null
                form.reset()
                startRedirectCountdown()
            }
        })
    }

</script>


<template>

<section class="w-full flex flex-col gap-8 p-6">
        <header class="flex items-center gap-3 text-[20px] font-semibold text-[#16241F]">
            <button @click="router.visit('/properties')" type="button" class="p-1.5 border border-stone-200 cursor-pointer text-[#5E7369] hover:bg-black/[0.04] hover:text-[#16241F]">
                <Icon icon="famicons:arrow-back-circle-outline" class="text-[18px]" />
            </button>
            <span>Transfer Property</span>
        </header>

        <form class="w-full flex flex-col gap-8" @submit.prevent="submit">
            <div class="border border-black/[0.08] p-6 flex flex-col gap-5">
                <p class="text-[13px] font-medium text-[#16241F] pb-3 border-b border-black/[0.08]">Add Properties</p>

                <div class="flex flex-col gap-5">
                    <div class="flex flex-col gap-2">
                        <label class="text-[11px] uppercase tracking-wide text-[#5E7369]">Search Property</label>
                        <input
                            v-model="searchValue"
                            @input="search($event.target.value)"
                            type="text"
                            placeholder="Search Property"
                            class="w-full max-w-sm border border-black/[0.12] px-3 py-2.5 text-[13px] text-[#16241F]"
                        >
                    </div>

                    <div class="w-full border border-black/[0.08] rounded-lg overflow-hidden bg-white">
                        <div class="grid grid-cols-12 bg-[#F6F7F5] border-b border-black/[0.08]">
                            <span class="col-span-4 px-3 py-2.5 text-[11px] font-medium uppercase tracking-wide text-[#5E7369]">Particulars</span>
                            <span class="col-span-3 px-3 py-2.5 text-[11px] font-medium uppercase tracking-wide text-[#5E7369]">Property No</span>
                            <span class="col-span-3 px-3 py-2.5 text-[11px] font-medium uppercase tracking-wide text-[#5E7369]">MR To</span>
                            <span class="col-span-2 px-3 py-2.5 text-[11px] font-medium uppercase tracking-wide text-[#5E7369] text-right">Action</span>
                        </div>

                        <div
                            v-for="(prop, index) in props.properties"
                            :key="prop.id"
                            class="grid grid-cols-12 border-b border-black/[0.06] last:border-b-0 hover:bg-[#F6F7F5]/60 transition-colors items-center"
                        >
                            <span class="col-span-4 px-3 py-3 text-[13px] text-[#16241F]">{{ prop.particulars }}</span>
                            <span class="col-span-3 px-3 py-3 text-[13px] text-[#5E7369]">{{ prop.property_no }}</span>
                            <span class="col-span-3 px-3 py-3 text-[13px] text-[#5E7369]">{{ prop.personnel?.full_name ?? '—' }}</span>
                            <span class="col-span-2 px-3 py-3 flex justify-end">
                                <button
                                    type="button"
                                    @click="selectProperty(prop, index)"
                                    class="p-1.5 border border-black/[0.08] rounded-md hover:bg-black/[0.04] hover:text-[#5E7369] disabled:opacity-40 disabled:cursor-not-allowed disabled:hover:bg-transparent"
                                >
                                    <Icon icon="ant-design:plus-square-outlined" class="text-[18px]" />
                                </button>
                            </span>
                        </div>

                        <div v-if="props.properties.length === 0" class="px-3 py-8 text-[13px] text-[#5E7369] text-center">
                            No properties found.
                        </div>
                    </div>
                </div>
            </div>

            <div class="border border-black/[0.08] p-6 flex flex-col gap-5">
                <p class="text-[13px] font-medium text-[#16241F] pb-3 border-b border-black/[0.08] flex items-center justify-between">
                    <span>Selected Properties</span>
                    <span class="text-[11px] font-normal text-[#5E7369] normal-case">{{ properties.length }} selected</span>
                </p>

                <div v-if="properties.length" class="w-full border border-black/[0.08] rounded-lg overflow-hidden bg-white">
                    <div class="grid grid-cols-12 bg-[#F6F7F5] border-b border-black/[0.08]">
                        <span class="col-span-4 px-3 py-2.5 text-[11px] font-medium uppercase tracking-wide text-[#5E7369]">Particulars</span>
                        <span class="col-span-3 px-3 py-2.5 text-[11px] font-medium uppercase tracking-wide text-[#5E7369]">Property No</span>
                        <span class="col-span-3 px-3 py-2.5 text-[11px] font-medium uppercase tracking-wide text-[#5E7369]">Current MR To</span>
                        <span class="col-span-2 px-3 py-2.5 text-[11px] font-medium uppercase tracking-wide text-[#5E7369] text-right">Action</span>
                    </div>

                    <div
                        v-for="(prop, index) in properties"
                        :key="prop.id"
                        class="grid grid-cols-12 border-b border-black/[0.06] last:border-b-0 hover:bg-[#F6F7F5]/60 transition-colors items-center"
                    >
                        <span class="col-span-4 px-3 py-3 text-[13px] text-[#16241F]">{{ prop.particulars }}</span>
                        <span class="col-span-3 px-3 py-3 text-[13px] text-[#5E7369]">{{ prop.property_no }}</span>
                        <span class="col-span-3 px-3 py-3 text-[13px] text-[#5E7369]">{{ prop.personnel?.full_name ?? '—' }}</span>
                        <span class="col-span-2 px-3 py-3 flex justify-end">
                            <button
                                type="button"
                                @click="removeProperty(prop, index)"
                                class="p-1.5 border border-black/[0.08] rounded-md hover:bg-black/[0.04] hover:text-red-800 disabled:opacity-40 disabled:cursor-not-allowed disabled:hover:bg-transparent"
                            >
                                <Icon icon="circum:circle-remove" class="text-[18px]" />
                            </button>
                        </span>
                    </div>
                </div>

                <div v-else class="px-3 py-6 text-[13px] text-[#5E7369] text-center border border-dashed border-black/[0.12] rounded-lg">
                    No properties selected yet. Use the search above to add some.
                </div>

                <div class="flex flex-col gap-2 w-full">
                    <label class="text-[11px] uppercase tracking-wide text-[#5E7369]">Transfer To</label>
                    <multiselect
                        v-model="selectedMrTo"
                        :options="props.personnel"
                        label="full_name"
                        track-by="user_id"
                        placeholder="Select personnel"
                        class="app-multiselect"
                    ></multiselect>
                    <span v-if="form.errors.personnel" class="text-[11px] text-red-800">{{ form.errors.personnel }}</span>
                    <span v-if="form.errors.properties" class="text-[11px] text-red-800">{{ form.errors.properties }}</span>
                </div>
            </div>

            <div v-if="form.hasErrors" class="border border-red-800/30 bg-red-50 px-4 py-3 text-[13px] text-red-800">
                <span>Something went wrong, check form entries and try again or contact system administrator.</span>
            </div>

            <div v-if="showSuccess" class="border border-emerald-800/30 bg-emerald-50 px-4 py-3 text-[13px] text-emerald-800 flex items-center justify-between">
                <span>Properties transferred successfully.</span>
                <span class="font-medium">Redirecting in {{ countdown }}...</span>
            </div>

            <div class="flex items-center gap-3">
                <button
                    type="submit"
                    :disabled="form.processing || !form.personnel || form.properties.length === 0"
                    class="px-5 py-2.5 bg-[#16241F] text-white text-[13px] font-medium hover:bg-[#16241F]/90 disabled:opacity-50 disabled:cursor-not-allowed"
                >
                    {{ form.processing ? 'Saving...' : `Transfer (${form.properties.length})` }}
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