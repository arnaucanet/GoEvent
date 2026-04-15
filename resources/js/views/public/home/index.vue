<template>
    <div class="home-page">
        <section class="relative overflow-hidden">
            <div class="hero-bg absolute inset-0"></div>
            <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-16 sm:py-20 lg:py-24 relative">
                <div class="max-w-4xl">
                    <p class="uppercase tracking-[0.2em] text-xs sm:text-sm text-white/80 mb-4">Plataforma de eventos</p>
                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black leading-tight text-white mb-5 reveal-1">
                        Descubre experiencias
                        <span class="block text-sky-300">en directo cerca de ti</span>
                    </h1>
                    <p class="text-base sm:text-lg text-white/90 max-w-2xl mb-8 reveal-2">
                        Conciertos, festivales, deporte y cultura en una sola web. Busca por ciudad, fecha o categoría y reserva en segundos.
                    </p>

                    <div
                        class="reveal-3 rounded-2xl p-3 sm:p-4 shadow-2xl max-w-3xl"
                        :class="isDarkTheme ? 'bg-gray-900' : 'bg-white'"
                    >
                        <div class="grid grid-cols-1 md:grid-cols-12 gap-2 sm:gap-3">
                            <div class="md:col-span-6">
                                <InputText
                                    v-model="searchText"
                                    type="text"
                                    placeholder="Artista, recinto o evento"
                                    class="w-full"
                                />
                            </div>
                            <div class="md:col-span-3">
                                <Select
                                    v-model="selectedCity"
                                    :options="cityOptions"
                                    optionLabel="label"
                                    optionValue="value"
                                    placeholder="Ciudad"
                                    class="w-full"
                                />
                            </div>
                            <div class="md:col-span-3">
                                <Button label="Buscar" icon="pi pi-search" class="w-full mt-auto !bg-blue-600 !border-blue-600 hover:!bg-blue-700" :loading="isLoadingEvents" @click="applySearch" />
                            </div>
                            
                        </div>
                    </div>

                    <div class="flex flex-wrap gap-2 mt-6 reveal-3">
                        <Tag
                            v-for="chip in quickFilters"
                            :key="chip.value"
                            :value="chip.label"
                            :severity="activeQuickFilter === chip.value ? 'info' : 'contrast'"
                            @click="applyQuickFilter(chip.value)"
                            class="cursor-pointer"
                        />
                    </div>
                </div>
            </div>
        </section>

        <section id="categorias" class="container mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-14">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl sm:text-3xl font-bold text-surface-900 dark:text-surface-0">Explora por categoría</h2>
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3 sm:gap-4">
                <button
                    v-for="cat in categories"
                    :key="cat.id"
                    class="rounded-xl p-4 text-left border border-surface-200 dark:border-surface-700 bg-surface-0 dark:bg-surface-900 hover:-translate-y-0.5 transition-transform"
                    @click="selectCategory(cat.id)"
                >
                    <i :class="resolveCategoryIcon(cat, cat.id)" class="text-lg mb-3 text-blue-600"></i>
                    <p class="font-semibold text-sm sm:text-base">{{ cat.name }}</p>
                </button>
            </div>
        </section>

        <section id="eventos" class="container mx-auto px-4 sm:px-6 lg:px-8 pb-14 sm:pb-16">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl sm:text-3xl font-bold text-surface-900 dark:text-surface-0">Eventos destacados</h2>
                <Button
                    label="Ver todos"
                    text
                    class="hover:!bg-blue-100"
                    :pt="{ label: { class: '!text-blue-600' } }"
                    @click="router.push('/events')"
                />
            </div>

            <div v-if="isLoadingFeatured" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <article
                    v-for="n in 3"
                    :key="`skeleton-${n}`"
                    class="rounded-lg overflow-hidden border border-surface-200 dark:border-surface-700 bg-surface-0 dark:bg-surface-900 animate-pulse"
                >
                    <div class="h-24 bg-surface-200 dark:bg-surface-700"></div>
                    <div class="p-4 sm:p-5 space-y-2">
                        <div class="h-3 w-1/2 bg-surface-200 dark:bg-surface-700 rounded"></div>
                        <div class="h-4 w-4/5 bg-surface-200 dark:bg-surface-700 rounded"></div>
                        <div class="h-4 w-2/3 bg-surface-200 dark:bg-surface-700 rounded"></div>
                    </div>
                </article>
            </div>

            <div v-else-if="featuredCards.length" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 items-stretch">
                <article
                    v-for="item in featuredCards"
                    :key="item.id"
                    class="h-full flex flex-col rounded-xl border overflow-hidden transition-shadow shadow-sm hover:shadow-md"
                    :class="isDarkTheme ? 'bg-gray-800 border-gray-700' : 'bg-white border-slate-200'"
                >
                    <div class="bg-blue-600 p-4 text-white min-h-[6rem]">
                        <h3 class="text-2xl font-bold mb-2 line-clamp-2 min-h-[3.5rem]">{{ item.title }}</h3>
                        <div class="flex items-center gap-2 text-sm">
                            <i class="pi pi-map-marker"></i>
                            <span>{{ item.city }}</span>
                        </div>
                    </div>

                    <div class="p-4 flex-1 flex flex-col">
                        <p class="mb-3 line-clamp-2 min-h-[3.5rem]" :class="isDarkTheme ? 'text-gray-300' : 'text-gray-600'">
                            {{ item.description }}
                        </p>

                        <div class="mb-3">
                            <span
                                class="inline-block px-3 py-1 rounded-full text-xs font-medium"
                                :class="isDarkTheme ? 'bg-blue-900 text-blue-200' : 'bg-blue-100 text-blue-800'"
                            >
                                {{ item.categoryName }}
                            </span>
                        </div>

                        <div class="space-y-2 text-sm mb-4 min-h-[4.4rem]" :class="isDarkTheme ? 'text-gray-200' : 'text-gray-700'">
                            <div class="flex items-center gap-2">
                                <i class="pi pi-calendar"></i>
                                <span>{{ item.startDateLabel }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <i class="pi pi-users"></i>
                                <span>Capacidad: {{ item.capacity }}</span>
                            </div>
                        </div>
                    </div>

                <div class="flex items-center gap-2">
                    <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                    <span class="text-black">{{ item.status || 'publicado' }}</span>
                </div>
                    <div>
                        <Button
                            label="Ver Detalles"
                            icon="pi pi-arrow-right"
                            class="w-full mt-auto !bg-blue-600 !border-blue-600 hover:!bg-blue-700"
                            @click="goToEvent(item.id)"
                        />
                    </div>
                </article>
            </div>

            <div v-else class="rounded-2xl border border-surface-200 dark:border-surface-700 bg-surface-50 dark:bg-surface-900/40 p-8 text-center">
                <p class="font-semibold text-lg mb-2">No hay eventos destacados disponibles</p>
                <p class="text-surface-600 dark:text-surface-300">{{ featuredError || 'Prueba más tarde.' }}</p>
            </div>
        </section>

        <section class="container mx-auto px-4 sm:px-6 lg:px-8 pb-14 sm:pb-20">
            <div class="rounded-3xl p-6 sm:p-10 cta-bg text-white">
                <div class="max-w-3xl">
                    <h3 class="text-2xl sm:text-3xl font-black mb-3">No te pierdas el próximo gran evento</h3>
                    <p class="text-white/90 mb-6">
                        Crea tu cuenta y recibe alertas personalizadas cuando salgan nuevas fechas en tu ciudad.
                    </p>
                    <div class="flex flex-wrap gap-3">
                        <template v-if="!authStore().user?.name">
                            <Button label="Crear cuenta" as="router-link" to="/register" severity="secondary" />
                            <Button label="Iniciar sesión" as="router-link" to="/login" outlined class="!text-white !border-white hover:!bg-white/10" />
                        </template>
                        <template v-else>
                            <Button label="Ir a mi panel" as="router-link" class="w-full mt-auto !bg-blue-400 !border-blue-400 hover:!bg-blue-500" to="/app" />
                        </template>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- Global Footer -->
    <AppFooter />
</template>

<script setup>
import { onMounted, ref } from "vue";
import { useRouter } from "vue-router";
import { authStore } from "@/store/auth";
import useFeaturedEvents from "@/composables/featuredEvents";
import { useLayout } from "@/composables/layout";
import usePublicEvents from "@/composables/publicEvents";
import AppFooter from "@/components/AppFooter.vue";

const router = useRouter();
const searchText = ref("");
const selectedCity = ref(null);
const selectedCategoryId = ref(null);
const activeQuickFilter = ref("all");

const {
    categories,
    cityOptions,
    isLoadingEvents,
    loadHomeInitialData,
    searchPublicEvents,
} = usePublicEvents();

const {
    featuredCards,
    isLoadingFeatured,
    featuredError,
    loadFeaturedEvents,
} = useFeaturedEvents();

const { isDarkTheme, setDefaultMode } = useLayout();

const quickFilters = [
    { label: "Todos", value: "all" },
    { label: "Hoy", value: "today" },
    { label: "Esta semana", value: "this_week" },
];

const categoryIcons = [
    "pi pi-volume-up",
    "pi pi-star",
    "pi pi-bolt",
    "pi pi-ticket",
    "pi pi-face-smile",
    "pi pi-heart",
];

const buildSearchParams = () => {
    const params = {
        limit: 12,
    };

    if (searchText.value.trim()) {
        params.q = searchText.value.trim();
    }

    if (selectedCity.value) {
        params.city = selectedCity.value;
    }

    if (selectedCategoryId.value) {
        params.category_id = selectedCategoryId.value;
    }

    const now = new Date();
    if (activeQuickFilter.value === "today") {
        params.date_from = new Date(now.setHours(0, 0, 0, 0)).toISOString();
        params.date_to = new Date(now.setHours(23, 59, 59, 999)).toISOString();
    }

    if (activeQuickFilter.value === "this_week") {
        const start = new Date();
        const end = new Date();
        end.setDate(start.getDate() + 7);
        params.date_from = start.toISOString();
        params.date_to = end.toISOString();
    }

    return params;
};

const applySearch = async () => {
    await router.push({
        name: "events.list",
        query: buildSearchParams(),
    });
};

const goToEvent = (id) => {
  router.push({ name: 'events.detail', params: { id } });
};

const selectCategory = async (categoryId) => {
    selectedCategoryId.value = selectedCategoryId.value === categoryId ? null : categoryId;
    await applySearch();
};

const applyQuickFilter = async (filterValue) => {
    activeQuickFilter.value = filterValue;
    await applySearch();
};

const resetFilters = async () => {
    searchText.value = "";
    selectedCity.value = null;
    selectedCategoryId.value = null;
    activeQuickFilter.value = "all";
    await searchPublicEvents({ limit: 12 });
    await loadFeaturedEvents(3);
};

const resolveCategoryIcon = (category, indexSeed = 0) => {
    if (category?.icon) return category.icon;
    return categoryIcons[indexSeed % categoryIcons.length];
};

onMounted(async () => {
    setDefaultMode();
    await Promise.all([
        loadHomeInitialData(),
        loadFeaturedEvents(3),
    ]);
});
</script>

<style scoped>
.hero-bg {
    background:
        radial-gradient(circle at 20% 20%, rgba(8, 145, 178, 0.35), transparent 38%),
        radial-gradient(circle at 80% 20%, rgba(59, 130, 246, 0.35), transparent 34%),
        linear-gradient(120deg, #111827 0%, #1e3a8a 45%, #0b1023 100%);
}

.cta-bg {
    background:
        radial-gradient(circle at 85% 15%, rgba(125, 211, 252, 0.25), transparent 35%),
        linear-gradient(130deg, #0f172a 0%, #1d4ed8 55%, #1e293b 100%);
}

.reveal-1,
.reveal-2,
.reveal-3 {
    opacity: 0;
    transform: translateY(18px);
    animation: fadeup 0.6s ease forwards;
}

.reveal-2 {
    animation-delay: 120ms;
}

.reveal-3 {
    animation-delay: 220ms;
}

@keyframes fadeup {
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>
