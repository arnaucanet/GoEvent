<template>
  <div class="event-detail-page container mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-14"
    :class="isDarkTheme ? 'text-slate-100' : 'text-slate-900'">
    <!-- Loading State -->
    <div v-if="isLoading" class="flex items-center justify-center py-20">
      <i class="pi pi-spin pi-spinner text-5xl text-blue-500"></i>
    </div>

    <!-- Event Tickets & Details -->
    <div v-else-if="event?.id">
      <!-- Back Button -->
      <div class="pt-2 pb-2">
        <button @click="goBack"
          class="flex items-center gap-2 font-medium transition-colors"
          :class="isDarkTheme ? 'text-blue-400 hover:text-blue-300' : 'text-blue-600 hover:text-blue-700'">
          <i class="pi pi-arrow-left"></i>
          <span>Volver a eventos</span>
        </button>
      </div>

      <!-- Header Section -->
      <div class="py-6">
        <div class="bg-blue-600 text-white rounded-xl p-8">
          <div class="flex items-start justify-between gap-4">
            <h1 class="text-3xl md:text-4xl font-bold mb-4">{{ event.title }}</h1>
            <button
              v-if="isAuthenticated"
              type="button"
              class="rounded-full bg-white/15 hover:bg-white/25 transition p-3 disabled:opacity-50"
              :title="isFavorite ? 'Quitar de favoritos' : 'Añadir a favoritos'"
              :disabled="isToggling"
              @click="onToggleFavorite"
            >
              <i :class="['pi', isFavorite ? 'pi-heart-fill text-yellow-300' : 'pi-heart', 'text-2xl']"></i>
            </button>
          </div>
          <div class="flex flex-wrap gap-6">
            <div class="flex items-center gap-2">
              <i class="pi pi-map-marker"></i>
              <span>{{ event.venueRelation?.city || '-' }}</span>
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

          <!-- Types & Artists badges -->
          <div v-if="(event.types && event.types.length) || (event.artists && event.artists.length)" class="mt-5 flex flex-wrap gap-2">
            <span
              v-for="type in event.types"
              :key="'t-' + type.id"
              class="px-3 py-1 rounded-full text-xs font-semibold bg-white/20 text-white"
            >
              <i class="pi pi-bookmark mr-1"></i>{{ type.name }}
            </span>
            <span
              v-for="artist in event.artists"
              :key="'a-' + artist.id"
              class="px-3 py-1 rounded-full text-xs font-semibold bg-sky-400/30 text-white"
            >
              <i class="pi pi-user mr-1"></i>{{ artist.name }}
            </span>
          </div>

          <!-- Register button -->
          <div v-if="isAuthenticated" class="mt-6">
            <Button
              v-if="!isRegistered"
              :label="isRegistering ? 'Procesando...' : 'Inscribirme al evento'"
              icon="pi pi-check-circle"
              class="!text-white !bg-blue-400 !border-blue-400 hover:!bg-blue-500"
              :disabled="isRegistering"
              @click="onRegister"
            />
            <Button
              v-else
              label="Cancelar inscripción"
              class="!text-white !bg-blue-400 !border-blue-400 hover:!bg-blue-500"
              icon="pi pi-times-circle"
              severity="secondary"
              outlined
              :disabled="isRegistering"
              @click="onCancelRegistration"
            />
            <span v-if="isRegistered" class="ml-3 text-sm text-blue-100">
              <i class="pi pi-check-circle"></i> Estás inscrito
            </span>
          </div>
        </div>
      </div>

      <!-- Main Content: 2 Columns Layout -->
      <div class="py-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-12">

          <!-- LEFT COLUMN: Event Image / Seat Map -->
          <div class="relative rounded-lg shadow-lg border overflow-hidden min-h-[500px] h-full"
            :class="isDarkTheme ? 'bg-gray-800 border-gray-700' : 'bg-white border-slate-200'">
            <template v-if="event.image">
              <img
                :src="`/images/${event.image}`"
                :alt="event.title"
                class="absolute inset-0 w-full h-full object-cover"
              >
            </template>
            <div v-else class="absolute inset-0 flex flex-col items-center justify-center text-center">
              <i class="pi pi-image text-6xl text-slate-300 dark:text-slate-600 mb-4"></i>
              <p class="text-slate-500 dark:text-slate-400 font-medium text-lg">Imagen del Evento</p>
              <p class="text-sm text-slate-400 dark:text-slate-500 mt-2">O Mapa de Asientos</p>
            </div>
          </div>

          <!-- RIGHT COLUMN: Ticket Selection -->
          <div class="rounded-xl shadow-sm border p-6"
            :class="isDarkTheme ? 'bg-gray-800 border-gray-700' : 'bg-white border-slate-200'">
            <!-- Tabs/Filters -->
            <div class="mb-6 flex gap-3 items-center">

            </div>

            <!-- Title -->
            <h2 class="text-2xl font-bold mb-6" :class="isDarkTheme ? 'text-white' : 'text-gray-900'">Buscar Entradas</h2>

            <!-- Quick Filters -->
            <div class="flex gap-3 mb-6">
              <button
                class="px-4 py-2 rounded-full text-sm font-medium transition-colors"
                :class="isDarkTheme ? 'bg-slate-700 text-white hover:bg-slate-600' : 'bg-slate-100 text-gray-900 hover:bg-slate-200'">
                Todos los precios
              </button>
              <button
                class="px-4 py-2 rounded-full text-sm font-medium border border-blue-500"
                :class="isDarkTheme ? 'bg-blue-900 text-blue-300' : 'bg-blue-100 text-blue-700'">
                Todas las secciones
              </button>
            </div>

            <!-- Tickets List with Quantity -->
            <div class="space-y-4 mb-8">
              <!-- Ticket Item 1: Front Stage Pista -->
              <div
                class="border rounded-lg p-4 transition-colors"
                :class="isDarkTheme ? 'border-gray-700 hover:border-blue-400' : 'border-slate-200 hover:border-blue-500'">
                <div class="flex items-center justify-between">
                  <div class="flex items-center gap-4">
                    <div class="w-4 h-4 bg-blue-500 rounded-full flex-shrink-0"></div>
                    <div class="flex-1">
                      <p class="font-bold" :class="isDarkTheme ? 'text-white' : 'text-gray-900'">Front Stage Pista</p>
                      <p class="text-sm" :class="isDarkTheme ? 'text-slate-400' : 'text-slate-600'">102,00 € cada una</p>
                    </div>
                  </div>
                  <div class="flex items-center gap-3">
                    <div class="flex items-center border rounded-lg" :class="isDarkTheme ? 'border-slate-500' : 'border-slate-300'">
                      <button @click="decrementTicket(0)"
                        class="px-2 py-1"
                        :class="isDarkTheme ? 'text-slate-400 hover:text-slate-200' : 'text-slate-600 hover:text-slate-900'">−</button>
                      <span class="px-3 py-1 font-semibold" :class="isDarkTheme ? 'text-white' : 'text-gray-900'">{{ ticketQuantities[0] }}</span>
                      <button @click="incrementTicket(0)"
                        class="px-2 py-1"
                        :class="isDarkTheme ? 'text-slate-400 hover:text-slate-200' : 'text-slate-600 hover:text-slate-900'">+</button>
                    </div>

                  </div>
                </div>
              </div>

              <!-- Ticket Item 2: Pista -->
              <div
                class="border rounded-lg p-4 transition-colors"
                :class="isDarkTheme ? 'border-gray-700 hover:border-blue-400' : 'border-slate-200 hover:border-blue-500'">
                <div class="flex items-center justify-between">
                  <div class="flex items-center gap-4">
                    <div class="w-4 h-4 bg-blue-500 rounded-full flex-shrink-0"></div>
                    <div class="flex-1">
                      <p class="font-bold" :class="isDarkTheme ? 'text-white' : 'text-gray-900'">Pista</p>
                      <p class="text-sm" :class="isDarkTheme ? 'text-slate-400' : 'text-slate-600'">51,00 € cada una</p>
                    </div>
                  </div>
                  <div class="flex items-center gap-3">
                    <div class="flex items-center border rounded-lg" :class="isDarkTheme ? 'border-slate-500' : 'border-slate-300'">
                      <button @click="decrementTicket(1)"
                        class="px-2 py-1"
                        :class="isDarkTheme ? 'text-slate-400 hover:text-slate-200' : 'text-slate-600 hover:text-slate-900'">−</button>
                      <span class="px-3 py-1 font-semibold" :class="isDarkTheme ? 'text-white' : 'text-gray-900'">{{ ticketQuantities[1] }}</span>
                      <button @click="incrementTicket(1)"
                        class="px-2 py-1"
                        :class="isDarkTheme ? 'text-slate-400 hover:text-slate-200' : 'text-slate-600 hover:text-slate-900'">+</button>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Ticket Item 3: Gradería -->
              <div
                class="border rounded-lg p-4 transition-colors"
                :class="isDarkTheme ? 'border-gray-700 hover:border-blue-400' : 'border-slate-200 hover:border-blue-500'">
                <div class="flex items-center justify-between">
                  <div class="flex items-center gap-4">
                    <div class="w-4 h-4 bg-blue-500 rounded-full flex-shrink-0"></div>
                    <div class="flex-1">
                      <p class="font-bold" :class="isDarkTheme ? 'text-white' : 'text-gray-900'">Asiento Reservado Grada</p>
                      <p class="text-sm" :class="isDarkTheme ? 'text-slate-400' : 'text-slate-600'">102,00 € cada una</p>
                    </div>
                  </div>
                  <div class="flex items-center gap-3">
                    <div class="flex items-center border rounded-lg" :class="isDarkTheme ? 'border-slate-500' : 'border-slate-300'">
                      <button @click="decrementTicket(2)"
                        class="px-2 py-1"
                        :class="isDarkTheme ? 'text-slate-400 hover:text-slate-200' : 'text-slate-600 hover:text-slate-900'">−</button>
                      <span class="px-3 py-1 font-semibold" :class="isDarkTheme ? 'text-white' : 'text-gray-900'">{{ ticketQuantities[2] }}</span>
                      <button @click="incrementTicket(2)"
                        class="px-2 py-1"
                        :class="isDarkTheme ? 'text-slate-400 hover:text-slate-200' : 'text-slate-600 hover:text-slate-900'">+</button>
                    </div>

                  </div>
                </div>
              </div>
            </div>

            <!-- Info Text -->
            <div class="mb-6 p-4 rounded-lg bg-blue-50 dark:bg-blue-900/30 border border-blue-200 dark:border-blue-800">
              <p class="text-lg font-bold text-blue-900 dark:text-blue-200">
                Total: <span class="text-1xl text-blue-600 dark:text-blue-400">{{ totalTicketsPriceCurrency }}</span>
              </p>
            </div>

            <p class="text-xs mb-4" :class="isDarkTheme ? 'text-slate-400' : 'text-slate-600'">
              Los precios incluyen 6,00 €–12,00 € de gastos de gestión.
              <br>
              <span class="text-xs">
                Es posible que se apliquen cargos adicionales por compra según el método de pago.
              </span>
            </p>

            <!-- Limit Info -->
            <div
              class="flex items-center gap-2 mb-6 p-3 rounded-lg text-sm"
              :class="isDarkTheme ? 'bg-slate-700 text-slate-300' : 'bg-slate-100 text-slate-700'">
              <i class="pi pi-ticket"></i>
              <span>× <span class="font-semibold">0</span> Límite de entradas por evento: 6</span>
            </div>

            <!-- Try Tickets Button -->
              <Button label="Finalizar Compra" icon="pi pi-shopping-cart"
                class="w-full !bg-blue-600 !border-blue-600 hover:!bg-blue-700" size="large" :pt="{
                    label: { class: '!text-white' },
                    icon: { class: '!text-white' }
                }" @click="tryTickets" />
          </div>
        </div>

        <!-- Event Information Section Below -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
          <!-- Start Date -->
          <div class="rounded-xl p-6 shadow-sm border" :class="isDarkTheme ? 'bg-gray-800 border-gray-700' : 'bg-white border-slate-200'">
            <div class="flex items-start gap-3">
              <div class="rounded-lg p-3" :class="isDarkTheme ? 'bg-blue-900' : 'bg-blue-100'">
                <i class="pi pi-calendar text-xl" :class="isDarkTheme ? 'text-blue-300' : 'text-blue-600'"></i>
              </div>
              <div>
                <h3 class="font-semibold mb-1" :class="isDarkTheme ? 'text-white' : 'text-gray-900'">Fecha de Inicio</h3>
                <p :class="isDarkTheme ? 'text-slate-400' : 'text-slate-600'">{{ formatDate(event.start_date) }}</p>
              </div>
            </div>
          </div>

          <!-- End Date -->
          <div class="rounded-xl p-6 shadow-sm border" :class="isDarkTheme ? 'bg-gray-800 border-gray-700' : 'bg-white border-slate-200'">
            <div class="flex items-start gap-3">
              <div class="rounded-lg p-3" :class="isDarkTheme ? 'bg-green-900' : 'bg-green-100'">
                <i class="pi pi-calendar text-xl" :class="isDarkTheme ? 'text-green-300' : 'text-green-600'"></i>
              </div>
              <div>
                <h3 class="font-semibold mb-1" :class="isDarkTheme ? 'text-white' : 'text-gray-900'">Fecha de Vencimiento</h3>
                <p :class="isDarkTheme ? 'text-slate-400' : 'text-slate-600'">{{ formatDate(event.end_date) }}</p>
              </div>
            </div>
          </div>

          <!-- Capacity -->
          <div class="rounded-xl p-6 shadow-sm border" :class="isDarkTheme ? 'bg-gray-800 border-gray-700' : 'bg-white border-slate-200'">
            <div class="flex items-start gap-3">
              <div class="rounded-lg p-3" :class="isDarkTheme ? 'bg-purple-900' : 'bg-purple-100'">
                <i class="pi pi-users text-xl" :class="isDarkTheme ? 'text-purple-300' : 'text-purple-600'"></i>
              </div>
              <div>
                <h3 class="font-semibold mb-1" :class="isDarkTheme ? 'text-white' : 'text-gray-900'">Capacidad</h3>
                <p :class="isDarkTheme ? 'text-slate-400' : 'text-slate-600'">{{ event.capacity || 0 }} personas</p>
              </div>
            </div>
          </div>

          <!-- Location -->
          <div class="rounded-xl p-6 shadow-sm border" :class="isDarkTheme ? 'bg-gray-800 border-gray-700' : 'bg-white border-slate-200'">
            <div class="flex items-start gap-3">
              <div class="rounded-lg p-3" :class="isDarkTheme ? 'bg-orange-900' : 'bg-orange-100'">
                <i class="pi pi-map-marker text-xl" :class="isDarkTheme ? 'text-orange-300' : 'text-orange-600'"></i>
              </div>
              <div>
                <h3 class="font-semibold mb-1" :class="isDarkTheme ? 'text-white' : 'text-gray-900'">Ubicación</h3>
                <p :class="isDarkTheme ? 'text-slate-400' : 'text-slate-600'">{{ event.venueRelation?.name }}<span>{{ event.venueRelation?.city || event.venue_relation?.city || '-' }}</span></p>
              </div>
            </div>
          </div>
        </div>

        <!-- Description -->
        <div class="rounded-xl p-6 shadow-sm border mb-8" :class="isDarkTheme ? 'bg-gray-800 border-gray-700' : 'bg-white border-slate-200'">
          <h2 class="text-2xl font-bold mb-4 flex items-center gap-2" :class="isDarkTheme ? 'text-white' : 'text-gray-900'">
            <i class="pi pi-info-circle text-blue-500"></i>Descripción
          </h2>
          <p class="text-base leading-7" :class="isDarkTheme ? 'text-slate-400' : 'text-slate-600'">
            {{ event.description || 'Sin descripción disponible.' }}
          </p>
        </div>
      </div>
    </div>

    <!-- Not Found State -->
    <div v-else class="container mx-auto px-4 sm:px-6 lg:px-8 py-20 text-center">
      <i class="pi pi-inbox text-6xl mb-4" :class="isDarkTheme ? 'text-slate-600' : 'text-slate-300'"></i>
      <p class="text-lg mb-6" :class="isDarkTheme ? 'text-slate-400' : 'text-slate-600'">Evento no encontrado</p>
      <Button label="Volver a eventos" icon="pi pi-arrow-left" @click="goBack" />
    </div>
  </div>

  <!-- Global Footer -->
  <AppFooter />
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import Button from 'primevue/button';
import AppFooter from '@/components/AppFooter.vue';
import useEvents from '@/composables/events';
import useEventActions from '@/composables/eventActions';
import { useLayout } from '@/composables/layout';
import { authStore } from '@/store/auth';

const router = useRouter();
const route = useRoute();
const { event, isLoading, getPublicEvent } = useEvents();
const { isToggling, isRegistering, toggleFavorite, registerToEvent, cancelRegistration } = useEventActions();
const { isDarkTheme, setDefaultMode } = useLayout();
const auth = authStore();

const isAuthenticated = computed(() => !!auth.authenticated);
const isFavorite = computed(() => !!event.value?.is_favorite);
const isRegistered = computed(() => !!event.value?.is_registered);

const onToggleFavorite = async () => {
    const result = await toggleFavorite(event.value.id);
    if (result !== null) {
        event.value.is_favorite = result;
    }
};

const onRegister = async () => {
    const ok = await registerToEvent(event.value.id);
    if (ok) event.value.is_registered = true;
};

const onCancelRegistration = async () => {
    const ok = await cancelRegistration(event.value.id);
    if (ok) event.value.is_registered = false;
};

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

const totalTicketsPrice = computed(() => {
  return tickets.reduce((total, ticket, index) => {
    return total + (ticket.price * ticketQuantities.value[index]);
  }, 0);
});

const totalTicketsPriceCurrency = computed(() => {
  return totalTicketsPrice.value.toFixed(2) + ' €';
});

const decrementTicket = (index) => {
  if (ticketQuantities.value[index] > 0) {
    ticketQuantities.value[index]--;
  }
};

const tryTickets = () => {
  const hasSelection = ticketQuantities.value.some(qty => qty > 0);
  if (hasSelection) {
    sessionStorage.setItem('ticketQuantities', JSON.stringify(ticketQuantities.value));
    
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
  setDefaultMode();
  const eventId = route.params.id;
  if (eventId) {
    getPublicEvent(eventId);
  }
});
</script>
