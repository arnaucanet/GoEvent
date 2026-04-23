
<template>
  <div class="compras-page bg-white dark:bg-gray-950 min-h-screen flex flex-col">
    <div v-if="isLoading" class="flex items-center justify-center py-20 min-h-screen">
      <i class="pi pi-spin pi-spinner text-5xl text-blue-500"></i>
    </div>
    

    <div v-else-if="event?.id" class="flex-1">
      <div class="bg-gray-900 text-white py-8">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
          <div class="flex items-center justify-between mb-4">
            <div>
              <h1 class="text-3xl md:text-4xl font-bold">PROCESO DE COMPRA</h1>
              <div class="h-1 w-32 bg-blue-600 mt-2"></div>
            </div>
                <div class="text-right">
                    <p class="text-sm text-gray-400">Tiempo restante</p>

                    <vue-countdown
                    :time="10 * 60 * 1000"
                    v-slot="{ minutes, seconds }"
                    @end="onCountdownEnd"
                    >
                    <span class="text-xl font-bold">
                        {{ minutes }}:{{ seconds.toString().padStart(2, '0') }}
                    </span>
                    </vue-countdown>
                </div>
            </div>
        </div>
    </div>

      <div class="container mx-auto px-4 sm:px-6 lg:px-8 pt-6">
        <button 
          @click="goBack"
          class="flex items-center gap-2 text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 font-medium transition-colors mb-8"
        >
          <i class="pi pi-arrow-left"></i>
          <span>Volver a entradas</span>
        </button>
      </div>

      <div class="container mx-auto px-4 sm:px-6 lg:px-8 pb-12">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
          
          <div class="lg:col-span-2 space-y-6">
            
            <section class="bg-white dark:bg-gray-800 rounded-lg border border-slate-200 dark:border-gray-700 p-6">
              <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                <i class="pi pi-check text-green-600"></i>
                ENTREGA TICKETS
              </h2>
              <p class="text-sm text-slate-600 dark:text-slate-400 mb-4">Los tickets se enviarán a tu correo electrónico</p>
              <div class="bg-slate-50 dark:bg-gray-700 rounded-lg p-4">
                <div v-if="!editingEmail" class="flex items-center justify-between">
                  <p class="text-sm font-medium text-gray-900 dark:text-white">{{ userEmail }}</p>
                  <button 
                    @click="startEditingEmail"
                    class="text-xs text-blue-600 dark:text-blue-400 hover:underline font-medium transition-colors"
                  >
                    Editar
                  </button>
                </div>
                <div v-else class="space-y-3">
                  <input 
                    v-model="emailEdit" 
                    type="email"
                    placeholder="Ingresa tu correo electrónico"
                    class="w-full px-3 py-2 bg-white dark:bg-gray-600 border border-slate-300 dark:border-gray-500 rounded-lg text-gray-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:border-blue-500 dark:focus:border-blue-400"
                  />
                  <div class="flex gap-2">
                    <button 
                      @click="saveEmail"
                      class="flex-1 px-3 py-2 bg-blue-600 dark:bg-blue-500 text-white text-sm font-medium rounded-lg hover:bg-blue-700 dark:hover:bg-blue-600 transition-colors"
                    >
                      Guardar
                    </button>
                    <button 
                      @click="cancelEditEmail"
                      class="flex-1 px-3 py-2 bg-slate-300 dark:bg-gray-600 text-gray-900 dark:text-white text-sm font-medium rounded-lg hover:bg-slate-400 dark:hover:bg-gray-500 transition-colors"
                    >
                      Cancelar
                    </button>
                  </div>
                </div>
              </div>
            </section>

            <section class="bg-white dark:bg-gray-800 rounded-lg border border-slate-200 dark:border-gray-700 p-6">
              <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">EXTRAS DEL EVENTO</h2>
              <div class="space-y-4">
                <label v-for="extra in event?.extras || []" :key="extra.id" class="flex items-start gap-4 p-4 rounded-lg border border-slate-200 dark:border-gray-600 hover:border-blue-500 cursor-pointer transition-colors" :class="{ 'border-blue-500 bg-blue-50 dark:bg-blue-900/20': selectedExtras.includes(extra.id) }">
                  <input type="checkbox" class="mt-1" :value="extra.id" v-model.number="selectedExtras">
                  <div class="flex-1">
                    <p class="font-medium text-gray-900 dark:text-white">{{ extra.name }}</p>
                    <p class="text-sm text-slate-600 dark:text-slate-400">{{ extra.description }}</p>
                    <p class="text-sm font-semibold text-gray-900 dark:text-white mt-2">+ {{ parseFloat(extra.price).toFixed(2) }} €</p>
                  </div>
                </label>
              </div>
            </section>

            <section class="bg-white dark:bg-gray-800 rounded-lg border border-slate-200 dark:border-gray-700 p-6">
              <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">PROTECCIÓN DE COMPRA</h2>
              <div class="space-y-4">
                <label class="flex items-start gap-4 p-4 rounded-lg border border-slate-200 dark:border-gray-600 hover:border-blue-500 cursor-pointer transition-colors" :class="{ 'border-blue-500 bg-blue-50 dark:bg-blue-900/20': insuranceSelected }">
                  <input type="radio" name="insurance" class="mt-1" v-model="insuranceSelected" :value="true">
                  <div class="flex-1">
                    <p class="font-medium text-gray-900 dark:text-white">Proteger mi compra</p>
                    <p class="text-sm text-slate-600 dark:text-slate-400">Cubre cancelaciones y cambios de fecha</p>
                    <p class="text-sm font-semibold text-gray-900 dark:text-white mt-2">+ 12,00 €</p>
                  </div>
                </label>

                <label class="flex items-start gap-4 p-4 rounded-lg border border-slate-200 dark:border-gray-600 hover:border-blue-500 cursor-pointer transition-colors" :class="{ 'border-blue-500 bg-blue-50 dark:bg-blue-900/20': !insuranceSelected }">
                  <input type="radio" name="insurance" class="mt-1" v-model="insuranceSelected" :value="false">
                  <div class="flex-1">
                    <p class="font-medium text-gray-900 dark:text-white">No proteger mi compra</p>
                    <p class="text-sm text-slate-600 dark:text-slate-400">Sin cobertura adicional</p>
                  </div>
                </label>
              </div>
            </section>

            <section class="bg-white dark:bg-gray-800 rounded-lg border border-slate-200 dark:border-gray-700 p-6">
              <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">CONDICIONES DE COMPRA</h2>
              <label class="flex items-start gap-3 mb-6">
                <input type="checkbox" v-model="termsAccepted" @change="showTermsError = false">
                <span class="text-sm text-slate-600 dark:text-slate-400">
                  Confirmo que he leído y acepto los 
                  <a href="#" class="text-blue-600 dark:text-blue-400 hover:underline">Términos de Compra</a>
                  y la 
                  <a href="#" class="text-blue-600 dark:text-blue-400 hover:underline">Política de Privacidad</a>
                </span>
              </label>
              <div v-if="showTermsError" class="text-red-500 text-sm mb-4 font-medium">
                Es obligatorio aceptar los Términos de Compra y la Política de Privacidad
              </div>
              <Button 
                label="Finalizar Compra" 
                icon="pi pi-check"
                class="w-full !bg-green-600 !border-green-600 hover:!bg-green-700"
                size="large"
                @click="finalizarCompra"
              />
            </section>
          </div>


          <div class="lg:col-span-1">
            <div class="sticky top-6 bg-white dark:bg-gray-800 rounded-lg border border-slate-200 dark:border-gray-700 p-6">
              <div class="border-b border-slate-200 dark:border-gray-600 pb-4 mb-4">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">TOTAL</h3>
                <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ totalPriceCurrency }}</p>
              </div>
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

              <div class="mb-6">
                <h4 class="font-semibold text-gray-900 dark:text-white mb-3">EXTRAS</h4>
                <div class="space-y-2 text-sm">
                  <div v-for="extra in selectedExtrasData" :key="extra.id" class="text-slate-600 dark:text-slate-400">
                    <p>
                      <span class="font-medium">{{ extra.name }}</span>
                      <span class="text-gray-900 dark:text-white font-semibold float-right">{{ parseFloat(extra.price).toFixed(2) }} €</span>
                    </p>
                  </div>
                  <div v-if="insuranceSelected" class="text-slate-600 dark:text-slate-400">
                    <p>
                      <span class="font-medium">Protección de Compra</span>
                      <span class="text-gray-900 dark:text-white font-semibold float-right">12,00 €</span>
                    </p>
                  </div>
                  <div v-if="selectedExtrasData.length === 0 && !insuranceSelected" class="text-slate-500 dark:text-slate-400 italic">
                    Sin extras
                  </div>
                </div>
              </div>

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
import VueCountdown from '@chenfengyuan/vue-countdown';


const router = useRouter();
const route = useRoute();
const { event, isLoading, getPublicEvent } = useEvents();


const ticketQuantities = ref([0, 0, 0]);
const userEmail = ref('usuario@ejemplo.com');
const emailEdit = ref('');
const editingEmail = ref(false);
const insuranceSelected = ref(false);
const selectedExtras = ref([]);
const termsAccepted = ref(false);
const showTermsError = ref(false);

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

const onCountdownEnd = () => {
  alert('Ha ocurrido un error: el tiempo de compra ha terminado, redirigiendo a la pagina principal...');

  sessionStorage.removeItem('ticketQuantities');
  sessionStorage.removeItem('userEmail');
  sessionStorage.removeItem('selectedExtras');

  router.push({ name: 'home' }); 

};

const totalPrice = computed(() => {
  const ticketsTotal = ticketDetails.reduce((total, ticket, index) => {
    return total + (ticket.price * ticketQuantities.value[index]);
  }, 0);
  
  let extrasTotal = 0;
  const extrasData = event.value?.extras || [];
  selectedExtras.value.forEach(extraId => {
    const extra = extrasData.find(e => e.id === extraId);
    if (extra) {
      extrasTotal += parseFloat(extra.price);
    }
  });
  
  if (insuranceSelected.value) extrasTotal += 12;
  
  return ticketsTotal + extrasTotal;
});

const totalPriceCurrency = computed(() => {
  return totalPrice.value.toFixed(2) + ' €';
});

const selectedExtrasData = computed(() => {
  const extrasData = event.value?.extras || [];
  return extrasData.filter(extra => selectedExtras.value.includes(extra.id));
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
    if (!termsAccepted.value) {
      showTermsError.value = true;
      return;
    }

    const totalSeleccionadas = ticketQuantities.value.reduce((a, b) => a + b, 0);


    await new Promise(resolve => setTimeout(resolve, 800));

    sessionStorage.removeItem('ticketQuantities');
    sessionStorage.removeItem('userEmail');
    sessionStorage.removeItem('selectedExtras');

    alert('✅ ¡Compra completada exitosamente! Recibirás los detalles en tu correo.');

    router.push({ name: 'home' });
  } catch (error) {
    console.error('Error al finalizar compra:', error);
    alert('Error al procesar la compra. Por favor intenta de nuevo.');
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

const startEditingEmail = () => {
  emailEdit.value = userEmail.value;
  editingEmail.value = true;
};

const saveEmail = () => {
  if (emailEdit.value.trim()) {
    userEmail.value = emailEdit.value.trim();
    sessionStorage.setItem('userEmail', emailEdit.value.trim());
    editingEmail.value = false;
  }
};

const cancelEditEmail = () => {
  editingEmail.value = false;
  emailEdit.value = '';
};

onMounted(() => {
  const eventId = route.params.id;
  
  const savedTickets = sessionStorage.getItem('ticketQuantities');
  if (savedTickets) {
    try {
      ticketQuantities.value = JSON.parse(savedTickets);
    } catch (e) {
      console.log('Error parsing tickets:', e);
    }
  }
  
  const savedEmail = sessionStorage.getItem('userEmail');
  if (savedEmail) {
    userEmail.value = savedEmail;
  }

  const savedExtras = sessionStorage.getItem('selectedExtras');
  if (savedExtras) {
    try {
      selectedExtras.value = JSON.parse(savedExtras);
    } catch (e) {
      console.log('Error parsing extras:', e);
    }
  }

  if (eventId) {
    getPublicEvent(eventId);
  }
});
</script>
