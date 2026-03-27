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

                    <div class="reveal-3 bg-white rounded-2xl p-3 sm:p-4 shadow-2xl max-w-3xl">
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
                                <Button label="Buscar" icon="pi pi-search" class="w-full" :loading="isLoadingEvents" @click="applySearch" />
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
                <Button label="Ver todos" text @click="resetFilters" />
            </div>

            <div v-if="isLoadingFeatured || isLoadingEvents" class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 sm:gap-5">
                <article
                    v-for="n in 4"
                    :key="`skeleton-${n}`"
                    class="rounded-2xl overflow-hidden border border-surface-200 dark:border-surface-700 bg-surface-0 dark:bg-surface-900 animate-pulse"
                >
                    <div class="h-44 sm:h-48 bg-surface-200 dark:bg-surface-700"></div>
                    <div class="p-4 sm:p-5 space-y-2">
                        <div class="h-3 w-1/2 bg-surface-200 dark:bg-surface-700 rounded"></div>
                        <div class="h-4 w-4/5 bg-surface-200 dark:bg-surface-700 rounded"></div>
                        <div class="h-4 w-2/3 bg-surface-200 dark:bg-surface-700 rounded"></div>
                    </div>
                </article>
            </div>

            <div v-else-if="filteredEvents.length" class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 sm:gap-5">
                <article
                    v-for="item in filteredEvents"
                    :key="item.id"
                    class="group rounded-2xl overflow-hidden border border-surface-200 dark:border-surface-700 bg-surface-0 dark:bg-surface-900 shadow-sm hover:shadow-xl transition-shadow"
                >
                    <div class="h-44 sm:h-48 relative" :class="item.cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                        <Tag :value="item.type" severity="info" class="absolute top-3 left-3" />
                        <p class="absolute bottom-3 left-3 text-white font-semibold">{{ item.city }}</p>
                    </div>
                    <div class="p-4 sm:p-5">
                        <p class="text-xs uppercase tracking-wider text-surface-500 dark:text-surface-400 mb-2">{{ item.date }}</p>
                        <h3 class="font-extrabold text-lg leading-tight mb-2">{{ item.title }}</h3>
                        <p class="text-sm text-surface-600 dark:text-surface-300 mb-4">{{ item.venue }}</p>
                        <div class="flex items-center justify-between">
                            <p class="font-bold text-blue-700 dark:text-blue-300">{{ item.priceLabel }}</p>
                            <Button label="Entradas" size="small" rounded />
                        </div>
                    </div>
                </article>
            </div>

            <div v-else class="rounded-2xl border border-surface-200 dark:border-surface-700 bg-surface-50 dark:bg-surface-900/40 p-8 text-center">
                <p class="font-semibold text-lg mb-2">No hay eventos destacados disponibles</p>
                <p class="text-surface-600 dark:text-surface-300">{{ eventsError || featuredError || 'Prueba más tarde o revisa los filtros seleccionados.' }}</p>
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
                            <Button label="Ir a mi panel" as="router-link" to="/app" />
                        </template>
                    </div>
                </div>
            </div>
        </section>
    </div>
</template>

<script setup>
import { computed, onMounted, ref } from "vue";
import { authStore } from "@/store/auth";
import usePublicEvents from "@/composables/publicEvents";

const searchText = ref("");
const selectedCity = ref(null);
const selectedCategoryId = ref(null);
const activeQuickFilter = ref("all");
const {
    visibleEvents,
    categories,
    cityOptions,
    isLoadingFeatured,
    isLoadingEvents,
    featuredError,
    eventsError,
    loadHomeInitialData,
    searchPublicEvents,
} = usePublicEvents();

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

const fallbackCovers = [
    "bg-gradient-to-br from-blue-700 to-cyan-400",
    "bg-gradient-to-br from-emerald-700 to-lime-400",
    "bg-gradient-to-br from-fuchsia-700 to-orange-400",
    "bg-gradient-to-br from-violet-700 to-sky-400",
];

const formatDate = (value) => {
    if (!value) return "Fecha por confirmar";
    return new Date(value).toLocaleDateString("es-ES", {
        weekday: "short",
        day: "2-digit",
        month: "short",
    });
};

const mapEventToCard = (item, index) => ({
    id: item.id,
    title: item.title,
    city: item.city || "Ciudad por confirmar",
    date: formatDate(item.start_date),
    venue: item.venue || item.category?.name || "Recinto por confirmar",
    priceLabel: item.price ? `Desde ${item.price} EUR` : "Entradas disponibles",
    type: item.category?.name || "Evento",
    cover: fallbackCovers[index % fallbackCovers.length],
});

const filteredEvents = computed(() => {
    return visibleEvents.value.map(mapEventToCard);
});

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
    await searchPublicEvents(buildSearchParams());
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
};

const resolveCategoryIcon = (category, indexSeed = 0) => {
    if (category?.icon) return category.icon;
    return categoryIcons[indexSeed % categoryIcons.length];
};

onMounted(() => {
    loadHomeInitialData();
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
