<template>
  <div class="compras-page bg-white dark:bg-gray-950 min-h-screen flex flex-col">
    <!-- Loading State -->
    <div v-if="isLoading" class="flex items-center justify-center py-20 min-h-screen">
      <i class="pi pi-spin pi-spinner text-5xl text-blue-500"></i>
    </div>

    <!-- Checkout Page -->
    <div v-else-if="event?.id" class="flex-1">
      <!-- Header with Dark Background -->
      <div class="bg-gray-900 text-white py-8">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
          <div class="flex items-center justify-between mb-4">
            <div>
              <h1 class="text-3xl md:text-4xl font-bold">PROCESO DE COMPRA</h1>
              <div class="h-1 w-32 bg-blue-600 mt-2"></div>
            </div>
            <div class="text-right">
              <p class="text-sm text-gray-400">Tiempo restante</p>
              <p class="text-2xl font-bold">09:08</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Back Button -->
      <div class="container mx-auto px-4 sm:px-6 lg:px-8 pt-6">
        <button 
          @click="goBack"
          class="flex items-center gap-2 text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 font-medium transition-colors mb-8"
        >
          <i class="pi pi-arrow-left"></i>
          <span>Volver a entradas</span>
        </button>
      </div>

      <!-- Main Content: 2 Columns -->
      <div class="container mx-auto px-4 sm:px-6 lg:px-8 pb-12">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
          
          <!-- LEFT COLUMN (2/3): Checkout Sections -->
          <div class="lg:col-span-2 space-y-6">
            
            <!-- ENTREGA TICKETS -->
            <section class="bg-white dark:bg-gray-800 rounded-lg border border-slate-200 dark:border-gray-700 p-6">
              <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                <i class="pi pi-check text-green-600"></i>
                ENTREGA TICKETS
              </h2>
              <p class="text-sm text-slate-600 dark:text-slate-400 mb-4">Los tickets se enviarán a tu correo electrónico</p>
              <div class="bg-slate-50 dark:bg-gray-700 rounded-lg p-4">
                <p class="text-sm font-medium text-gray-900 dark:text-white">{{ userEmail }}</p>
                <p class="text-xs text-slate-600 dark:text-slate-400 mt-1">
                  <a href="#" class="text-blue-600 dark:text-blue-400 hover:underline">Editar</a>
                </p>
              </div>
            </section>

            <!-- EXTRAS DEL EVENTO -->
            <section class="bg-white dark:bg-gray-800 rounded-lg border border-slate-200 dark:border-gray-700 p-6">
              <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">EXTRAS DEL EVENTO</h2>
              <div class="space-y-4">
                <label class="flex items-start gap-4 p-4 rounded-lg border border-slate-200 dark:border-gray-600 hover:border-blue-500 cursor-pointer transition-colors">
                  <input type="radio" name="extras" value="none" class="mt-1" checked>
                  <div class="flex-1">
                    <p class="font-medium text-gray-900 dark:text-white">Sin extras</p>
                    <p class="text-sm text-slate-600 dark:text-slate-400">No añadir extras a la compra</p>
                  </div>
                </label>
              </div>
            </section>

            <!-- PROTECCIÓN DE COMPRA -->
            <section class="bg-white dark:bg-gray-800 rounded-lg border border-slate-200 dark:border-gray-700 p-6">
              <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">PROTECCIÓN DE COMPRA</h2>
              <div class="space-y-4">
                <label class="flex items-start gap-4 p-4 rounded-lg border border-slate-200 dark:border-gray-600 hover:border-blue-500 cursor-pointer transition-colors">
                  <input type="radio" name="insurance" value="yes" class="mt-1">
                  <div class="flex-1">
                    <p class="font-medium text-gray-900 dark:text-white">Proteger mi compra</p>
                    <p class="text-sm text-slate-600 dark:text-slate-400">Cubre cancelaciones y cambios de fecha</p>
                    <p class="text-sm font-semibold text-gray-900 dark:text-white mt-2">+ 12,00 €</p>
                  </div>
                </label>

                <label class="flex items-start gap-4 p-4 rounded-lg border border-slate-200 dark:border-gray-600 hover:border-blue-500 cursor-pointer transition-colors">
                  <input type="radio" name="insurance" value="no" class="mt-1" checked>
                  <div class="flex-1">
                    <p class="font-medium text-gray-900 dark:text-white">No proteger mi compra</p>
                    <p class="text-sm text-slate-600 dark:text-slate-400">Sin cobertura adicional</p>
                  </div>
                </label>
              </div>
            </section>

            <!-- CONDICIONES DE COMPRA -->
            <section class="bg-white dark:bg-gray-800 rounded-lg border border-slate-200 dark:border-gray-700 p-6">
              <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">CONDICIONES DE COMPRA</h2>
              <label class="flex items-start gap-3 mb-6">
                <input type="checkbox" class="mt-1">
                <span class="text-sm text-slate-600 dark:text-slate-400">
                  Confirmo que he leído y acepto los 
                  <a href="#" class="text-blue-600 dark:text-blue-400 hover:underline">Términos de Compra</a>
                  y la 
                  <a href="#" class="text-blue-600 dark:text-blue-400 hover:underline">Política de Privacidad</a>
                </span>
              </label>
              <Button 
                label="Finalizar Compra" 
                icon="pi pi-check"
                class="w-full !bg-green-600 !border-green-600 hover:!bg-green-700"
                size="large"
                @click="finalizarCompra"
              />
            </section>
          </div>

          <!-- RIGHT COLUMN (1/3): Order Summary -->
          <div class="lg:col-span-1">
            <div class="sticky top-6 bg-white dark:bg-gray-800 rounded-lg border border-slate-200 dark:border-gray-700 p-6">
              
              <!-- Total Price -->
              <div class="border-b border-slate-200 dark:border-gray-600 pb-4 mb-4">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">TOTAL</h3>
                <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ totalPriceCurrency }}</p>
              </div>

              <!-- Entradas Items -->
              <div class="mb-6">
                <h4 class="font-semibold text-gray-900 dark:text-white mb-3">ENTRADAS</h4>
                <div class="space-y-2 text-sm">
                  <div v-for="(item, index) in ticketsSummary" :key="index">
                    <div v-if="item && item.quantity > 0">
                        <p class="text-slate-600 dark:text-slate-400">
                        <span class="font-medium">{{ item.label }}</span>
                        <br>
                        <span class="text-xs">{{ item.price }} € × {{ item.quantity }}</span>
                        <span class="text-gray-900 dark:text-white font-semibold float-right">
                            {{ (item.price * item.quantity).toFixed(2) }} €
                        </span>
                        </p>
                    </div>
                    </div>
                  <div v-if="ticketsSummary.every(item => item.quantity === 0)" class="text-slate-500 dark:text-slate-400 italic">
                    No hay entradas seleccionadas
                  </div>
                </div>
              </div>

              <!-- Event Info -->
              <div class="bg-slate-50 dark:bg-gray-700 rounded-lg p-4">
                <h4 class="font-semibold text-gray-900 dark:text-white mb-3">EVENTO</h4>
                <div class="space-y-2 text-sm">
                  <div class="flex items-start gap-2">
                    <i class="pi pi-calendar text-blue-600 dark:text-blue-400 flex-shrink-0 mt-1"></i>
                    <span class="text-slate-600 dark:text-slate-400">{{ eventDate }}</span>
                  </div>
                  <div class="flex items-start gap-2">
                    <i class="pi pi-map-marker text-blue-600 dark:text-blue-400 flex-shrink-0 mt-1"></i>
                    <span class="text-slate-600 dark:text-slate-400">{{ eventLocation }}</span>
                  </div>
                </div>
              </div>

              <!-- Seat Map Placeholder -->
              <div class="mt-4 bg-slate-100 dark:bg-gray-600 rounded-lg overflow-hidden flex items-center justify-center min-h-[200px]">
                <img 
                  v-if="event.image" 
                  :src="`/images/${event.image}`" 
                  :alt="event.title"
                  class="w-full h-full object-cover"
                >
                <div v-else class="text-center">
                  <i class="pi pi-image text-4xl text-slate-400 dark:text-slate-500 mb-2"></i>
                  <p class="text-sm text-slate-600 dark:text-slate-400">Mapa de Asientos</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Not Found State -->
    <div v-else class="flex-1 container mx-auto px-4 sm:px-6 lg:px-8 py-20 text-center">
      <i class="pi pi-inbox text-6xl text-slate-300 dark:text-slate-600 mb-4"></i>
      <p class="text-slate-600 dark:text-slate-400 text-lg mb-6">Evento no encontrado</p>
      <Button 
        label="Volver"
        icon="pi pi-arrow-left"
        @click="goBack"
      />
    </div>
  </div>

  <AppFooter />
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import Button from 'primevue/button';
import AppFooter from '@/components/AppFooter.vue';
import useEvents from '@/composables/events';

const router = useRouter();
const route = useRoute();
const { event, isLoading, getPublicEvent } = useEvents();

const ticketQuantities = ref([0, 0, 0]);
const userEmail = ref('usuario@ejemplo.com');

const ticketDetails = [
  { label: 'Front Stage Pista', price: 102 },
  { label: 'Pista', price: 51 },
  { label: 'Asiento Reservado Grada', price: 102 }
];


const ticketsSummary = computed(() => {
  return ticketDetails.map((ticket, index) => ({
    ...ticket,
    quantity: ticketQuantities.value[index]
  }));
});

const totalPrice = computed(() => {
  return ticketDetails.reduce((total, ticket, index) => {
    return total + (ticket.price * ticketQuantities.value[index]);
  }, 0);
});

const totalPriceCurrency = computed(() => {
  return totalPrice.value.toFixed(2) + ' €';
});

const eventDate = computed(() => {
  if (!event.value?.start_date) return '-';
  const date = new Date(event.value.start_date);
  return date.toLocaleDateString('es-ES', {
    day: 'numeric',
    month: 'short',
    year: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  });
});

const eventLocation = computed(() => {
  return event.value?.city || 'Ubicación no especificada';
});


const goBack = () => {
  router.back();
};

const finalizarCompra = async () => {
  try {
    const totalSeleccionadas = ticketQuantities.value.reduce((a, b) => a + b, 0);
    
    console.log('🎫 Compra finalizada:', {
      entradas: ticketsSummary.value.filter(t => t.quantity > 0),
      total: totalPrice.value,
      email: userEmail.value,
      totalTickets: totalSeleccionadas
    });

    await new Promise(resolve => setTimeout(resolve, 800));

    sessionStorage.removeItem('ticketQuantities');
    sessionStorage.removeItem('userEmail');

    // Mostrar confirmación
    alert('✅ ¡Compra completada exitosamente! Recibirás los detalles en tu correo.');

    // Redirigir a home
    router.push({ name: 'home' });
  } catch (error) {
    console.error('Error al finalizar compra:', error);
    alert('❌ Error al procesar la compra. Por favor intenta de nuevo.');
  }
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

onMounted(() => {
  const eventId = route.params.id;
  
  // Get ticket quantities from sessionStorage
  const savedTickets = sessionStorage.getItem('ticketQuantities');
  if (savedTickets) {
    try {
      ticketQuantities.value = JSON.parse(savedTickets);
    } catch (e) {
      console.log('Error parsing tickets:', e);
    }
  }
  
  // Get user email from sessionStorage if available
  const savedEmail = sessionStorage.getItem('userEmail');
  if (savedEmail) {
    userEmail.value = savedEmail;
  }

  // Load event details
  if (eventId) {
    getPublicEvent(eventId);
  }
});
</script>
