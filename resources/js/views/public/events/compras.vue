<template>
  <div class="event-tickets-page container mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-14">
    <!-- Loading State -->
    <div v-if="isLoading" class="flex items-center justify-center py-12">
      <i class="pi pi-spin pi-spinner text-4xl text-blue-500"></i>
    </div>

    <!-- Event Tickets -->
    <div v-else-if="event?.id" class="max-w-5xl mx-auto">
      <!-- Back Button -->
      <button 
        @click="goBack"
        class="mb-6 flex items-center gap-2 text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 font-medium transition-colors"
      >
        <i class="pi pi-arrow-left"></i>
        <span>Volver</span>
      </button>

      <!-- Header Section -->
      <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg p-8 mb-8">
        <h1 class="text-3xl md:text-4xl font-bold mb-2">
            {{ event.title }}</h1>
        <p class="text-blue-100 text-lg">Comprar Entradas</p>
      </div>

      <!-- Image Placeholder - Full Width & Taller -->
      <div class="bg-slate-200 dark:bg-slate-700 rounded-lg p-16 mb-8 shadow-lg border border-slate-300 dark:border-slate-600 flex items-center justify-center min-h-[450px]">
        <div class="text-center">
          <i class="pi pi-image text-6xl text-slate-400 dark:text-slate-500 mb-4"></i>
          <p class="text-slate-500 dark:text-slate-400 font-medium text-lg">Imagen del Evento</p>
        </div>
      </div>

      <!-- Event Description -->
      <div class="bg-white dark:bg-gray-800 rounded-lg p-6 mb-8 shadow-lg border border-slate-200 dark:border-gray-700">
        <h2 class="text-2xl font-bold mb-4 text-gray-900 dark:text-white flex items-center gap-2">
          <i class="pi pi-info-circle text-blue-500"></i>
          Descripción del Evento
        </h2>
        <p class="text-base leading-7 text-slate-600 dark:text-slate-400">
          {{ event.description || 'Sin descripción disponible.' }}
        </p>
      </div>

      <!-- Event Information Grid -->
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <!-- Start Date -->
        <div class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-lg border border-slate-200 dark:border-gray-700">
          <div class="flex items-start gap-3">
            <div class="bg-blue-100 dark:bg-blue-900 rounded-lg p-3">
              <i class="pi pi-calendar text-xl text-blue-600 dark:text-blue-300"></i>
            </div>
            <div>
              <h3 class="font-semibold text-gray-900 dark:text-white mb-1">Fecha de Inicio</h3>
              <p class="text-slate-600 dark:text-slate-400">{{ formatDate(event.start_date) }}</p>
            </div>
          </div>
        </div>

        <!-- Time -->
        <div class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-lg border border-slate-200 dark:border-gray-700">
          <div class="flex items-start gap-3">
            <div class="bg-blue-100 dark:bg-blue-900 rounded-lg p-3">
              <i class="pi pi-clock text-xl text-blue-600 dark:text-blue-300"></i>
            </div>
            <div>
              <h3 class="font-semibold text-gray-900 dark:text-white mb-1">Hora</h3>
              <p class="text-slate-600 dark:text-slate-400">{{ formatTime(event.start_date) }}</p>
            </div>
          </div>
        </div>

        <!-- Location -->
        <div class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-lg border border-slate-200 dark:border-gray-700">
          <div class="flex items-start gap-3">
            <div class="bg-orange-100 dark:bg-orange-900 rounded-lg p-3">
              <i class="pi pi-map-marker text-xl text-orange-600 dark:text-orange-300"></i>
            </div>
            <div>
              <h3 class="font-semibold text-gray-900 dark:text-white mb-1">Ubicación</h3>
              <p class="text-slate-600 dark:text-slate-400">{{ event.city?.name || 'Por especificar' }}</p>
            </div>
          </div>
        </div>

        <!-- Capacity -->
        <div class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-lg border border-slate-200 dark:border-gray-700">
          <div class="flex items-start gap-3">
            <div class="bg-purple-100 dark:bg-purple-900 rounded-lg p-3">
              <i class="pi pi-users text-xl text-purple-600 dark:text-purple-300"></i>
            </div>
            <div>
              <h3 class="font-semibold text-gray-900 dark:text-white mb-1">Capacidad</h3>
              <p class="text-slate-600 dark:text-slate-400">{{ event.capacity || 0 }} personas</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Tickets Section Header -->
      <div class="mb-8">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
          <i class="pi pi-ticket text-blue-500"></i>
          Tipos de Entradas
        </h2>
        <p class="text-slate-600 dark:text-slate-400 mt-2">Selecciona el tipo de entrada que deseas comprar</p>
      </div>

      <!-- Tickets - Vertical Blocks (Full Width) -->
      <div class="space-y-4 mb-8">
        <!-- Pista Ticket -->
        <div class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-lg border-2 border-slate-200 dark:border-gray-700 hover:border-blue-500 dark:hover:border-blue-400 transition-colors cursor-pointer"
          @click="selectTicket('pista')"
          :class="{ 'border-blue-500 dark:border-blue-400 bg-blue-50 dark:bg-blue-900/20': selectedTicket === 'pista' }">
          <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
              <div class="flex-shrink-0">
                <i class="pi pi-check-circle text-3xl" :class="selectedTicket === 'pista' ? 'text-blue-600 dark:text-blue-400' : 'text-slate-300 dark:text-slate-600'"></i>
              </div>
              <div>
                <h3 class="text-xl font-bold text-gray-900 dark:text-white">Pista</h3>
                <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">Acceso a la zona de pista - Vista directa del escenario</p>
              </div>
            </div>
            <div class="text-right flex-shrink-0">
              <p class="text-3xl font-bold text-blue-600 dark:text-blue-400">99€</p>
            </div>
          </div>
        </div>

        <!-- Gradería Ticket -->
        <div class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-lg border-2 border-slate-200 dark:border-gray-700 hover:border-blue-500 dark:hover:border-blue-400 transition-colors cursor-pointer"
          @click="selectTicket('graderia')"
          :class="{ 'border-blue-500 dark:border-blue-400 bg-blue-50 dark:bg-blue-900/20': selectedTicket === 'graderia' }">
          <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
              <div class="flex-shrink-0">
                <i class="pi pi-check-circle text-3xl" :class="selectedTicket === 'graderia' ? 'text-blue-600 dark:text-blue-400' : 'text-slate-300 dark:text-slate-600'"></i>
              </div>
              <div>
                <h3 class="text-xl font-bold text-gray-900 dark:text-white">Gradería</h3>
                <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">Asiento en gradería - Vista cómoda y segura del evento</p>
              </div>
            </div>
            <div class="text-right flex-shrink-0">
              <p class="text-3xl font-bold text-blue-600 dark:text-blue-400">99€</p>
            </div>
          </div>
        </div>

        <!-- Front Stage Ticket -->
        <div class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-lg border-2 border-slate-200 dark:border-gray-700 hover:border-blue-500 dark:hover:border-blue-400 transition-colors cursor-pointer"
          @click="selectTicket('front-stage')"
          :class="{ 'border-blue-500 dark:border-blue-400 bg-blue-50 dark:bg-blue-900/20': selectedTicket === 'front-stage' }">
          <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
              <div class="flex-shrink-0">
                <i class="pi pi-check-circle text-3xl" :class="selectedTicket === 'front-stage' ? 'text-blue-600 dark:text-blue-400' : 'text-slate-300 dark:text-slate-600'"></i>
              </div>
              <div>
                <h3 class="text-xl font-bold text-gray-900 dark:text-white">Front Stage</h3>
                <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">Zona frontal al escenario - Experiencia premium de primera fila</p>
              </div>
            </div>
            <div class="text-right flex-shrink-0">
              <p class="text-3xl font-bold text-blue-600 dark:text-blue-400">180€</p>
            </div>
          </div>
        </div>

        <!-- VIP Pass Ticket -->
        <div class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-lg border-2 border-slate-200 dark:border-gray-700 hover:border-blue-500 dark:hover:border-blue-400 transition-colors cursor-pointer"
          @click="selectTicket('vip')"
          :class="{ 'border-blue-500 dark:border-blue-400 bg-blue-50 dark:bg-blue-900/20': selectedTicket === 'vip' }">
          <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
              <div class="flex-shrink-0">
                <i class="pi pi-star-fill text-3xl" :class="selectedTicket === 'vip' ? 'text-yellow-500' : 'text-slate-300 dark:text-slate-600'"></i>
              </div>
              <div>
                <h3 class="text-xl font-bold text-gray-900 dark:text-white">VIP Pass</h3>
                <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">Acceso VIP premium - Incluye zona VIP, parking y servicios especiales</p>
              </div>
            </div>
            <div class="text-right flex-shrink-0">
              <p class="text-3xl font-bold text-blue-600 dark:text-blue-400">299€</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Selected Ticket Summary -->
      <div v-if="selectedTicket" class="bg-gradient-to-r from-blue-50 to-blue-100 dark:from-blue-900/30 dark:to-blue-800/30 rounded-lg p-6 mb-8 border-2 border-blue-200 dark:border-blue-700">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm text-slate-600 dark:text-slate-400 font-semibold">Entrada seleccionada</p>
            <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">
              {{ getTicketLabel(selectedTicket) }}
            </p>
          </div>
          <div class="text-right">
            <p class="text-sm text-slate-600 dark:text-slate-400 font-semibold">Precio Total</p>
            <p class="text-4xl font-bold text-blue-600 dark:text-blue-400">
              {{ getTicketPrice(selectedTicket) }}€
            </p>
          </div>
        </div>
      </div>

      <!-- Buy Button -->
      <Button 
        label="Comprar Entrada" 
        icon="pi pi-shopping-cart"
        class="w-full"
        size="large"
        style="background-color: #3b82f6; border-color: #3b82f6; padding: 1rem; font-size: 1.125rem; font-weight: 600;"
        :disabled="!selectedTicket"
        @click="handleCompra"
      />
      <p v-if="!selectedTicket" class="text-center text-slate-600 dark:text-slate-400 text-sm mt-3">
        Selecciona un tipo de entrada para continuar
      </p>
    </div>

    <!-- Not Found State -->
    <div v-else class="text-center py-12">
      <i class="pi pi-inbox text-5xl text-slate-400"></i>
      <p class="text-slate-600 dark:text-slate-400 text-lg mt-4">Evento no encontrado</p>
      <Button 
        label="Volver"
        icon="pi pi-arrow-left"
        class="mt-6"
        @click="goBack"
      />
    </div>
  </div>

  <!-- Global Footer -->
  <AppFooter />
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import Button from 'primevue/button';
import AppFooter from '@/components/AppFooter.vue';
import useEvents from '@/composables/events';

const router = useRouter();
const route = useRoute();
const { event, isLoading, getEvent } = useEvents();

const selectedTicket = ref(null);

const tickets = {
  pista: { label: 'Pista', price: 99 },
  graderia: { label: 'Gradería', price: 99 },
  'front-stage': { label: 'Front Stage', price: 180 },
  vip: { label: 'VIP Pass', price: 299 }
};

const getTicketLabel = (ticketType) => {
  return tickets[ticketType]?.label || 'N/A';
};

const getTicketPrice = (ticketType) => {
  return tickets[ticketType]?.price || 0;
};

const selectTicket = (ticketType) => {
  selectedTicket.value = selectedTicket.value === ticketType ? null : ticketType;
};

const formatDate = (dateStr) => {
  if (!dateStr) return 'Fecha no especificada';
  const date = new Date(dateStr);
  return date.toLocaleDateString('es-ES', {
    weekday: 'long',
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  });
};

const formatTime = (dateStr) => {
  if (!dateStr) return 'Hora no especificada';
  const date = new Date(dateStr);
  return date.toLocaleTimeString('es-ES', {
    hour: '2-digit',
    minute: '2-digit'
  });
};

const handleCompra = () => {
  console.log('🛒 Intento de compra:', {
    eventId: event.value.id,
    ticket: selectedTicket.value,
    label: getTicketLabel(selectedTicket.value),
    price: getTicketPrice(selectedTicket.value)
  });
};

const goBack = () => {
  router.push({ name: 'events.detail', params: { id: event.value.id } });
};

onMounted(() => {
  const eventId = route.params.id;
  if (eventId) {
    getEvent(eventId);
  }
});
</script>

<style scoped>
/* Smooth transitions */
.transition-colors {
  transition: border-color 0.3s ease, background-color 0.3s ease;
}

/* Dark mode adjustments */
@media (prefers-color-scheme: dark) {
  .event-tickets-page {
    background-color: transparent;
  }
}
</style>
