<template>
  <div class="registrations-page p-4">
    <div class="flex justify-between items-center mb-4 gap-2">
      <h2 class="text-xl font-semibold">Inscripciones</h2>
      <Button label="Actualizar" icon="pi pi-refresh" size="small" outlined severity="secondary"
              :loading="isLoading" @click="refresh" />
    </div>

    <div class="flex flex-wrap gap-3 mb-4">
      <span class="p-input-icon-left">
        <InputText v-model="filters.q" placeholder="Buscar usuario..." class="w-64" @keyup.enter="refresh" />
      </span>
      <Select v-model="filters.event_id" :options="eventOptions" optionLabel="title" optionValue="id"
              placeholder="Filtrar por evento" showClear class="w-72" @change="refresh" filter />
      <Select v-model="filters.status" :options="statusOptions" optionLabel="label" optionValue="value"
              placeholder="Estado" showClear class="w-48" @change="refresh" />
      <Button label="Aplicar" icon="pi pi-filter" @click="refresh" />
    </div>

    <DataTable :value="rows" data-key="id" striped-rows :loading="isLoading"
               :lazy="true" :paginator="true"
               :rows="pagination.per_page"
               :total-records="pagination.total"
               :first="(pagination.current_page - 1) * pagination.per_page"
               :rows-per-page-options="[10, 15, 25, 50]"
               @page="onPage">
      <Column field="id" header="ID" sortable />
      <Column header="Usuario">
        <template #body="{ data }">
          <div class="flex flex-col">
            <span class="font-semibold">{{ data.user?.name }} {{ data.user?.surname1 }}</span>
            <span class="text-xs text-slate-500">{{ data.user?.email }}</span>
          </div>
        </template>
      </Column>
      <Column header="Evento">
        <template #body="{ data }">
          <div class="flex flex-col">
            <span class="font-medium">{{ data.event?.title }}</span>
            <span class="text-xs text-slate-500">
              {{ data.event?.venue_relation?.name || '-' }}
              <span v-if="data.event?.venue_relation?.city">· {{ data.event.venue_relation.city }}</span>
            </span>
          </div>
        </template>
      </Column>
      <Column header="Estado" sortable field="status">
        <template #body="{ data }">
          <Tag :value="data.status" :severity="statusSeverity(data.status)" />
        </template>
      </Column>
      <Column field="created_at" header="Fecha inscripción" sortable>
        <template #body="{ data }">{{ formatDate(data.created_at) }}</template>
      </Column>
    </DataTable>
  </div>
</template>

<script setup>
import { onMounted, reactive, ref } from 'vue';
import axios from 'axios';
import Tag from 'primevue/tag';
import InputText from 'primevue/inputtext';
import Select from 'primevue/select';
import { useToast } from '@/composables/useToast';

const toast = useToast();
const rows = ref([]);
const eventOptions = ref([]);
const isLoading = ref(false);
const pagination = reactive({ total: 0, per_page: 15, current_page: 1, last_page: 1 });

const filters = reactive({ q: '', event_id: null, status: null });
const statusOptions = [
  { label: 'Inscrito', value: 'inscrito' },
  { label: 'Cancelado', value: 'cancelado' },
  { label: 'Asistido', value: 'asistido' },
];

const fetch = async (page = 1) => {
  isLoading.value = true;
  try {
    const params = { page, per_page: pagination.per_page };
    if (filters.q) params.q = filters.q;
    if (filters.event_id) params.event_id = filters.event_id;
    if (filters.status) params.status = filters.status;
    const { data } = await axios.get('/api/admin/registrations', { params });
    rows.value = data.data;
    pagination.total = data.total;
    pagination.per_page = data.per_page;
    pagination.current_page = data.current_page;
    pagination.last_page = data.last_page;
  } catch {
    toast.error('Error cargando inscripciones');
  } finally {
    isLoading.value = false;
  }
};

const refresh = () => fetch(1);

const onPage = (event) => {
  pagination.per_page = event.rows;
  fetch(event.page + 1);
};

const loadEvents = async () => {
  try {
    const { data } = await axios.get('/api/events');
    const list = data?.data ?? data ?? [];
    eventOptions.value = list.map((e) => ({ id: e.id, title: e.title }));
  } catch { toast.error("Error cargando eventos"); }
};

const statusSeverity = (s) => {
  if (s === 'inscrito') return 'info';
  if (s === 'asistido') return 'success';
  if (s === 'cancelado') return 'secondary';
  return null;
};

const formatDate = (d) => d ? new Date(d).toLocaleString('es-ES') : '-';

onMounted(async () => { await loadEvents(); await fetch(1); });
</script>
