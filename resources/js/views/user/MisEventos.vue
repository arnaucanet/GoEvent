<template>
  <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-10"
       :class="isDarkTheme ? 'text-slate-100' : 'text-slate-900'">
    <h1 class="text-3xl font-bold mb-2">Mis Eventos</h1>
    <p class="mb-8" :class="isDarkTheme ? 'text-slate-300' : 'text-slate-600'">
      Tus inscripciones y favoritos.
    </p>

    <TabView>
      <TabPanel header="Inscripciones">
        <div v-if="loadingRegistrations" class="py-10 text-center">
          <i class="pi pi-spin pi-spinner text-3xl text-blue-500"></i>
        </div>
        <div v-else-if="registrations.length === 0" class="py-10 text-center">
          <i class="pi pi-inbox text-4xl text-slate-400"></i>
          <p class="mt-3" :class="isDarkTheme ? 'text-slate-300' : 'text-slate-600'">
            Aún no estás inscrito a ningún evento.
          </p>
        </div>
        <div v-else class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <article
            v-for="reg in registrations"
            :key="'reg-' + reg.id"
            class="border rounded-xl p-4 shadow-sm flex flex-col gap-2"
            :class="isDarkTheme ? 'bg-slate-800 border-slate-700' : 'bg-white border-slate-200'"
          >
            <div class="flex items-center justify-between">
              <h3 class="font-bold text-lg">{{ reg.title }}</h3>
              <span class="text-xs px-2 py-1 rounded-full font-semibold"
                    :class="statusClasses(reg.pivot?.status)">
                {{ reg.pivot?.status }}
              </span>
            </div>
            <p class="text-sm" :class="isDarkTheme ? 'text-slate-300' : 'text-slate-600'">
              <i class="pi pi-calendar mr-1"></i>{{ formatDate(reg.start_date) }}
            </p>
            <p class="text-sm" :class="isDarkTheme ? 'text-slate-300' : 'text-slate-600'">
              <i class="pi pi-map-marker mr-1"></i>{{ reg.venue_relation?.name || reg.city || '-' }}
            </p>
            <div class="flex gap-2 mt-auto pt-2">
              <Button label="Ver" size="small" outlined class="!text-white !bg-blue-400 !border-blue-400 hover:!bg-blue-500"
                      @click="router.push({ name: 'events.detail', params: { id: reg.id } })" />
              <Button
                v-if="reg.pivot?.status === 'inscrito'"
                label="Cancelar"
                size="small"
                severity="secondary"
                outlined
                @click="onCancel(reg.id)"
              />
            </div>
          </article>
        </div>
      </TabPanel>

      <TabPanel header="Favoritos">
        <div v-if="loadingFavorites" class="py-10 text-center">
          <i class="pi pi-spin pi-spinner text-3xl text-blue-500"></i>
        </div>
        <div v-else-if="favorites.length === 0" class="py-10 text-center">
          <i class="pi pi-heart text-4xl text-slate-400"></i>
          <p class="mt-3" :class="isDarkTheme ? 'text-slate-300' : 'text-slate-600'">
            Aún no has marcado ningún evento como favorito.
          </p>
        </div>
        <div v-else class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <article
            v-for="fav in favorites"
            :key="'fav-' + fav.id"
            class="border rounded-xl p-4 shadow-sm flex flex-col gap-2"
            :class="isDarkTheme ? 'bg-slate-800 border-slate-700' : 'bg-white border-slate-200'"
          >
            <div class="flex items-center justify-between">
              <h3 class="font-bold text-lg">{{ fav.title }}</h3>
              <i class="pi pi-heart-fill text-blue-500"></i>
            </div>
            <p class="text-sm" :class="isDarkTheme ? 'text-slate-300' : 'text-slate-600'">
              <i class="pi pi-calendar mr-1"></i>{{ formatDate(fav.start_date) }}
            </p>
            <p class="text-sm" :class="isDarkTheme ? 'text-slate-300' : 'text-slate-600'">
              <i class="pi pi-map-marker mr-1"></i>{{ fav.venue_relation?.name || fav.city || '-' }}
            </p>
            <div class="flex gap-2 mt-auto pt-2">
              <Button label="Ver" size="small" outlined class="!text-white !bg-blue-400 !border-blue-400 hover:!bg-blue-500"
                      @click="router.push({ name: 'events.detail', params: { id: fav.id } })" />
              <Button label="Quitar" size="small" severity="secondary" outlined
                      @click="onUnfavorite(fav.id)" />
            </div>
          </article>
        </div>
      </TabPanel>
    </TabView>
  </div>
</template>

<script setup>
import { onMounted, ref } from 'vue';
import { useRouter } from 'vue-router';
import TabView from 'primevue/tabview';
import TabPanel from 'primevue/tabpanel';
import Button from 'primevue/button';
import useEventActions from '@/composables/eventActions';
import { useLayout } from '@/composables/layout';

const router = useRouter();
const { isDarkTheme } = useLayout();
const { fetchFavorites, fetchRegistrations, toggleFavorite, cancelRegistration } = useEventActions();

const favorites = ref([]);
const registrations = ref([]);
const loadingFavorites = ref(false);
const loadingRegistrations = ref(false);

const loadFavorites = async () => {
    loadingFavorites.value = true;
    try {
        const data = await fetchFavorites();
        favorites.value = data?.data ?? data ?? [];
    } finally {
        loadingFavorites.value = false;
    }
};

const loadRegistrations = async () => {
    loadingRegistrations.value = true;
    try {
        const data = await fetchRegistrations();
        registrations.value = data?.data ?? data ?? [];
    } finally {
        loadingRegistrations.value = false;
    }
};

const onUnfavorite = async (id) => {
    await toggleFavorite(id);
    favorites.value = favorites.value.filter((e) => e.id !== id);
};

const onCancel = async (id) => {
    const ok = await cancelRegistration(id);
    if (ok) {
        const target = registrations.value.find((r) => r.id === id);
        if (target?.pivot) target.pivot.status = 'cancelado';
    }
};

const formatDate = (dateStr) => {
    if (!dateStr) return '-';
    return new Date(dateStr).toLocaleDateString('es-ES', {
        year: 'numeric', month: 'long', day: 'numeric',
    });
};

const statusClasses = (status) => {
    if (status === 'inscrito') return 'bg-blue-100 text-blue-800';
    if (status === 'asistido') return 'bg-sky-100 text-sky-800';
    if (status === 'cancelado') return 'bg-slate-200 text-slate-700';
    return 'bg-slate-100 text-slate-800';
};

onMounted(() => {
    loadRegistrations();
    loadFavorites();
});
</script>
