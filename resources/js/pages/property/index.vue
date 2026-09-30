<script setup>
    import { ref, onBeforeUnmount, inject } from 'vue'
    import { debounce } from 'lodash'
    import { Icon } from '@iconify/vue'
    import MainLayout from '@/layouts/MainLayout.vue'
    import { router } from '@inertiajs/vue3'

    defineOptions({
        layout: MainLayout
    })

    const props = defineProps({
        list: Object,
        filters: Object
    })

    const user = inject('user')
    const search = ref(props.filters?.search ?? '')

    const performSearch = debounce((value) => {
        router.get('/properties', { search: value }, {
            preserveState: true,
            preserveScroll: true,
            replace: true
        })
    }, 350)

    onBeforeUnmount(() => {
        performSearch.cancel()
    })

    const goToPage = (path) => {
        if (!path) return
        router.visit(path, {
            preserveState: true,
            preserveScroll: true
        })
    }

</script>

<template>
    <section class="w-full flex flex-col gap-6 p-6">
        <header class="w-full flex justify-between items-center text-[20px] font-semibold text-[#16241F]">
            <span class="underline">Properties</span>
            <button @click="router.visit('/properties/create')" class="px-5 py-2.5 bg-[#16241F] text-white text-[13px] font-medium hover:bg-[#16241F]/90">
                Add New Property
            </button>
        </header>

        <div class="w-full flex justify-between items-center gap-2">
            <input
                v-model="search"
                type="text"
                placeholder="Search Property"
                class="w-full max-w-sm border border-black/[0.12] px-3 py-2 text-[13px] text-[#16241F]"
                @input="performSearch($event.target.value)"
            >
            <div class="flex justify-center items-center gap-2">
                <button
                    type="button"
                    @click="goToPage(props.list.prev_page_url)"
                    class="p-2 border border-black/[0.08] hover:bg-black/[0.04] hover:text-[#5E7369] disabled:opacity-40 disabled:cursor-not-allowed disabled:hover:bg-transparent"
                    title="Create Stickers"
                >
                    <Icon icon="at-icons:sticker" class="text-[18px]" />
                </button>
                <button
                    type="button"
                    @click="goToPage('/properties/inventory-report')"
                    class="p-2 border border-black/[0.08] hover:bg-black/[0.04] hover:text-[#5E7369] disabled:opacity-40 disabled:cursor-not-allowed disabled:hover:bg-transparent"
                    title="Create Inventory Report"
                >
                    <Icon icon="basil:document-solid" class="text-[18px]" />
                </button>
                <button
                    type="button"
                    @click="goToPage('/properties/transfer')"
                    class="p-2 border border-black/[0.08] hover:bg-black/[0.04] hover:text-[#5E7369] disabled:opacity-40 disabled:cursor-not-allowed disabled:hover:bg-transparent"
                    title="Transfer Properties"
                >
                    <Icon icon="mingcute:transfer-fill" class="text-[18px]" />
                </button>
            </div>
        </div>

        <div class="w-full border border-black/[0.08] rounded-lg overflow-hidden bg-white">
            <div class="grid grid-cols-12 bg-[#F6F7F5] border-b border-black/[0.08] bg-green-100 justify-start items-start text-stone-900">
                <span class="col-span-4 px-3 py-2.5 text-[11px] font-medium uppercase tracking-wide">Particulars</span>
                <span class="col-span-2 px-3 py-2.5 text-[11px] font-medium uppercase tracking-wide">MR To</span>
                <span class="col-span-1 px-3 py-2.5 text-[11px] font-medium uppercase tracking-wide">Unit Cost</span>
                <span class="col-span-1 px-3 py-2.5 text-[11px] font-medium uppercase tracking-wide">Status</span>
                <span class="col-span-2 px-3 py-2.5 text-[11px] font-medium uppercase tracking-wide">Category</span>
                <span class="col-span-1 px-3 py-2.5 text-[11px] font-medium uppercase tracking-wide">Acquisition Date</span>
                <span class="col-span-1 px-3 py-2.5 text-[11px] font-medium uppercase tracking-wide text-right">Actions</span>
            </div>

            <div
                v-for="prop in props.list.data"
                :key="prop.id"
                class="grid grid-cols-12 border-b border-stone-300 last:border-b-0 hover:bg-[#F6F7F5]/60 transition-colors justify-start items-start"
            >
                <span class="col-span-4 px-3 py-3 text-[13px] font-medium text-[#5E7369]">
                    {{ prop.particulars }}
                </span>

                <span class="col-span-2 px-3 py-3 text-[13px] font-medium text-[#5E7369]">
                    {{ prop.personnel?.full_name }}
                </span>

                <span class="col-span-1 px-3 py-3 text-[13px] font-medium text-[#5E7369]">
                    {{ prop.unit_cost }}
                </span>

                <span class="col-span-1 px-3 py-3 text-[13px] font-medium text-[#5E7369]">
                    {{ prop.status }}
                </span>

                <span class="col-span-2 px-3 py-3 text-[13px] font-medium text-[#5E7369]">
                    {{ prop.category }}
                </span>

                <span class="col-span-1 px-3 py-3 text-[13px] font-medium text-[#5E7369]">
                    {{ prop.acquisition_date }}
                </span>

                <span class="col-span-1 px-3 py-3 flex justify-end items-start gap-1">
                    <button @click="goToPage(`/properties/${prop.id}/edit`)" type="button" title="Edit" class="p-1.5 rounded-md text-[#5E7369] hover:bg-black/[0.05] hover:text-[#5E7369]">
                        <Icon icon="bxs:edit" class="text-[16px]" />
                    </button>
                </span>
            </div>

            <div v-if="props.list.data.length === 0" class="px-3 py-8 text-[13px] text-[#5E7369] text-center">
                No propentory records found.
            </div>
        </div>

        <div class="w-full flex items-center justify-between text-[13px] text-[#5E7369]">
            <span>Showing {{ props.list.data.length }} rows per page</span>
            <div class="flex items-center gap-1">
                <button
                    type="button"
                    :disabled="!props.list.prev_page_url"
                    @click="goToPage(props.list.prev_page_url)"
                    class="p-1.5 border border-black/[0.08] rounded-md hover:bg-black/[0.04] hover:text-[#5E7369] disabled:opacity-40 disabled:cursor-not-allowed disabled:hover:bg-transparent"
                >
                    <Icon icon="mdi:chevron-left" class="text-[18px]" />
                </button>
                <button
                    type="button"
                    :disabled="!props.list.next_page_url"
                    @click="goToPage(props.list.next_page_url)"
                    class="p-1.5 border border-black/[0.08] rounded-md hover:bg-black/[0.04] hover:text-[#5E7369] disabled:opacity-40 disabled:cursor-not-allowed disabled:hover:bg-transparent"
                >
                    <Icon icon="mdi:chevron-right" class="text-[18px]" />
                </button>
            </div>
        </div>
    </section>
</template>