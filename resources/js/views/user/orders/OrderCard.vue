<template>
  <div
    class="rounded-2xl border overflow-hidden transition-all duration-200 hover:shadow-lg"
    :class="[
      isDarkTheme ? 'bg-gray-900' : 'bg-white',
      past
        ? (isDarkTheme ? 'border-gray-700' : 'border-gray-200')
        : (isDarkTheme ? 'border-blue-900/50 hover:border-blue-800' : 'border-blue-100 hover:border-blue-200')
    ]"
  >
    <div class="flex flex-col sm:flex-row">

      <!-- Image -->
      <div class="relative sm:w-48 sm:flex-shrink-0 h-44 sm:h-auto overflow-hidden" :class="isDarkTheme ? 'bg-gray-800' : 'bg-gray-100'">
        <img
          v-if="order.event?.image"
          :src="`/images/${order.event.image}`"
          :alt="order.event?.title"
          class="w-full h-full object-cover"
          :class="past ? 'grayscale opacity-70' : ''"
        />
        <div v-else class="flex flex-col items-center justify-center h-full gap-2">
          <i class="pi pi-calendar text-4xl" :class="past ? (isDarkTheme ? 'text-gray-600' : 'text-gray-300') : (isDarkTheme ? 'text-blue-900' : 'text-blue-200')"></i>
        </div>

        <!-- Badge -->
        <div class="absolute top-3 left-3">
          <span
            class="text-xs font-semibold px-2.5 py-1 rounded-full backdrop-blur-sm"
            :class="past
              ? (isDarkTheme ? 'bg-gray-800/60 text-gray-200' : 'bg-gray-900/60 text-gray-100')
              : 'bg-blue-600 text-white'"
          >
            {{ past ? 'Pasado' : 'Próximo' }}
          </span>
        </div>
      </div>

      <!-- Content -->
      <div class="flex-1 p-5 flex flex-col justify-between gap-4">

        <!-- Top: title + date/location -->
        <div class="space-y-3">
          <div>
            <div class="flex items-start justify-between gap-2">
              <h3 class="font-bold text-lg leading-tight" :class="isDarkTheme ? 'text-white' : 'text-gray-900'">
                {{ order.event?.title }}
              </h3>
              <span class="text-lg font-bold whitespace-nowrap flex-shrink-0" :class="isDarkTheme ? 'text-blue-400' : 'text-blue-600'">
                {{ Number(order.total_price).toFixed(2) }} €
              </span>
            </div>
            <span v-if="order.event?.category?.name" class="inline-flex items-center gap-1 text-xs mt-1" :class="isDarkTheme ? 'text-gray-500' : 'text-gray-400'">
              <i class="pi pi-tag"></i>{{ order.event.category.name }}
            </span>
          </div>

          <div class="flex flex-wrap gap-x-4 gap-y-1 text-sm" :class="isDarkTheme ? 'text-gray-400' : 'text-gray-500'">
            <span class="flex items-center gap-1.5">
              <i class="pi pi-calendar text-blue-500"></i>
              {{ formatDate(order.event?.start_date) }}
            </span>
            <span v-if="order.event?.city" class="flex items-center gap-1.5">
              <i class="pi pi-map-marker text-blue-500"></i>
              {{ order.event.city }}<span v-if="order.event.venue">, {{ order.event.venue }}</span>
            </span>
          </div>
        </div>

        <!-- Bottom: tickets + meta -->
        <div class="border-t pt-3 flex flex-col sm:flex-row sm:items-end sm:justify-between gap-3" :class="isDarkTheme ? 'border-gray-700' : 'border-gray-100'">

          <!-- Tickets chips -->
          <div class="flex flex-wrap gap-2">
            <span
              v-for="(t, i) in order.tickets"
              :key="i"
              class="inline-flex items-center gap-1 text-xs px-2.5 py-1 rounded-full font-medium"
              :class="past
                ? (isDarkTheme ? 'bg-gray-800 text-gray-400' : 'bg-gray-100 text-gray-600')
                : (isDarkTheme ? 'bg-blue-900/30 text-blue-300' : 'bg-blue-50 text-blue-700')"
            >
              <i class="pi pi-ticket text-xs"></i>
              {{ t.label }} × {{ t.quantity }}
            </span>
            <span
              v-if="order.insurance"
              class="inline-flex items-center gap-1 text-xs px-2.5 py-1 rounded-full font-medium"
              :class="isDarkTheme ? 'bg-amber-900/20 text-amber-400' : 'bg-amber-50 text-amber-700'"
            >
              <i class="pi pi-shield text-xs"></i>
              Protección
            </span>
          </div>

          <!-- Order ID + date -->
          <p class="text-xs whitespace-nowrap" :class="isDarkTheme ? 'text-gray-500' : 'text-gray-400'">
            Pedido #{{ order.id }} · {{ formatDate(order.created_at) }}
          </p>
        </div>

      </div>
    </div>
  </div>
</template>

<script setup>
import { useLayout } from '@/composables/layout';

defineProps({
  order: { type: Object, required: true },
  past:  { type: Boolean, default: false },
});

const { isDarkTheme } = useLayout();

const formatDate = (dateStr) => {
  if (!dateStr) return '—';
  return new Date(dateStr).toLocaleDateString('es-ES', {
    day: 'numeric', month: 'short', year: 'numeric',
  });
};
</script>
