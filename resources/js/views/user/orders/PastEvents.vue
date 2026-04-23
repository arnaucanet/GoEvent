<template>
  <div class="min-h-screen pb-16" :class="isDarkTheme ? 'bg-gray-950' : 'bg-gray-50'">

    <!-- Page hero -->
    <div class="border-b" :class="isDarkTheme ? 'bg-gray-900 border-gray-800' : 'bg-white border-gray-200'">
      <div class="max-w-4xl mx-auto px-4 sm:px-6 py-10">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
          <div>
            <div class="flex items-center gap-3 mb-1">
              <div class="w-10 h-10 rounded-xl bg-gray-500 flex items-center justify-center">
                <i class="pi pi-history text-white text-lg"></i>
              </div>
              <h1 class="text-2xl font-bold" :class="isDarkTheme ? 'text-white' : 'text-gray-900'">Eventos Pasados</h1>
            </div>
            <p class="text-sm" :class="isDarkTheme ? 'text-gray-400' : 'text-gray-500'">
              Historial de eventos a los que has asistido
            </p>
          </div>
          <router-link to="/orders">
            <Button label="Próximos eventos" icon="pi pi-ticket" severity="info" outlined size="small" />
          </router-link>
        </div>
      </div>
    </div>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 pt-8">

      <!-- Loading -->
      <div v-if="isLoading" class="flex flex-col items-center justify-center py-24 gap-4">
        <i class="pi pi-spin pi-spinner text-4xl text-blue-500"></i>
        <p class="text-sm" :class="isDarkTheme ? 'text-gray-500' : 'text-gray-400'">Cargando historial...</p>
      </div>

      <!-- Empty state -->
      <div v-else-if="orders.length === 0" class="flex flex-col items-center py-24 text-center">
        <div class="w-20 h-20 rounded-2xl flex items-center justify-center mb-5" :class="isDarkTheme ? 'bg-gray-800' : 'bg-gray-100'">
          <i class="pi pi-history text-4xl" :class="isDarkTheme ? 'text-gray-600' : 'text-gray-300'"></i>
        </div>
        <h2 class="text-lg font-semibold mb-2" :class="isDarkTheme ? 'text-gray-300' : 'text-gray-700'">Sin historial de eventos</h2>
        <p class="text-sm max-w-xs" :class="isDarkTheme ? 'text-gray-500' : 'text-gray-400'">Los eventos a los que hayas asistido aparecerán aquí.</p>
      </div>

      <!-- Orders list -->
      <div v-else class="space-y-4">
        <OrderCard v-for="order in orders" :key="order.id" :order="order" :past="true" />
      </div>

    </div>
  </div>
</template>

<script setup>
import { onMounted } from 'vue';
import useOrders from '@/composables/orders';
import { useLayout } from '@/composables/layout';
import OrderCard from './OrderCard.vue';

const { orders, isLoading, getOrders } = useOrders();
const { isDarkTheme, setDefaultMode } = useLayout();

onMounted(() => {
  setDefaultMode();
  getOrders('past');
});
</script>
