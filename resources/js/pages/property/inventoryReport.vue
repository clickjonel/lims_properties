<script setup>
    import { ref, computed } from 'vue';
    import MainLayout from '@/layouts/MainLayout.vue';
    import { router } from '@inertiajs/vue3';
    import { Icon } from '@iconify/vue';
    import Multiselect from 'vue-multiselect'

    // defineOptions({
    //     layout: MainLayout
    // })

    const props = defineProps({
        person: Object,
        personnel: Array
    })

    const personnel = ref(null)
    const loading = ref(false)

    const hasReport = computed(() => !!props.person)

    const totalCost = computed(() => {
        if (!hasReport.value) return 0
        return props.person.properties.reduce((sum, prop) => sum + Number(prop.unit_cost || 0), 0)
    })

    const generatedAt = computed(() => {
        return new Date().toLocaleString('en-PH', {
            dateStyle: 'medium',
            timeStyle: 'short'
        })
    })

    const formatCurrency = (value) => {
        if (value === null || value === undefined || value === '') return '—'
        return new Intl.NumberFormat('en-PH', { style: 'currency', currency: 'PHP' }).format(value)
    }

    const formatDate = (value) => {
        if (!value) return '—'
        return new Date(value).toLocaleDateString('en-PH', { dateStyle: 'medium' })
    }

    const submit = () => {
        if (!personnel.value) return

        loading.value = true

        router.get('/properties/inventory-report', { personnel: personnel.value.user_id }, {
            preserveState: true,
            preserveScroll: true,
            replace: true,
            only: ['person'],
            onFinish: () => {
                loading.value = false
            }
        })
    }

    const printReport = () => {
        window.print()
    }

</script>


<template>

<section class="w-full h-full flex flex-col gap-8 p-6">
        <header class="flex justify-between items-center gap-3 text-[20px] font-semibold text-[#16241F] print:hidden">
            <div>
                <button @click="router.visit('/properties')" type="button" class="p-1.5 border border-stone-200 cursor-pointer text-[#5E7369] hover:bg-black/[0.04] hover:text-[#16241F]">
                    <Icon icon="famicons:arrow-back-circle-outline" class="text-[18px]" />
                </button>
                <span>Inventory Report</span>
            </div>
            <button
                type="button"
                @click="printReport"
                :disabled="!hasReport"
                class="px-5 py-2.5 border border-black/[0.12] text-[#16241F] text-[13px] font-medium hover:bg-black/[0.04] disabled:opacity-50 disabled:cursor-not-allowed"
            >
                Print Report
            </button>
        </header>

        <div class="w-full border border-black/[0.08] p-6 flex flex-col gap-5 print:hidden">
            <p class="text-[13px] font-medium text-[#16241F] pb-3 border-b border-black/[0.08]">Select Personnel</p>
            <div class="w-full flex flex-col gap-2 max-w-sm">
                <label class="text-[11px] uppercase tracking-wide text-[#5E7369]">Personnel</label>
                <multiselect
                    v-model="personnel"
                    :options="props.personnel"
                    @select="submit"
                    label="full_name"
                    track-by="user_id"
                    placeholder="Select personnel"
                    class="w-full app-multiselect"
                ></multiselect>
            </div>
        </div>


        <div class="border border-black/[0.08] print:border-0">
            <!-- Report letterhead — only meaningful once a report is loaded -->
            <div v-if="hasReport" class="flex flex-col items-center gap-1 px-8 pt-8 pb-6 border-b border-black/[0.08] print:border-b-2 print:border-black">
                <p class="text-[11px] uppercase tracking-[0.2em] text-[#5E7369]">Property &amp; Supply Office</p>
                <h1 class="text-[18px] font-semibold text-[#16241F] uppercase tracking-wide">Property Inventory Report</h1>
                <p class="text-[11px] text-[#5E7369] mt-1">Generated on {{ generatedAt }}</p>
            </div>

            <div v-if="hasReport" class="grid grid-cols-2 gap-x-8 gap-y-2 px-8 py-6 border-b border-black/[0.08] print:border-b print:border-black text-[13px]">
                <div class="flex gap-2">
                    <span class="text-[#5E7369] w-32 shrink-0">Accountable Officer</span>
                    <span class="font-medium text-[#16241F]">{{ props.person.full_name }}</span>
                </div>
                <div class="flex gap-2">
                    <span class="text-[#5E7369] w-32 shrink-0">Total Items</span>
                    <span class="font-medium text-[#16241F]">{{ props.person.properties.length }}</span>
                </div>
                <div class="flex gap-2">
                    <span class="text-[#5E7369] w-32 shrink-0">Position/Office</span>
                    <span class="font-medium text-[#16241F]">{{ props.person.position ?? '—' }}</span>
                </div>
                <div class="flex gap-2">
                    <span class="text-[#5E7369] w-32 shrink-0">Total Value</span>
                    <span class="font-medium text-[#16241F]">{{ formatCurrency(totalCost) }}</span>
                </div>
            </div>

            <div v-if="!hasReport" class="px-8 py-16 text-[13px] text-[#5E7369] text-center">
                Select personnel above and click "Get Properties" to generate a report.
            </div>

            <div v-if="hasReport" class="px-8 pb-8 pt-6">
                <table class="w-full text-[13px] border-collapse">
                    <thead>
                        <tr class="border-b-2 border-black/[0.15] print:border-black">
                            <th class="text-left font-medium uppercase tracking-wide text-[11px] text-[#5E7369] py-2 pr-3 w-14">No.</th>
                            <th class="text-left font-medium uppercase tracking-wide text-[11px] text-[#5E7369] py-2 pr-3">Property No</th>
                            <th class="text-left font-medium uppercase tracking-wide text-[11px] text-[#5E7369] py-2 pr-3">Particulars</th>
                            <th class="text-right font-medium uppercase tracking-wide text-[11px] text-[#5E7369] py-2 pr-3">Unit Cost</th>
                            <th class="text-left font-medium uppercase tracking-wide text-[11px] text-[#5E7369] py-2">Acquisition Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="(prop, index) in props.person.properties"
                            :key="prop.id"
                            class="border-b border-black/[0.06] print:border-black/[0.3]"
                        >
                            <td class="py-2.5 pr-3 text-[#5E7369]">{{ index + 1 }}</td>
                            <td class="py-2.5 pr-3 text-[#16241F]">{{ prop.property_no }}</td>
                            <td class="py-2.5 pr-3 text-[#16241F]">{{ prop.particulars }}</td>
                            <td class="py-2.5 pr-3 text-right text-[#16241F] tabular-nums">{{ formatCurrency(prop.unit_cost) }}</td>
                            <td class="py-2.5 text-[#5E7369]">{{ formatDate(prop.acquisition_date) }}</td>
                        </tr>
                        <tr class="border-t-2 border-black/[0.15] print:border-black">
                            <td colspan="2" class="py-3 pr-3 text-left font-medium text-[#16241F] uppercase text-[11px] tracking-wide">Total Value</td>
                            <td colspan="4" class="py-3 pr-3 text-right font-semibold text-[#16241F] tabular-nums">{{ formatCurrency(totalCost) }}</td>
                        </tr>

                        <tr v-if="props.person.properties.length === 0">
                            <td colspan="5" class="py-8 text-center text-[#5E7369]">No properties assigned to this personnel.</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Signature block — only relevant for print/records -->
            <!-- <div v-if="hasReport" class="grid grid-cols-2 gap-8 px-8 pb-10 pt-4 text-[13px]">
                <div class="flex flex-col gap-8">
                    <div class="flex flex-col gap-1">
                        <div class="border-b border-black/[0.4] h-10"></div>
                        <span class="text-[11px] text-[#5E7369]">Prepared by / Date</span>
                    </div>
                </div>
                <div class="flex flex-col gap-8">
                    <div class="flex flex-col gap-1">
                        <div class="border-b border-black/[0.4] h-10"></div>
                        <span class="text-[11px] text-[#5E7369]">Verified/Received by / Date</span>
                    </div>
                </div>
            </div> -->
        </div>


    </section>

</template>

<style scoped>
@media print {
    .print-hide {
        display: none !important;
    }
}
</style>