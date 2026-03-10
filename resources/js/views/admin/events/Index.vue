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

      <!-- Active -->
      <Column field="active" header="Activo" sortable>
        <template #body="slotProps">
          <Tag 
            :value="slotProps.data.active ? 'Activo' : 'Inactivo'" 
            :severity="slotProps.data.active ? 'success' : 'danger'" 
          />
        </template>
      </Column>


      <!-- Acciones -->
      <Column header="Acciones">
        <template #body="{ data }">
          <Button
            icon="pi pi-pencil"
            text
            size="small"
            @click="openEditDialog(data)"
          />
          <Button
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

    <!-- Dialog Editar -->
    <Dialog v-model:visible="dialogOpen" modal :header="dialogTitle" style="width:400px">
      <div class="flex flex-col gap-4">
        <label>Titulo del evento</label>
        <InputText v-model="event.title" :class="{ 'p-invalid': hasError('title') }" />
        <small v-if="hasError('title')" class="text-red-500">{{ getError('title') }}</small>
        <label>Descripción</label>
        <InputText v-model="event.description" :class="{ 'p-invalid': hasError('description') }" />
        <small v-if="hasError('description')" class="text-red-500">{{ getError('description') }}</small>
          <div class="flex flex-col gap-2">
            <label class="font-bold">Estado</label>
              <Select 
                v-model="event.active" 
                :options="statusOptions" 
                optionLabel="label" 
                optionValue="value" 
                placeholder="Selecciona un estado" 
                class="w-full"
              />
          </div>
      </div>

      <template #footer>
        <Button label="Cancelar" severity="secondary" @click="closeDialog" />
        <Button
          :label="dialogType === 'create' ? 'Crear' : 'Guardar'"
          :loading="isLoading"
          @click="submitForm"
        />
      </template>
    </Dialog>
  </div>
</template>

<script setup>
import { ref, reactive, computed, onMounted } from 'vue';
import { useToast } from '@/composables/useToast';
import Select from 'primevue/select';
import useEvents from '@/composables/events';
import { useRouter } from "vue-router";


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
