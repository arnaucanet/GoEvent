
<template>
  <div class="compras-page min-h-screen flex flex-col" :class="isDarkTheme ? 'bg-gray-950' : 'bg-white'">
    <div v-if="isLoading" class="flex items-center justify-center py-20 min-h-screen">
      <i class="pi pi-spin pi-spinner text-5xl text-blue-500"></i>
    </div>
    

    <div v-else-if="event?.id" class="flex-1">
      <div class="text-white py-8" :class="isDarkTheme ? 'bg-gray-900' : 'bg-blue-700'">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
          <div class="flex items-center justify-between mb-4">
            <div>
              <h1 class="text-3xl md:text-4xl font-bold">PROCESO DE COMPRA</h1>
              <div class="h-1 w-32 bg-blue-600 mt-2"></div>
            </div>
                <div class="text-right">
                    <p class="text-sm" :class="isDarkTheme ? 'text-gray-400' : 'text-blue-100'">Tiempo restante</p>

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
          class="flex items-center gap-2 font-medium transition-colors mb-8"
          :class="isDarkTheme ? 'text-blue-400 hover:text-blue-300' : 'text-blue-600 hover:text-blue-700'"
        >
          <i class="pi pi-arrow-left"></i>
          <span>Volver a entradas</span>
        </button>
      </div>

      <div class="container mx-auto px-4 sm:px-6 lg:px-8 pb-12">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
          
          <div class="lg:col-span-2 space-y-6">
            
            <section class="rounded-lg border p-6" :class="isDarkTheme ? 'bg-gray-800 border-gray-700' : 'bg-white border-slate-200'">
              <h2 class="text-xl font-bold mb-4 flex items-center gap-2" :class="isDarkTheme ? 'text-white' : 'text-gray-900'">
                <i class="pi pi-check text-green-600"></i>
                ENTREGA TICKETS
              </h2>
              <p class="text-sm mb-4" :class="isDarkTheme ? 'text-slate-400' : 'text-slate-600'">Los tickets se enviarán a tu correo electrónico</p>
              <div class="rounded-lg p-4" :class="isDarkTheme ? 'bg-gray-700' : 'bg-slate-50'">
                <div v-if="!editingEmail" class="flex items-center justify-between">
                  <p class="text-sm font-medium" :class="isDarkTheme ? 'text-white' : 'text-gray-900'">{{ userEmail }}</p>
                  <button 
                    @click="startEditingEmail"
                    class="text-xs hover:underline font-medium transition-colors"
                    :class="isDarkTheme ? 'text-blue-400' : 'text-blue-600'"
                  >
                    Editar
                  </button>
                </div>
                <div v-else class="space-y-3">
                  <input 
                    v-model="emailEdit" 
                    type="email"
                    placeholder="Ingresa tu correo electrónico"
                    class="w-full px-3 py-2 border rounded-lg placeholder-slate-400 focus:outline-none focus:border-blue-500"
                    :class="isDarkTheme ? 'bg-gray-600 border-gray-500 text-white placeholder-slate-500' : 'bg-white border-slate-300 text-gray-900'"
                  />
                  <div class="flex gap-2">
                    <button 
                      @click="saveEmail"
                      class="flex-1 px-3 py-2 text-white text-sm font-medium rounded-lg transition-colors"
                      :class="isDarkTheme ? 'bg-blue-500 hover:bg-blue-600' : 'bg-blue-600 hover:bg-blue-700'"
                    >
                      Guardar
                    </button>
                    <button 
                      @click="cancelEditEmail"
                      class="flex-1 px-3 py-2 text-sm font-medium rounded-lg transition-colors"
                      :class="isDarkTheme ? 'bg-gray-600 hover:bg-gray-500 text-white' : 'bg-slate-300 hover:bg-slate-400 text-gray-900'"
                    >
                      Cancelar
                    </button>
                  </div>
                </div>
              </div>
            </section>

            <section class="rounded-lg border p-6" :class="isDarkTheme ? 'bg-gray-800 border-gray-700' : 'bg-white border-slate-200'">
              <h2 class="text-xl font-bold mb-4" :class="isDarkTheme ? 'text-white' : 'text-gray-900'">EXTRAS DEL EVENTO</h2>
              <div class="space-y-4">
                <label
                  v-for="extra in event?.extras || []"
                  :key="extra.id"
                  class="flex items-start gap-4 p-4 rounded-lg border hover:border-blue-500 cursor-pointer transition-colors"
                  :class="[
                    isDarkTheme ? 'border-gray-600' : 'border-slate-200',
                    selectedExtras.includes(extra.id)
                      ? (isDarkTheme ? 'border-blue-500 bg-blue-900/20' : 'border-blue-500 bg-blue-50')
                      : ''
                  ]"
                >
                  <input type="checkbox" class="mt-1" :value="extra.id" v-model.number="selectedExtras">
                  <div class="flex-1">
                    <p class="font-medium" :class="isDarkTheme ? 'text-white' : 'text-gray-900'">{{ extra.name }}</p>
                    <p class="text-sm" :class="isDarkTheme ? 'text-slate-400' : 'text-slate-600'">{{ extra.description }}</p>
                    <p class="text-sm font-semibold mt-2" :class="isDarkTheme ? 'text-white' : 'text-gray-900'">+ {{ parseFloat(extra.price).toFixed(2) }} €</p>
                  </div>
                </label>
              </div>
            </section>

            <section class="rounded-lg border p-6" :class="isDarkTheme ? 'bg-gray-800 border-gray-700' : 'bg-white border-slate-200'">
              <h2 class="text-xl font-bold mb-4" :class="isDarkTheme ? 'text-white' : 'text-gray-900'">PROTECCIÓN DE COMPRA</h2>
              <div class="space-y-4">
                <label
                  class="flex items-start gap-4 p-4 rounded-lg border hover:border-blue-500 cursor-pointer transition-colors"
                  :class="[
                    isDarkTheme ? 'border-gray-600' : 'border-slate-200',
                    insuranceSelected
                      ? (isDarkTheme ? 'border-blue-500 bg-blue-900/20' : 'border-blue-500 bg-blue-50')
                      : ''
                  ]"
                >
                  <input type="radio" name="insurance" class="mt-1" v-model="insuranceSelected" :value="true">
                  <div class="flex-1">
                    <p class="font-medium" :class="isDarkTheme ? 'text-white' : 'text-gray-900'">Proteger mi compra</p>
                    <p class="text-sm" :class="isDarkTheme ? 'text-slate-400' : 'text-slate-600'">Cubre cancelaciones y cambios de fecha</p>
                    <p class="text-sm font-semibold mt-2" :class="isDarkTheme ? 'text-white' : 'text-gray-900'">+ 12,00 €</p>
                  </div>
                </label>

                <label
                  class="flex items-start gap-4 p-4 rounded-lg border hover:border-blue-500 cursor-pointer transition-colors"
                  :class="[
                    isDarkTheme ? 'border-gray-600' : 'border-slate-200',
                    !insuranceSelected
                      ? (isDarkTheme ? 'border-blue-500 bg-blue-900/20' : 'border-blue-500 bg-blue-50')
                      : ''
                  ]"
                >
                  <input type="radio" name="insurance" class="mt-1" v-model="insuranceSelected" :value="false">
                  <div class="flex-1">
                    <p class="font-medium" :class="isDarkTheme ? 'text-white' : 'text-gray-900'">No proteger mi compra</p>
                    <p class="text-sm" :class="isDarkTheme ? 'text-slate-400' : 'text-slate-600'">Sin cobertura adicional</p>
                  </div>
                </label>
              </div>
            </section>

            <section class="rounded-lg border p-6" :class="isDarkTheme ? 'bg-gray-800 border-gray-700' : 'bg-white border-slate-200'">
              <h2 class="text-xl font-bold mb-4" :class="isDarkTheme ? 'text-white' : 'text-gray-900'">CONDICIONES DE COMPRA</h2>
              <label class="flex items-start gap-3 mb-6">
                <input type="checkbox" v-model="termsAccepted" @change="showTermsError = false">
                <span class="text-sm" :class="isDarkTheme ? 'text-slate-400' : 'text-slate-600'">
                  Confirmo que he leído y acepto los 
                  <a href="#" class="hover:underline" :class="isDarkTheme ? 'text-blue-400' : 'text-blue-600'">Términos de Compra</a>
                  y la 
                  <a href="#" class="hover:underline" :class="isDarkTheme ? 'text-blue-400' : 'text-blue-600'">Política de Privacidad</a>
                </span>
              </label>
              <div v-if="showTermsError" class="text-red-500 text-sm mb-4 font-medium">
                Es obligatorio aceptar los Términos de Compra y la Política de Privacidad
              </div>
              <Button
                :label="isSubmitting ? 'Procesando...' : 'Finalizar Compra'"
                :icon="isSubmitting ? 'pi pi-spin pi-spinner' : 'pi pi-check'"
                :disabled="isSubmitting"
                class="w-full !bg-green-600 !border-green-600 hover:!bg-green-700 !text-white disabled:!text-white"
                size="large"
                :pt="{
                  label: { class: '!text-white' },
                  icon: { class: '!text-white' }
                }"
                @click="finalizarCompra"
              />
            </section>
          </div>


          <div class="lg:col-span-1">
            <div class="sticky top-6 rounded-lg border p-6" :class="isDarkTheme ? 'bg-gray-800 border-gray-700' : 'bg-white border-slate-200'">
              <div class="border-b pb-4 mb-4" :class="isDarkTheme ? 'border-gray-600' : 'border-slate-200'">
                <h3 class="text-lg font-bold mb-2" :class="isDarkTheme ? 'text-white' : 'text-gray-900'">TOTAL</h3>
                <p class="text-3xl font-bold" :class="isDarkTheme ? 'text-white' : 'text-gray-900'">{{ totalPriceCurrency }}</p>
              </div>
              <div class="mb-6">
                <h4 class="font-semibold mb-3" :class="isDarkTheme ? 'text-white' : 'text-gray-900'">ENTRADAS</h4>
                <div class="space-y-2 text-sm">
                  <div v-for="(item, index) in ticketsSummary" :key="index">
                    <div v-if="item && item.quantity > 0">
                        <p :class="isDarkTheme ? 'text-slate-400' : 'text-slate-600'">
                        <span class="font-medium">{{ item.label }}</span>
                        <br>
                        <span class="text-xs">{{ item.price }} € × {{ item.quantity }}</span>
                        <span class="font-semibold float-right" :class="isDarkTheme ? 'text-white' : 'text-gray-900'">
                            {{ (item.price * item.quantity).toFixed(2) }} €
                        </span>
                        </p>
                    </div>
                    </div>
                  <div v-if="ticketsSummary.every(item => item.quantity === 0)" class="italic" :class="isDarkTheme ? 'text-slate-400' : 'text-slate-500'">
                    No hay entradas seleccionadas
                  </div>
                </div>
              </div>

              <div class="mb-6">
                <h4 class="font-semibold mb-3" :class="isDarkTheme ? 'text-white' : 'text-gray-900'">EXTRAS</h4>
                <div class="space-y-2 text-sm">
                  <div v-for="extra in selectedExtrasData" :key="extra.id" :class="isDarkTheme ? 'text-slate-400' : 'text-slate-600'">
                    <p>
                      <span class="font-medium">{{ extra.name }}</span>
                      <span class="font-semibold float-right" :class="isDarkTheme ? 'text-white' : 'text-gray-900'">{{ parseFloat(extra.price).toFixed(2) }} €</span>
                    </p>
                  </div>
                  <div v-if="insuranceSelected" :class="isDarkTheme ? 'text-slate-400' : 'text-slate-600'">
                    <p>
                      <span class="font-medium">Protección de Compra</span>
                      <span class="font-semibold float-right" :class="isDarkTheme ? 'text-white' : 'text-gray-900'">12,00 €</span>
                    </p>
                  </div>
                  <div v-if="selectedExtrasData.length === 0 && !insuranceSelected" class="italic" :class="isDarkTheme ? 'text-slate-400' : 'text-slate-500'">
                    Sin extras
                  </div>
                </div>
              </div>

              <div class="rounded-lg p-4" :class="isDarkTheme ? 'bg-gray-700' : 'bg-slate-50'">
                <h4 class="font-semibold mb-3" :class="isDarkTheme ? 'text-white' : 'text-gray-900'">EVENTO</h4>
                <div class="space-y-2 text-sm">
                  <div class="flex items-start gap-2">
                    <i class="pi pi-calendar flex-shrink-0 mt-1" :class="isDarkTheme ? 'text-blue-400' : 'text-blue-600'"></i>
                    <span :class="isDarkTheme ? 'text-slate-400' : 'text-slate-600'">{{ eventDate }}</span>
                  </div>
                  <div class="flex items-start gap-2">
                    <i class="pi pi-map-marker flex-shrink-0 mt-1" :class="isDarkTheme ? 'text-blue-400' : 'text-blue-600'"></i>
                    <span :class="isDarkTheme ? 'text-slate-400' : 'text-slate-600'">{{ eventLocation }}</span>
                  </div>
                </div>
              </div>

              <div class="mt-4 rounded-lg overflow-hidden flex items-center justify-center min-h-[200px]" :class="isDarkTheme ? 'bg-gray-600' : 'bg-slate-100'">
                <img 
                  v-if="event.image" 
                  :src="`/images/${event.image}`" 
                  :alt="event.title"
                  class="w-full h-full object-cover"
                >
                <div v-else class="text-center">
                  <i class="pi pi-image text-4xl mb-2" :class="isDarkTheme ? 'text-slate-500' : 'text-slate-400'"></i>
                  <p class="text-sm" :class="isDarkTheme ? 'text-slate-400' : 'text-slate-600'">Mapa de Asientos</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div v-else class="flex-1 container mx-auto px-4 sm:px-6 lg:px-8 py-20 text-center">
      <i class="pi pi-inbox text-6xl mb-4" :class="isDarkTheme ? 'text-slate-600' : 'text-slate-300'"></i>
      <p class="text-lg mb-6" :class="isDarkTheme ? 'text-slate-400' : 'text-slate-600'">Evento no encontrado</p>
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
import useOrders from '@/composables/orders';
import { useLayout } from '@/composables/layout';
import VueCountdown from '@chenfengyuan/vue-countdown';


const router = useRouter();
const route = useRoute();
const { event, isLoading, getPublicEvent } = useEvents();
const { createOrder } = useOrders();
const { isDarkTheme, setDefaultMode } = useLayout();
const isSubmitting = ref(false);

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
  if (!termsAccepted.value) {
    showTermsError.value = true;
    return;
  }

  const totalSeleccionadas = ticketQuantities.value.reduce((a, b) => a + b, 0);
  if (totalSeleccionadas === 0) {
    alert('Debes seleccionar al menos una entrada.');
    return;
  }

  isSubmitting.value = true;
  try {
    await createOrder({
      event_id:    Number(route.params.id),
      tickets:     ticketsSummary.value.filter(t => t.quantity > 0),
      extras:      selectedExtrasData.value,
      insurance:   insuranceSelected.value,
      email:       userEmail.value,
      total_price: totalPrice.value,
    });

    sessionStorage.removeItem('ticketQuantities');
    sessionStorage.removeItem('userEmail');
    sessionStorage.removeItem('selectedExtras');

    router.push({ name: 'user.orders', query: { success: '1' } });
  } catch (err) {
    console.error('Error al finalizar compra:', err);
  } finally {
    isSubmitting.value = false;
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
  window.scrollTo(0, 0);
  
  setDefaultMode();
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
