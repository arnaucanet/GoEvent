<template>
  <div class="events-page p-4">
    <div class="flex justify-between items-center mb-4">
      <h2 class="text-xl font-semibold">Gestión de Eventos</h2>
      
       <Button
        label="Actualizar"
        icon="pi pi-refresh"
        size="small"
        outlined
        severity="secondary"
        :loading="isLoading"
        @click="refreshEvents"
      />
      <Button
        v-if="can('events-create')"
        label="Nuevo Evento"
        icon="pi pi-plus"
        severity="primary"
        @click="router.push({ name: 'events.create' })"
      />
    </div>

    <DataTable
      :value="events"
      data-key="id"
      striped-rows
      :loading="isLoading"
      :paginator="true"
      :rows="10"
    >
      <!-- ID -->
      <Column field="id" header="ID" sortable />

      <!-- Title -->
      <Column field="title" header="Titulo" sortable />

      <!-- Description -->
      <Column field="description" header="Descripción" sortable />

      <!-- Created At -->
      <Column field="created_at" header="Creado" sortable>
          <template #body="slotProps">
              {{ formatDate(slotProps.data.created_at) }}
          </template>
      </Column>

      <Column field="capacity" header="Capacity" sortable></Column>



      <column  field="featured" header="featured" sortable></column>
      


      
      <Column field="status" header="Status" sortable></Column>



      <!-- Acciones -->
      <Column header="Acciones">
        <template #body="{ data }">
          <Button
            v-if="can('events-edit')"
            icon="pi pi-pencil"
            text
            size="small"
            @click="router.push({ name: 'events.edit', params: { id: data.id } })"
          />
          <Button
            v-if="can('events-delete')"
            icon="pi pi-trash"
            text
            severity="danger"
            size="small"
            class="ml-2"
            @click="confirmDeleteEvent(data.id)"
          />
        </template>
      </Column>
    </DataTable>

  </div>
</template>

<script setup>
import { ref, reactive, computed, onMounted } from 'vue';
import { useToast } from '@/composables/useToast';
import { useAbility } from '@casl/vue'
import Select from 'primevue/select';
import useEvents from '@/composables/events';
import { useRouter } from "vue-router";

const { can } = useAbility()




const {
  events,
  event,
  getEvents,
  createEvent,
  updateEvent,
  destroyEvent,
  resetEvent,
  hasError,
  getError,
  isLoading
} = useEvents();

const router = useRouter()

const dialogOpen = ref(false);
const dialogType = ref('create');
const dialogTitle = computed(() => (dialogType.value === 'create' ? 'Crear Evento' : 'Editar Evento'));

const openCreateDialog = () => {
  resetEvent();
  dialogType.value = 'create';
  dialogOpen.value = true;
};

const openEditDialog = (evt) => {
  event.value = { ...evt };
  dialogType.value = 'edit';
  dialogOpen.value = true;
};

const refreshEvents = () => {
    isLoading.value = true;
    getEvents().finally(() => {
        isLoading.value = false;
    });
};
const closeDialog = () => {
  resetEvent();
  dialogOpen.value = false;
};

const submitForm = async () => {
  if (dialogType.value === 'create') {
    await createEvent(event.value);
  } else {
    await updateEvent(event.value.id, event.value);
  }
  await getEvents();
  closeDialog();
};

const confirmDeleteEvent = async (id) => {
  if (!confirm('¿Deseas eliminar este evento?')) return;
  await destroyEvent(id);
  await getEvents();
};

const formatDate = (dateStr) => {
  const date = new Date(dateStr);
  return date.toLocaleDateString('es-ES', {
    year: 'numeric',
    month: 'short',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  });
};

const statusOptions = ref([
  { label: 'Activo', value: 1 },
  { label: 'Inactivo', value: 0 }
]);

onMounted(getEvents);
</script>
