<script setup>
    import { Icon } from '@iconify/vue'
    import { router, usePage } from '@inertiajs/vue3'
    import { onMounted, provide } from 'vue'

    const page = usePage()
    const user = page.props?.auth?.user
    const activePath = page.url

    provide('user', user)

    const dashboardItems = [
        { label: 'Dashboard', icon: 'mdi:view-dashboard-outline', path: '/dashboard' },
    ]

    const dataManagementItems = [
        { label: 'Properties', icon: 'mdi:package-variant-closed', path: '/properties' },
        { label: 'Add New Property', icon: 'mdi:plus-box-outline', path: '/properties/create' },
    ]

    const statusItems = [
        { label: '5 Years and Up', icon: 'mdi:package-variant-closed', path: '/properties/five-years-up' },
        { label: 'Under Repair', icon: 'mdi:plus-box-outline', path: '/properties/under-repair' },
    ]

    const goToPage = (path) => {
        router.visit(path)
    }

    const logout = () => {
        router.delete('/logout')
    }

</script>

<template>
    <main class="flex min-h-screen h-screen w-full bg-[#F6F7F5]">
        <aside class="h-full flex flex-col w-[272px] shrink-0 bg-[#16241F] text-[#A9B8B2] print:hidden">
            <div class="p-6 border-b border-white/[0.06]">
                <p class="text-[11px] font-medium text-[#5E7369]">LIMS</p>
                <p class="mt-1 text-[15px] font-semibold text-white">Property Inventory Management</p>
            </div>

            <nav class="flex-1 flex flex-col gap-7 p-3">
                <div>
                    <p class="px-3 mb-2 text-[12px] font-medium text-[#5E7369]">Dashboard</p>
                    <button
                        v-for="item in dashboardItems"
                        :key="item.label"
                        type="button"
                        class="w-full flex items-center gap-3 px-3 py-2 text-[13.5px] hover:bg-white/5 hover:text-white"
                        :class="item.path === activePath ? 'bg-white/5 text-white' : ''"
                         @click="goToPage(item.path)"
                    >
                        <Icon :icon="item.icon" class="text-[18px]" />
                        <span>{{ item.label }}</span>
                    </button>
                </div>

                <div>
                    <p class="px-3 mb-2 text-[12px] font-medium text-[#5E7369]">Data Management</p>
                    <button
                        v-for="item in dataManagementItems"
                        :key="item.label"
                        type="button"
                        class="w-full flex items-center gap-3 px-3 py-2 text-[13.5px] hover:bg-white/5 hover:text-white"
                        :class="item.path === activePath ? 'bg-white/5 text-white' : ''"
                        @click="goToPage(item.path)"
                    >
                        <Icon :icon="item.icon" class="text-[18px]" />
                        <span>{{ item.label }}</span>
                    </button>
                </div>

                <div>
                    <p class="px-3 mb-2 text-[12px] font-medium text-[#5E7369]">Status</p>
                    <button
                        v-for="item in statusItems"
                        :key="item.label"
                        type="button"
                        class="w-full flex items-center gap-3 px-3 py-2 text-[13.5px] hover:bg-white/5 hover:text-white"
                        :class="item.path === activePath ? 'bg-white/5 text-white' : ''"
                         @click="goToPage(item.path)"
                    >
                        <Icon :icon="item.icon" class="text-[18px]" />
                        <span>{{ item.label }}</span>
                    </button>
                </div>
            </nav>

            <div class="flex items-center gap-2.5 p-4 border-t border-white/[0.06]">
                <span class="flex-1 text-[13.5px] text-white truncate">{{ user.name }}</span>
                <button @click="logout" type="button" class="text-[#5E7369] hover:text-white" title="Logout">
                    <Icon icon="mdi:logout" class="text-[18px]" />
                </button>
            </div>
        </aside>

        <section class="w-full flex-1">
            <slot></slot>
        </section>
    </main>
</template>

<style scoped>
section::-webkit-scrollbar {
    display: none;
}

section {
    scrollbar-width: none; /* Firefox */
    -ms-overflow-style: none; /* old Edge/IE */
}
</style>