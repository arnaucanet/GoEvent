<template>
  <div class="events-page p-4">
    <div class="mb-8">
      <h1 class="text-4xl font-bold mb-2">Eventos Disponibles</h1>
      <p :class="isDarkTheme ? 'text-gray-400' : 'text-gray-600'">Explora todos los eventos disponibles</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <div 
        v-for="event in events" 
        :key="event.id"
        class="rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition-shadow"
        :class="isDarkTheme ? 'bg-gray-800' : 'bg-white'"
      >
        <!-- Event Header -->
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 p-4 text-white">
          <h2 class="text-xl font-bold mb-2">{{ event.title }}</h2>
          <div class="flex items-center gap-2 text-sm">
            <i class="pi pi-map-marker"></i>
            <span>{{ event.city?.name || '-' }}</span>
          </div>
        </div>

        <!-- Event Body -->
        <div class="p-4">
          <!-- Description -->
          <p class="mb-3 line-clamp-2" :class="isDarkTheme ? 'text-gray-400' : 'text-gray-600'">
            {{ event.description }}
          </p>

          <!-- Category -->
          <div class="mb-3">
            <span
              class="inline-block px-3 py-1 rounded-full text-xs font-medium"
              :class="isDarkTheme ? 'bg-blue-900 text-blue-200' : 'bg-blue-100 text-blue-800'"
            >
              {{ event.category?.name }}
            </span>
          </div>

          <!-- Event Details -->
          <div class="space-y-2 text-sm mb-4">
            <div class="flex items-center gap-2" :class="isDarkTheme ? 'text-gray-300' : 'text-gray-700'">
              <i class="pi pi-calendar"></i>
              <span>{{ formatDate(event.start_date) }}</span>
            </div>
            <div class="flex items-center gap-2" :class="isDarkTheme ? 'text-gray-300' : 'text-gray-700'">
              <i class="pi pi-users"></i>
              <span>Capacidad: {{ event.capacity }}</span>
            </div>
          </div>

          <!-- Status -->
          <div class="mb-4">
            <Tag 
              :value="'Publicado'" 
              severity="success" 
            />
          </div>

          <!-- Button -->
          <Button 
            label="Ver Detalles" 
            icon="pi pi-arrow-right" 
            class="w-full" 
            style="background-color: blue;"
            @click="goToEvent(event.id)"
          />
        </div>
      </div>
    </div>

    <!-- Empty State -->
    <div v-if="events.length === 0" class="text-center py-12">
      <i class="pi pi-inbox text-5xl text-gray-400 mb-4"></i>
      <p class="text-lg" :class="isDarkTheme ? 'text-gray-400' : 'text-gray-600'">No hay eventos disponibles en este momento</p>
    </div>
  </div>
</template>

<script setup>
import { onMounted } from 'vue';
import { useRouter } from 'vue-router';
import Button from 'primevue/button';
import Tag from 'primevue/tag';
import useEvents from '@/composables/events';
import { useLayout } from '@/composables/layout';

const router = useRouter();
const { events, getPublicEvents, isLoading } = useEvents();
const { isDarkTheme, setDefaultMode } = useLayout();

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
  getPublicEvents();
});
</script>
