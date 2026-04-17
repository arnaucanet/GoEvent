<template>
  <div class="events-page container mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-14">
    <div class="mb-8">
      <h1 class="text-4xl font-bold mb-2" :class="isDarkTheme ? 'text-slate-100' : 'text-slate-900'">Eventos Disponibles
      </h1>
      <p :class="isDarkTheme ? 'text-slate-300' : 'text-gray-600'">Explora todos los eventos disponibles</p>
    </div>

    <div v-if="isLoading" class="flex items-center justify-center py-10">
      <i class="pi pi-spin pi-spinner text-3xl text-blue-500"></i>
    </div>

    <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 items-stretch">
      <div v-for="event in events" :key="event.id"
        class="h-full flex flex-col rounded-2xl border overflow-hidden transition-all duration-300 shadow-sm hover:-translate-y-0.5 hover:shadow-xl"
        :class="isDarkTheme ? 'bg-slate-800 border-slate-700' : 'bg-white border-slate-200'">
        <!-- Event Header -->
        <div class="bg-blue-600 p-4 text-white min-h-[6rem]">
          <h2 class="text-2xl font-bold mb-2 line-clamp-2 min-h-[3.5rem]">{{ event.title }}</h2>

          <div class="flex items-center gap-2 text-sm">
            <i class="pi pi-map-marker"></i>
            <span>{{ event.city || '-' }}</span>
          </div>
        </div>

        <!-- Event Body -->
        <div class="p-4 flex-1 flex flex-col">

          <!-- Description -->
          <p class="mb-3 line-clamp-2 min-h-[3.5rem]" :class="isDarkTheme ? 'text-slate-300' : 'text-slate-600'">
            {{ event.description }}
          </p>

          <!-- Category -->
          <div class="mb-3">
            <span class="inline-block px-3 py-1 rounded-full text-xs font-medium"
              :class="isDarkTheme ? 'bg-blue-900 text-blue-200' : 'bg-blue-100 text-blue-800'">
              {{ event.category?.name }}
            </span>
          </div>

          <!-- Event Details -->
          <div class="space-y-2 text-sm mb-4 min-h-[4.4rem]">
            <div class="flex items-center gap-2" :class="isDarkTheme ? 'text-slate-200' : 'text-slate-700'">
              <i class="pi pi-calendar"></i>
              <span>{{ formatDate(event.start_date) }}</span>
            </div>
            <div class="flex items-center gap-2" :class="isDarkTheme ? 'text-slate-200' : 'text-slate-700'">
              <i class="pi pi-users"></i>
              <span>Capacidad: {{ event.capacity }}</span>
            </div>
          </div>

          <div class="mt-auto pt-2 border-t" :class="isDarkTheme ? 'border-slate-700' : 'border-slate-200'">
            <div class="flex items-center justify-between">
              <div class="flex items-center gap-2">
                <div class="w-2.5 h-2.5 rounded-full"
                  :class="(event.status || 'publicado').toLowerCase() === 'publicado' ? 'bg-emerald-500' : 'bg-amber-500'">
                </div>
                <span class="text-xs font-semibold uppercase tracking-wide"
                  :class="isDarkTheme ? 'text-slate-300' : 'text-slate-600'">
                  {{ event.status || 'publicado' }}
                </span>
              </div>
            </div>
          </div>

          <div v-if="event.featured" class="mt-2">
            <i class="pi pi-star-fill text-yellow-500"></i>
            <span class="font-semibold" :class="isDarkTheme ? 'text-slate-100' : 'text-slate-800'"> Evento
              Destacado</span>
          </div>

          <!-- Button -->
          <Button label="Ver Detalles" icon="pi pi-arrow-right"
            class="w-full mt-4 !bg-blue-600 !border-blue-600 hover:!bg-blue-700 !text-white" :pt="{
              label: { class: '!text-white' },
              icon: { class: '!text-white' }
            }" @click="goToEvent(event.id)" />
        </div>
      </div>
    </div>

    <!-- Empty State -->
    <div v-if="!isLoading && events.length === 0" class="text-center py-12">
      <i class="pi pi-inbox text-5xl text-gray-400 mb-4"></i>
      <p class="text-gray-600 dark:text-gray-400 text-lg">No hay eventos disponibles en este momento</p>
    </div>
  </div>
  <AppFooter></AppFooter>
</template>

<script setup>
import { onMounted, watch } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import Button from 'primevue/button';
import useEvents from '@/composables/events';
import { useLayout } from '@/composables/layout';
import AppFooter from '../../../components/AppFooter.vue';

const router = useRouter();
const route = useRoute();
const { events, getPublicEvents, isLoading } = useEvents();
const { isDarkTheme, setDefaultMode } = useLayout();

const buildQueryParams = () => {
  const params = {};

  if (route.query.q) params.q = route.query.q;
  if (route.query.city) params.city = route.query.city;
  if (route.query.category_id) params.category_id = route.query.category_id;
  if (route.query.date_from) params.date_from = route.query.date_from;
  if (route.query.date_to) params.date_to = route.query.date_to;
  if (route.query.limit) params.limit = route.query.limit;

  return params;
};

const loadEvents = async () => {
  await getPublicEvents(buildQueryParams());
};

const formatDate = (dateStr) => {
  const date = new Date(dateStr);
  return date.toLocaleDateString('es-ES', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  });
};

const goToEvent = (id) => {
  router.push({ name: 'events.detail', params: { id } });
};

onMounted(() => {
  setDefaultMode();
  loadEvents();
});

watch(
  () => route.query,
  () => {
    loadEvents();
  },
  { deep: true }
);
</script>
