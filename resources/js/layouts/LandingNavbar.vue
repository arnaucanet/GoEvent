<template>
    <div
        class="fixed w-full z-50 border-b border-gray-200 dark:border-gray-800 transition-all duration-300"
        :class="isDarkTheme ? 'bg-gray-900' : 'bg-white'">
        <nav class="container mx-auto px-6 py-4 flex items-center justify-between">
            <!-- Logo -->
            <router-link to="/" class="flex items-center gap-2">
                <img :src="isDarkTheme ? '/images/goevent-dark.svg' : '/images/goevent-light.svg'" alt="GoEvent" class="h-10 w-auto"/>
            </router-link>

            <!-- Mobile Menu Button -->
            <button
                v-if="!isDesktop"
                @click="visibleMobileMenu = true"
                class="p-2 rounded-lg hover:bg-gray-100 transition-colors">
                <i class="pi pi-bars text-2xl"></i>
            </button>

            <!-- Desktop Menu -->
            <div v-if="isDesktop" class="flex items-center gap-6">
                <router-link
                    to="/"
                    class="text-blue-400 hover:text-gray-400 font-medium transition-colors"
                >
                    Inicio
                </router-link>

                <!-- Categories Dropdown -->
                <div class="relative" ref="categoriesDropdownRef">
                    <button
                        @click="toggleCategories"
                        class="flex items-center gap-1 text-blue-400 hover:text-gray-400 font-medium transition-colors">
                        Categorías
                        <i class="pi pi-chevron-down text-xs transition-transform duration-200" :class="showCategories ? 'rotate-180' : ''"></i>
                    </button>
                    <div
                        v-if="showCategories"
                        class="absolute top-full left-0 mt-2 w-56 rounded-xl shadow-xl border z-50 py-2 overflow-hidden"
                        :class="isDarkTheme ? 'bg-gray-900 border-gray-700' : 'bg-white border-gray-200'">
                        <div v-if="isLoadingCategories" class="px-4 py-3 text-sm text-gray-400 flex items-center gap-2">
                            <i class="pi pi-spin pi-spinner"></i>
                            Cargando...
                        </div>
                        <template v-else>
                            <button
                                v-for="cat in categories"
                                :key="cat.id"
                                @click="goToCategory(cat.id)"
                                class="w-full flex items-center gap-3 px-4 py-2.5 text-sm transition-colors text-left"
                                :class="isDarkTheme
                                    ? 'text-slate-200 hover:bg-slate-700'
                                    : 'text-slate-700 hover:bg-blue-50 hover:text-blue-700'">
                                <i :class="cat.icon || 'pi pi-tag'" class="text-blue-500 w-4"></i>
                                {{ cat.name }}
                            </button>
                            <p v-if="!categories.length" class="px-4 py-3 text-sm text-gray-400">
                                Sin categorías
                            </p>
                        </template>
                    </div>
                </div>

                <router-link
                    to="/events"
                    class="text-blue-400 hover:text-gray-400 font-medium transition-colors"
                >
                    Eventos
                </router-link>

                <!-- Actions -->
                <div class="flex items-center gap-3 pl-6 border-l border-gray-200">
                    <button
                        type="button"
                        @click="toggleDarkMode"
                        class="p-2 rounded-lg transition-colors"
                        :class="isDarkTheme ? 'hover:bg-slate-700/90 text-slate-100' : 'hover:bg-gray-100 text-slate-700'">
                        <i :class="isDarkTheme ? 'pi-moon' : 'pi-sun'" class="pi text-lg"></i>
                    </button>

                    <template v-if="!authStore().user?.name">
                        <router-link to="/login">
                            <Button
                                label="Login"
                                text
                                size="small"
                                class="w-full !text-blue-600 hover:!bg-sky-100"
                                :pt="{ label: { class: '!text-blue-600' } }"
                            />
                        </router-link>
                        <router-link to="/register">
                            <Button label="Registro" severity="primary" size="small" class="w-full mt-auto !bg-blue-600 !border-blue-600 hover:!bg-blue-700"/>
                        </router-link>
                    </template>

                    <div v-else>
                        <button
                            type="button"
                            @click="toggle"
                            class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-gray-100 transition-colors">
                            <Avatar :image="authStore().user.avatar" :label="authStore().user.name[0]" shape="circle" size="small" />
                            <span class="text-sm font-medium hidden xl:inline">{{ authStore().user?.name }}</span>
                            <i class="pi pi-chevron-down text-xs"></i>
                        </button>
                        <Menu ref="menu" :model="items" popup />
                    </div>
                </div>
            </div>
        </nav>

        <!-- Mobile Menu -->
        <div v-if="visibleMobileMenu" class="fixed inset-0 z-50 lg:hidden">
            <!-- Backdrop -->
            <div class="absolute inset-0 bg-black/50" @click="visibleMobileMenu = false"></div>

            <!-- Panel -->
            <div
                class="absolute right-0 top-0 h-full w-full sm:w-80 shadow-2xl"
                :class="isDarkTheme ? 'bg-gray-900 text-white' : 'bg-white text-gray-900'"
                @click.stop>
                <!-- Header -->
                <div class="flex items-center justify-between p-4 border-b border-gray-200 dark:border-gray-800">
                    <div class="flex items-center gap-2">
                        <img :src="isDarkTheme ? '/images/goevent-dark.svg' : '/images/goevent-light.svg'" alt="GoEvent" class="h-8 w-auto"/>
                        <span class="font-bold text-lg">Menu</span>
                    </div>
                    <button
                        @click="visibleMobileMenu = false"
                        class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                        <i class="pi pi-times text-xl"></i>
                    </button>
                </div>

                <!-- Content -->
                <div class="flex flex-col gap-4 p-4 h-[calc(100%-5rem)] overflow-y-auto">
                    <!-- Nav Links -->
                    <div class="flex flex-col gap-1">
                        <router-link
                            to="/"
                            @click="visibleMobileMenu = false"
                            class="flex items-center gap-3 p-3 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                            <i class="pi pi-home"></i>
                            <span>Inicio</span>
                        </router-link>

                        <!-- Mobile Categories Accordion -->
                        <div>
                            <button
                                @click="showMobileCategories = !showMobileCategories"
                                class="w-full flex items-center justify-between gap-3 p-3 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                                <div class="flex items-center gap-3">
                                    <i class="pi pi-th-large"></i>
                                    <span>Categorías</span>
                                </div>
                                <i class="pi pi-chevron-down text-xs transition-transform duration-200" :class="showMobileCategories ? 'rotate-180' : ''"></i>
                            </button>
                            <div v-if="showMobileCategories" class="ml-4 mt-1 flex flex-col gap-1">
                                <div v-if="isLoadingCategories" class="px-3 py-2 text-sm text-gray-400 flex items-center gap-2">
                                    <i class="pi pi-spin pi-spinner text-xs"></i>
                                    Cargando...
                                </div>
                                <button
                                    v-for="cat in categories"
                                    :key="cat.id"
                                    @click="goToCategory(cat.id)"
                                    class="flex items-center gap-3 p-2.5 rounded-lg text-sm text-left transition-colors"
                                    :class="isDarkTheme
                                        ? 'text-slate-300 hover:bg-slate-700'
                                        : 'text-slate-600 hover:bg-blue-50 hover:text-blue-700'">
                                    <i :class="cat.icon || 'pi pi-tag'" class="text-blue-500 text-xs w-4"></i>
                                    {{ cat.name }}
                                </button>
                                <p v-if="!isLoadingCategories && !categories.length" class="px-3 py-2 text-sm text-gray-400">
                                    Sin categorías
                                </p>
                            </div>
                        </div>

                        <router-link
                            to="/events"
                            @click="visibleMobileMenu = false"
                            class="flex items-center gap-3 p-3 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                            <i class="pi pi-calendar"></i>
                            <span>Eventos</span>
                        </router-link>
                    </div>

                    <div class="border-t border-gray-200 dark:border-gray-800"></div>

                    <!-- Auth -->
                    <div class="flex flex-col gap-3">
                        <template v-if="!authStore().user?.name">
                            <router-link to="/login" @click="visibleMobileMenu = false">
                                <Button label="Iniciar Sesión" outlined class="w-full" />
                            </router-link>
                            <router-link to="/register" @click="visibleMobileMenu = false">
                                <Button label="Registrarse" class="w-full" />
                            </router-link>
                        </template>
                        <template v-else>
                            <div class="p-3 rounded-lg bg-gray-50 dark:bg-gray-800">
                                <div class="font-medium">{{ authStore().user.name }}</div>
                                <div class="text-xs text-gray-500">{{ authStore().user.email }}</div>
                            </div>
                            <Button label="Ir al Dashboard" icon="pi pi-th-large" outlined @click="navigateToDashboard" />
                            <div class="border-t border-gray-200 dark:border-gray-700 pt-2">
                                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2 px-1">Entradas</p>
                                <button @click="() => { visibleMobileMenu = false; router.push('/my-events') }"
                                    class="w-full flex items-center gap-3 px-3 py-2 rounded-lg text-sm transition-colors"
                                    :class="isDarkTheme ? 'text-slate-300 hover:bg-slate-700' : 'text-slate-700 hover:bg-blue-50 hover:text-blue-700'">
                                    <i class="pi pi-ticket text-gray-400"></i>
                                    Mis eventos
                                </button>
                                <button @click="() => { visibleMobileMenu = false; router.push('/orders') }"
                                    class="w-full flex items-center gap-3 px-3 py-2 rounded-lg text-sm transition-colors"
                                    :class="isDarkTheme ? 'text-slate-300 hover:bg-slate-700' : 'text-slate-700 hover:bg-blue-50 hover:text-blue-700'">
                                    <i class="pi pi-ticket text-blue-500"></i>
                                    Próximos eventos
                                </button>
                                <button @click="() => { visibleMobileMenu = false; router.push('/orders/past-events') }"
                                    class="w-full flex items-center gap-3 px-3 py-2 rounded-lg text-sm transition-colors"
                                    :class="isDarkTheme ? 'text-slate-300 hover:bg-slate-700' : 'text-slate-700 hover:bg-blue-50 hover:text-blue-700'">
                                    <i class="pi pi-history text-gray-400"></i>
                                    Eventos pasados
                                </button>
                            </div>
                            <Button label="Cerrar Sesión" icon="pi pi-power-off" severity="danger" text @click="handleLogout" />
                        </template>
                    </div>

                    <!-- Theme Toggle -->
                    <div
                        class="mt-auto flex items-center justify-between p-3 rounded-lg"
                        :class="isDarkTheme ? 'bg-gray-800' : 'bg-gray-50'">
                        <span class="text-sm font-medium">Tema</span>
                        <button
                            @click="toggleDarkMode"
                            class="p-2 rounded-lg transition-colors"
                            :class="isDarkTheme ? 'hover:bg-gray-700' : 'hover:bg-gray-200'">
                            <i :class="isDarkTheme ? 'pi-moon' : 'pi-sun'" class="pi"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Spacer -->
    <div class="h-20"></div>
</template>

<script setup>
import { useLayout } from "@/composables/layout.js";
import useAuth from "@/composables/auth";
import usePublicEvents from "@/composables/publicEvents";
import { authStore } from "../store/auth";
import { ref, computed, onBeforeMount, onMounted, onUnmounted } from "vue";
import { useRouter } from "vue-router";

const router = useRouter();
const menu = ref();
const visibleMobileMenu = ref(false);
const isScrolled = ref(false);
const isDesktop = ref(window.innerWidth >= 992);
const showCategories = ref(false);
const showMobileCategories = ref(false);
const categoriesDropdownRef = ref(null);

const { processing, logout } = useAuth();
const { toggleDarkMode, isDarkTheme, setDefaultMode } = useLayout();
const { categories, isLoadingEvents: isLoadingCategories, loadCategories } = usePublicEvents();

const toggleCategories = () => {
    showCategories.value = !showCategories.value;
};

const goToCategory = (categoryId) => {
    showCategories.value = false;
    showMobileCategories.value = false;
    visibleMobileMenu.value = false;
    router.push({ path: '/events', query: { category_id: categoryId } });
};

const handleClickOutside = (event) => {
    if (categoriesDropdownRef.value && !categoriesDropdownRef.value.contains(event.target)) {
        showCategories.value = false;
    }
};

const items = computed(() => [
    {
        items: [
            { label: 'Perfil', icon: 'pi pi-user', command: () => router.push('/app/profile') },
            {
                label: 'Panel Admin',
                icon: 'pi pi-cog',
                route: '/admin',
                visible: authStore().user?.roles?.some(r => r.name.includes('admin')) || false
            },
            { label: 'Mi Panel', icon: 'pi pi-th-large', route: '/app' },
            { separator: true },
            { label: 'Mis eventos',  icon: 'pi pi-ticket', command: () => router.push('/my-events') },
            { label: 'Próximos eventos', icon: 'pi pi-ticket', command: () => router.push('/orders') },
            { label: 'Eventos pasados',  icon: 'pi pi-history', command: () => router.push('/orders/past-events') },
            { separator: true },
            {
                label: 'Cerrar sesión',
                icon: 'pi pi-power-off',
                class: 'text-red-500',
                command: () => { handleLogout() }
            }
        ]
    }
]);

const toggle = (event) => {
    menu.value.toggle(event);
};

const navigateToDashboard = () => {
    visibleMobileMenu.value = false;
    router.push('/app');
}

const handleLogout = () => {
    visibleMobileMenu.value = false;
    logout();
}

const handleScroll = () => {
    isScrolled.value = window.scrollY > 20;
}

const handleResize = () => {
    isDesktop.value = window.innerWidth >= 992;
}

onMounted(() => {
    window.addEventListener('scroll', handleScroll);
    window.addEventListener('resize', handleResize);
    document.addEventListener('click', handleClickOutside);
    loadCategories();
});

onUnmounted(() => {
    window.removeEventListener('scroll', handleScroll);
    window.removeEventListener('resize', handleResize);
    document.removeEventListener('click', handleClickOutside);
});

onBeforeMount(() => {
    setDefaultMode()
})
</script>
