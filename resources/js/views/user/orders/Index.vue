<template>
  <div class="min-h-screen pb-16" :class="isDarkTheme ? 'bg-gray-950' : 'bg-gray-50'">

    <!-- Page hero -->
    <div class="border-b" :class="isDarkTheme ? 'bg-gray-900 border-gray-800' : 'bg-white border-gray-200'">
      <div class="max-w-4xl mx-auto px-4 sm:px-6 py-10">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
          <div>
            <div class="flex items-center gap-3 mb-1">
              <div class="w-10 h-10 rounded-xl bg-blue-600 flex items-center justify-center">
                <i class="pi pi-ticket text-white text-lg"></i>
              </div>
              <h1 class="text-2xl font-bold" :class="isDarkTheme ? 'text-white' : 'text-gray-900'">Mis Entradas</h1>
            </div>
            <p class="text-sm" :class="isDarkTheme ? 'text-gray-400' : 'text-gray-500'">
              Próximos eventos a los que asistirás
            </p>
          </div>
          <router-link to="/orders/past-events">
            <Button label="Eventos pasados" icon="pi pi-history"  severity="info" outlined size="small" />
          </router-link>
        </div>
      </div>
    </div>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 pt-8">

      <!-- Banner éxito -->
      <div
        v-if="showSuccess"
        class="flex items-start gap-3 p-4 mb-6 border rounded-2xl"
        :class="isDarkTheme ? 'bg-green-900/20 border-green-800 text-green-300' : 'bg-green-50 border-green-200 text-green-800'"
      >
        <i class="pi pi-check-circle text-2xl text-green-500 mt-0.5 flex-shrink-0"></i>
        <div>
          <p class="font-semibold">¡Compra completada con éxito!</p>
          <p class="text-sm opacity-80">Recibirás los detalles en tu correo electrónico.</p>
        </div>
      </div>

      <!-- Loading -->
      <div v-if="isLoading" class="flex flex-col items-center justify-center py-24 gap-4">
        <i class="pi pi-spin pi-spinner text-4xl text-blue-500"></i>
        <p class="text-sm" :class="isDarkTheme ? 'text-gray-500' : 'text-gray-400'">Cargando tus entradas...</p>
      </div>

      <!-- Empty state -->
      <div v-else-if="orders.length === 0" class="flex flex-col items-center py-24 text-center">
        <div class="w-20 h-20 rounded-2xl flex items-center justify-center mb-5" :class="isDarkTheme ? 'bg-gray-800' : 'bg-gray-100'">
          <i class="pi pi-ticket text-4xl" :class="isDarkTheme ? 'text-gray-600' : 'text-gray-300'"></i>
        </div>
        <h2 class="text-lg font-semibold mb-2" :class="isDarkTheme ? 'text-gray-300' : 'text-gray-700'">No tienes entradas próximas</h2>
        <p class="text-sm mb-6 max-w-xs" :class="isDarkTheme ? 'text-gray-500' : 'text-gray-400'">Explora nuestra cartelera y consigue entradas para los próximos eventos.</p>
        <router-link to="/events">
          <Button label="Explorar eventos" icon="pi pi-search" />
        </router-link>
      </div>

      <!-- Orders list -->
      <div v-else class="space-y-4">
        <OrderCard v-for="order in orders" :key="order.id" :order="order" />
      </div>

    </div>
  </div>
</template>

<script setup>
import { onMounted, computed } from 'vue';
import { useRoute } from 'vue-router';
import useOrders from '@/composables/orders';
import { useLayout } from '@/composables/layout';
import OrderCard from './OrderCard.vue';

const route = useRoute();
const { orders, isLoading, getOrders } = useOrders();
const { isDarkTheme, setDefaultMode } = useLayout();

const showSuccess = computed(() => route.query.success === '1');

onMounted(() => {
  setDefaultMode();
  getOrders('upcoming');
});
</script>
