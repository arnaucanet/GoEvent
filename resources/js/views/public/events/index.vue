<template>
  <div class="events-page container mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-14">
    <div class="mb-8">
      <h1 class="text-4xl font-bold mb-2">Eventos Disponibles</h1>
      <p class="text-gray-600 dark:text-gray-400">Explora todos los eventos disponibles</p>
    </div>

    <div v-if="isLoading" class="flex items-center justify-center py-10">
      <i class="pi pi-spin pi-spinner text-3xl text-blue-500"></i>
    </div>

    <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 items-stretch">
      <div 
        v-for="event in events" 
        :key="event.id"
        class="h-full flex flex-col rounded-xl border overflow-hidden transition-shadow shadow-sm hover:shadow-md"
        :class="isDarkTheme ? 'bg-gray-800 border-gray-700' : 'bg-white border-slate-200'"
      >
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
          <p class="mb-3 line-clamp-2 min-h-[3.5rem]" :class="isDarkTheme ? 'text-gray-300' : 'text-gray-600'">
            {{ event.description }}
            
          </p>
          

          

          <!-- Category -->
          <div class="mb-3">
            <span class="inline-block bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 px-3 py-1 rounded-full text-xs font-medium">
              {{ event.category?.name }}
            </span>
          </div>

          <!-- Event Details -->
          <div class="space-y-2 text-sm mb-4 min-h-[4.4rem]">
            <div class="flex items-center gap-2" :class="isDarkTheme ? 'text-gray-200' : 'text-gray-700'">
              <i class="pi pi-calendar"></i>
              <span>{{ formatDate(event.start_date) }}</span>
            </div>
            <div class="flex items-center gap-2" :class="isDarkTheme ? 'text-gray-200' : 'text-gray-700'">
              <i class="pi pi-users"></i>
              <span>Capacidad: {{ event.capacity }}</span>
            </div>
          </div>
          

          <div class="flex items-center gap-2">
                <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                <span class="text-slate-900 font-medium">{{ event.status || 'publicado' }}</span>
              </div>

          <div v-if="event.featured">
              <i class="pi pi-star-fill text-yellow-500"></i>
              <span class="text-black-800 font-semibold"> Evento Destacado</span>
          </div>
          

          <!-- Button -->
          <Button 
            label="Ver Detalles" 
            icon="pi pi-arrow-right" 
            class="w-full mt-auto !bg-blue-600 !border-blue-600 hover:!bg-blue-700"
            @click="goToEvent(event.id)"
          />
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
import AppFooter from '../../../components/AppFooter.vue';

const router = useRouter();
const route = useRoute();
const { events, getPublicEvents, isLoading } = useEvents();

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
