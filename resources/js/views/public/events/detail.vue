<template>
  <div class="event-detail-page p-4 md:p-8">
    <!-- Loading State -->
    <div v-if="isLoading" class="flex items-center justify-center py-12">
      <i class="pi pi-spin pi-spinner text-4xl text-blue-500"></i>
    </div>

    <!-- Event Detail -->
    <div v-else-if="event?.id" class="max-w-4xl mx-auto">
      <!-- Back Button -->
      <button 
        @click="goBack"
        class="mb-6 flex items-center gap-2 text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 font-medium transition-colors"
      >
        <i class="pi pi-arrow-left"></i>
        <span>Volver a eventos</span>
      </button>

      <!-- Header Section -->
      <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg p-8 mb-8">
        <h1 class="text-4xl font-bold mb-4">{{ event.title }}</h1>
        <div class="flex flex-wrap gap-4">
          <div class="flex items-center gap-2">
            <i class="pi pi-map-marker"></i>
            <span class="text-lg">{{ event.city?.name || '-' }}</span>
          </div>
          <div class="flex items-center gap-2">
            <i class="pi pi-tag"></i>
            <span class="text-lg">{{ event.category?.name }}</span>
          </div>
          <div>
            <Tag 
              :value="'Publicado'" 
              severity="success"
              class="text-sm"
            />
          </div>
        </div>
      </div>

      <!-- Main Content -->
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left Column - Event Details -->
        <div class="lg:col-span-2">
          <!-- Description -->
          <div class="bg-white dark:bg-gray-800 rounded-lg p-6 mb-6 shadow-lg">
            <h2 class="text-2xl font-bold mb-4 text-gray-900 dark:text-white">
              <i class="pi pi-book mr-2 text-blue-500"></i>Descripción
            </h2>
            <p class="text-gray-700 dark:text-gray-300 text-lg leading-relaxed">
              {{ event.description }}
            </p>
          </div>

          <!-- Event Information Grid -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Start Date -->
            <div class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-lg">
              <div class="flex items-start gap-3">
                <div class="bg-blue-100 dark:bg-blue-900 rounded-lg p-3">
                  <i class="pi pi-calendar text-xl text-blue-600 dark:text-blue-300"></i>
                </div>
                <div>
                  <h3 class="font-semibold text-gray-900 dark:text-white mb-1">Fecha de Inicio</h3>
                  <p class="text-gray-600 dark:text-gray-400">{{ formatDate(event.start_date) }}</p>
                </div>
              </div>
            </div>

            <!-- End Date -->
            <div class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-lg">
              <div class="flex items-start gap-3">
                <div class="bg-green-100 dark:bg-green-900 rounded-lg p-3">
                  <i class="pi pi-calendar text-xl text-green-600 dark:text-green-300"></i>
                </div>
                <div>
                  <h3 class="font-semibold text-gray-900 dark:text-white mb-1">Fecha de Término</h3>
                  <p class="text-gray-600 dark:text-gray-400">{{ formatDate(event.end_date) }}</p>
                </div>
              </div>
            </div>

            <!-- Capacity -->
            <div class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-lg">
              <div class="flex items-start gap-3">
                <div class="bg-purple-100 dark:bg-purple-900 rounded-lg p-3">
                  <i class="pi pi-users text-xl text-purple-600 dark:text-purple-300"></i>
                </div>
                <div>
                  <h3 class="font-semibold text-gray-900 dark:text-white mb-1">Capacidad</h3>
                  <p class="text-gray-600 dark:text-gray-400 text-lg font-semibold">{{ event.capacity }} personas</p>
                </div>
              </div>
            </div>

            <!-- Location -->
            <div class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-lg">
              <div class="flex items-start gap-3">
                <div class="bg-orange-100 dark:bg-orange-900 rounded-lg p-3">
                  <i class="pi pi-map-marker text-xl text-orange-600 dark:text-orange-300"></i>
                </div>
                <div>
                  <h3 class="font-semibold text-gray-900 dark:text-white mb-1">Ubicación</h3>
                  <p class="text-gray-600 dark:text-gray-400">{{ event.city?.name || 'No especificada' }}</p>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Right Column - Actions -->
        <div class="lg:col-span-1">
          <!-- Event Card Summary -->
          <div class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-lg sticky top-24 h-fit">
            <h3 class="text-xl font-bold mb-6 text-gray-900 dark:text-white">Resumen del Evento</h3>

            <!-- Category Badge -->
            <div class="mb-6">
              <p class="text-sm text-gray-600 dark:text-gray-400 font-semibold mb-2">CATEGORÍA</p>
              <span class="inline-block bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 px-4 py-2 rounded-full font-medium">
                {{ event.category?.name }}
              </span>
            </div>

            <!-- Event Status -->
            <div class="mb-6 pb-6 border-b border-gray-200 dark:border-gray-700">
              <p class="text-sm text-gray-600 dark:text-gray-400 font-semibold mb-2">ESTADO</p>
              <div class="flex items-center gap-2">
                <div class="w-3 h-3 bg-green-500 rounded-full animate-pulse"></div>
                <span class="text-gray-900 dark:text-white font-medium">{{event.status}}</span>
              </div>
            </div>

            <!-- Featured Badge -->
            <div v-if="event.featured" class="mb-6 pb-6 border-b border-gray-200 dark:border-gray-700">
              <div class="flex items-center gap-2 bg-yellow-50 dark:bg-yellow-900/30 rounded-lg p-3">
                <i class="pi pi-star-fill text-yellow-500 text-lg"></i>
                <span class="text-yellow-800 dark:text-yellow-200 font-semibold">Evento Destacado</span>
              </div>
            </div>

            <!-- Action Button -->
            <Button 
              label="Registrarse al Evento" 
              icon="pi pi-user-plus"
              class="w-full mb-3"
              style="background-color: #3b82f6; color: white;"
              severity="primary"
              @click="registerEvent"
            />
            <Button 
            label="🪑 Ir al mapa de asientos" 
            class="w-full mb-3"
            style="background-color: #3b82f6; color: white;"
            severity="primary"
            @click="registerEvent"
            />
            
            <!-- Secondary Button -->
            <Button 
              label="Compartir" 
              icon="pi pi-share-alt"
              class="w-full"
              severity="secondary"
              text
              @click="shareEvent"
            />

            <!-- Event Dates Info -->
            <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
              <p class="text-xs text-gray-500 dark:text-gray-400 mb-2">
                <i class="pi pi-info-circle mr-1"></i>
                Evento desde
              </p>
              <p class="text-sm text-gray-700 dark:text-gray-300 font-mono">
                {{ formatDateTime(event.start_date) }}
              </p>
              <p class="text-xs text-gray-500 dark:text-gray-400 mt-3 mb-2">até</p>
              <p class="text-sm text-gray-700 dark:text-gray-300 font-mono">
                {{ formatDateTime(event.end_date) }}
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Not Found State -->
    <div v-else class="text-center py-12">
      <i class="pi pi-inbox text-5xl text-gray-400 mb-4"></i>
      <p class="text-gray-600 dark:text-gray-400 text-lg">Evento no encontrado</p>
      <Button 
        label="Volver a eventos"
        icon="pi pi-arrow-left"
        class="mt-4"
        @click="goBack"
      />
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import Button from 'primevue/button';
import Tag from 'primevue/tag';
import useEvents from '@/composables/events';

const router = useRouter();
const route = useRoute();
const { event, isLoading, getEvent } = useEvents();

const goBack = () => {
  router.push({ name: 'events.list' });
};

const formatDate = (dateStr) => {
  const date = new Date(dateStr);
  return date.toLocaleDateString('es-ES', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
  });
};

const formatDateTime = (dateStr) => {
  const date = new Date(dateStr);
  return date.toLocaleDateString('es-ES', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  });
};

const registerEvent = () => {
  console.log('Registrando al evento:', event.value.id);
};

const shareEvent = () => {
  const url = window.location.href;
  if (navigator.share) {
    navigator.share({
      title: event.value.title,
      text: event.value.description,
      url: url
    });
  } else {
    // Fallback: copiar al portapapeles
    navigator.clipboard.writeText(url).then(() => {
      alert('Enlace copiado al portapapeles');
    });
  }
};

onMounted(() => {
  const eventId = route.params.id;
  if (eventId) {
    getEvent(eventId);
  }
});
</script>
