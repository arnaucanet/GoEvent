<template>
  <div class="event-detail-page bg-gradient-to-b from-white to-slate-50 dark:from-gray-900 dark:to-gray-950">
    <!-- Loading State -->
    <div v-if="isLoading" class="flex items-center justify-center py-20 min-h-screen">
      <i class="pi pi-spin pi-spinner text-5xl text-blue-500"></i>
    </div>

    <!-- Event Tickets & Details -->
    <div v-else-if="event?.id">
      <!-- Back Button -->
      <div class="container mx-auto px-4 sm:px-6 lg:px-8 pt-6 pb-2">
        <button 
          @click="goBack"
          class="flex items-center gap-2 text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 font-medium transition-colors"
        >
          <i class="pi pi-arrow-left"></i>
          <span>Volver a eventos</span>
        </button>
      </div>

      <!-- Header Section -->
      <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg p-8">
          <h1 class="text-3xl md:text-4xl font-bold mb-4">{{ event.title }}</h1>
          <div class="flex flex-wrap gap-6">
            <div class="flex items-center gap-2">
              <i class="pi pi-map-marker"></i>
              <span>{{ event.city || '-' }}</span>
            </div>
            <div class="flex items-center gap-2">
              <i class="pi pi-tag"></i>
              <span>{{ event.category?.name || 'General' }}</span>
            </div>
            <div class="flex items-center gap-2" v-if="event.featured">
              <i class="pi pi-star-fill text-yellow-300"></i>
              <span>Evento Destacado</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Main Content: 2 Columns Layout -->
      <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-12">
          
          <!-- LEFT COLUMN: Event Image / Seat Map -->
          <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-slate-200 dark:border-gray-700 overflow-hidden flex items-center justify-center min-h-[500px]">
            <img 
              v-if="event.image" 
              :src="`/images/${event.image}`" 
              :alt="event.title"
              class="w-full h-full object-cover"
            >
            <div v-else class="text-center">
              <i class="pi pi-image text-6xl text-slate-300 dark:text-slate-600 mb-4"></i>
              <p class="text-slate-500 dark:text-slate-400 font-medium text-lg">Imagen del Evento</p>
              <p class="text-sm text-slate-400 dark:text-slate-500 mt-2">O Mapa de Asientos</p>
            </div>
          </div>

          <!-- RIGHT COLUMN: Ticket Selection -->
          <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-slate-200 dark:border-gray-700 p-6">
            <!-- Tabs/Filters -->
            <div class="mb-6 flex gap-3 items-center">

            </div>

            <!-- Title -->
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Buscar Entradas</h2>

            <!-- Quick Filters -->
            <div class="flex gap-3 mb-6">
              <button class="px-4 py-2 rounded-full bg-slate-100 dark:bg-slate-700 text-gray-900 dark:text-white text-sm font-medium hover:bg-slate-200 dark:hover:bg-slate-600 transition-colors">
                Todos los precios
              </button>
              <button class="px-4 py-2 rounded-full bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-300 text-sm font-medium border-2 border-blue-500">
                Todas las secciones
              </button>
            </div>

            <!-- Tickets List with Quantity -->
            <div class="space-y-4 mb-8">
              <!-- Ticket Item 1: Front Stage Pista -->
              <div class="border-2 border-slate-200 dark:border-gray-700 rounded-lg p-4 hover:border-blue-500 dark:hover:border-blue-400 transition-colors">
                <div class="flex items-center justify-between">
                  <div class="flex items-center gap-4">
                    <div class="w-4 h-4 bg-blue-500 rounded-full flex-shrink-0"></div>
                    <div class="flex-1">
                      <p class="font-bold text-gray-900 dark:text-white">Front Stage Pista</p>
                      <p class="text-sm text-slate-600 dark:text-slate-400">102,00 € cada una</p>
                    </div>
                  </div>
                  <div class="flex items-center gap-3">
                    <div class="flex items-center border border-slate-300 dark:border-slate-500 rounded-lg">
                      <button @click="decrementTicket(0)" class="px-2 py-1 text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-200">−</button>
                      <span class="px-3 py-1 text-gray-900 dark:text-white font-semibold">{{ ticketQuantities[0] }}</span>
                      <button @click="incrementTicket(0)" class="px-2 py-1 text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-200">+</button>
                    </div>

                  </div>
                </div>
              </div>

              <!-- Ticket Item 2: Pista -->
              <div class="border-2 border-slate-200 dark:border-gray-700 rounded-lg p-4 hover:border-blue-500 dark:hover:border-blue-400 transition-colors">
                <div class="flex items-center justify-between">
                  <div class="flex items-center gap-4">
                    <div class="w-4 h-4 bg-blue-500 rounded-full flex-shrink-0"></div>
                    <div class="flex-1">
                      <p class="font-bold text-gray-900 dark:text-white">Pista</p>
                      <p class="text-sm text-slate-600 dark:text-slate-400">51,00 € cada una</p>
                    </div>
                  </div>
                  <div class="flex items-center gap-3">
                    <div class="flex items-center border border-slate-300 dark:border-slate-500 rounded-lg">
                      <button @click="decrementTicket(1)" class="px-2 py-1 text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-200">−</button>
                      <span class="px-3 py-1 text-gray-900 dark:text-white font-semibold">{{ ticketQuantities[1] }}</span>
                      <button @click="incrementTicket(1)" class="px-2 py-1 text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-200">+</button>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Ticket Item 3: Gradería -->
              <div class="border-2 border-slate-200 dark:border-gray-700 rounded-lg p-4 hover:border-blue-500 dark:hover:border-blue-400 transition-colors">
                <div class="flex items-center justify-between">
                  <div class="flex items-center gap-4">
                    <div class="w-4 h-4 bg-blue-500 rounded-full flex-shrink-0"></div>
                    <div class="flex-1">
                      <p class="font-bold text-gray-900 dark:text-white">Asiento Reservado Grada</p>
                      <p class="text-sm text-slate-600 dark:text-slate-400">102,00 € cada una</p>
                    </div>
                  </div>
                  <div class="flex items-center gap-3">
                    <div class="flex items-center border border-slate-300 dark:border-slate-500 rounded-lg">
                      <button @click="decrementTicket(2)" class="px-2 py-1 text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-200">−</button>
                      <span class="px-3 py-1 text-gray-900 dark:text-white font-semibold">{{ ticketQuantities[2] }}</span>
                      <button @click="incrementTicket(2)" class="px-2 py-1 text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-200">+</button>
                    </div>
                    
                  </div>
                </div>
              </div>
            </div>

            <!-- Info Text -->
            <p class="text-xs text-slate-600 dark:text-slate-400 mb-4">
              Los precios incluyen 6,00 €–12,00 € de gastos de gestión. 
              <br>
              <span class="text-xs">
                Es posible que se apliquen cargos adicionales por compra según el método de pago.
              </span>
            </p>

            <!-- Limit Info -->
            <div class="flex items-center gap-2 mb-6 p-3 bg-slate-100 dark:bg-slate-700 rounded-lg text-sm text-slate-700 dark:text-slate-300">
              <i class="pi pi-ticket"></i>
              <span>× <span class="font-semibold">0</span>&nbsp;&nbsp;&nbsp;Límite de entradas por evento: 6</span>
            </div>

            <!-- Try Tickets Button -->
            <Button 
              label="Finalizar Compra" 
              icon="pi pi-shopping-cart"
              class="w-full"
              size="large"
              style="background-color: #059669; border-color: #059669; padding: 0.875rem; font-size: 1rem; font-weight: 600;"
              @click="tryTickets"
            />
          </div>
        </div>

        <!-- Event Information Section Below -->
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

          <!-- End Date -->
          <div class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-lg border border-slate-200 dark:border-gray-700">
            <div class="flex items-start gap-3">
              <div class="bg-green-100 dark:bg-green-900 rounded-lg p-3">
                <i class="pi pi-calendar text-xl text-green-600 dark:text-green-300"></i>
              </div>
              <div>
                <h3 class="font-semibold text-gray-900 dark:text-white mb-1">Fecha de Vencimiento</h3>
                <p class="text-slate-600 dark:text-slate-400">{{ formatDate(event.end_date) }}</p>
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

          <!-- Location -->
          <div class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-lg border border-slate-200 dark:border-gray-700">
            <div class="flex items-start gap-3">
              <div class="bg-orange-100 dark:bg-orange-900 rounded-lg p-3">
                <i class="pi pi-map-marker text-xl text-orange-600 dark:text-orange-300"></i>
              </div>
              <div>
                <h3 class="font-semibold text-gray-900 dark:text-white mb-1">Ubicación</h3>
                <p class="text-slate-600 dark:text-slate-400">{{ event.city || 'No especificada' }}</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Description -->
        <div class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-lg border border-slate-200 dark:border-gray-700 mb-8">
          <h2 class="text-2xl font-bold mb-4 text-gray-900 dark:text-white flex items-center gap-2">
            <i class="pi pi-info-circle text-blue-500"></i>Descripción
          </h2>
          <p class="text-base leading-7 text-slate-600 dark:text-slate-400">
            {{ event.description || 'Sin descripción disponible.' }}
          </p>
        </div>
      </div>
    </div>

    <!-- Not Found State -->
    <div v-else class="container mx-auto px-4 sm:px-6 lg:px-8 py-20 text-center">
      <i class="pi pi-inbox text-6xl text-slate-300 dark:text-slate-600 mb-4"></i>
      <p class="text-slate-600 dark:text-slate-400 text-lg mb-6">Evento no encontrado</p>
      <Button 
        label="Volver a eventos"
        icon="pi pi-arrow-left"
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
import Tag from 'primevue/tag';
import AppFooter from '@/components/AppFooter.vue';
import useEvents from '@/composables/events';

const router = useRouter();
const route = useRoute();
const { event, isLoading, getPublicEvent } = useEvents();

// Ticket quantities
const ticketQuantities = ref([0, 0, 0]);

const tickets = [
  { label: 'Front Stage Pista', price: 102 },
  { label: 'Pista', price: 51 },
  { label: 'Asiento Reservado Grada', price: 102 }
];

const incrementTicket = (index) => {
  if (ticketQuantities.value[index] < 6) {
    ticketQuantities.value[index]++;
  }
};

const decrementTicket = (index) => {
  if (ticketQuantities.value[index] > 0) {
    ticketQuantities.value[index]--;
  }
};

const tryTickets = () => {
  const hasSelection = ticketQuantities.value.some(qty => qty > 0);
  if (hasSelection) {
    // Guardar datos en sessionStorage para que compras.vue pueda acceder
    sessionStorage.setItem('ticketQuantities', JSON.stringify(ticketQuantities.value));
    
    console.log('🎫 Navegando a compra con:', {
      eventId: event.value.id,
      tickets: ticketQuantities.value,
      details: tickets.map((t, i) => ({
        ...t,
        quantity: ticketQuantities.value[i]
      }))
    });
    
    // Navegar a la página de compras
    router.push({ name: 'events.compras', params: { id: event.value.id } });
  } else {
    alert('Por favor selecciona al menos una entrada');
  }
};

const goBack = () => {
  router.push({ name: 'events.list' });
};

const formatDate = (dateStr) => {
  if (!dateStr) return '-';
  const date = new Date(dateStr);
  return date.toLocaleDateString('es-ES', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
  });
};

const formatDateTime = (dateStr) => {
  if (!dateStr) return '-';
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

const goToCompras = () => {
  router.push({ name: 'events.compras', params: { id: event.value.id } });
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
    getPublicEvent(eventId);
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
  .event-detail-page {
    background-color: transparent;
  }
}
</style>
