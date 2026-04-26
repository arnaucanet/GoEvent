<template>
  <div class="event-types-page p-4">
    <div class="flex justify-between items-center mb-4 gap-2">
      <h2 class="text-xl font-semibold">Gestión de Tipos de Evento</h2>
      <div class="flex gap-2">
        <Button label="Actualizar" icon="pi pi-refresh" size="small" outlined severity="secondary"
                :loading="isLoading" @click="getEventTypes" />
        <Button v-if="can('event-type-create')" label="Nuevo Tipo" icon="pi pi-plus"
                severity="primary" @click="openCreateDialog" />
      </div>
    </div>

    <DataTable :value="eventTypes" data-key="id" striped-rows :loading="isLoading"
               :paginator="true" :rows="10">
      <Column field="id" header="ID" sortable />
      <Column field="name" header="Nombre" sortable />
      <Column field="description" header="Descripción" />
      <Column field="active" header="Activo" sortable>
      </Column>
      <Column header="Acciones">
        <template #body="{ data }">
          <Button v-if="can('event-type-edit')" icon="pi pi-pencil" text size="small" @click="openEditDialog(data)" />
          <Button v-if="can('event-type-delete')" icon="pi pi-trash" text severity="danger" size="small"
                  class="ml-2" @click="confirmDelete(data.id)" />
        </template>
      </Column>
    </DataTable>

    <Dialog v-model:visible="dialogOpen" modal :header="dialogTitle" style="width:440px">
      <div class="flex flex-col gap-3">
        <div>
          <label class="block mb-1 font-medium">Nombre</label>
          <InputText v-model="eventType.name" class="w-full" :class="{ 'p-invalid': hasError('name') }" />
          <small v-if="hasError('name')" class="text-red-500">{{ getError('name') }}</small>
        </div>
        <div>
          <label class="block mb-1 font-medium">Descripción</label>
          <Textarea v-model="eventType.description" autoResize rows="3" class="w-full" />
        </div>
        <div class="flex items-center gap-2">
          <Checkbox v-model="eventType.active" :binary="true" inputId="type-active" />
          <label for="type-active">Activo</label>
        </div>
      </div>
      <template #footer>
        <Button label="Cancelar" severity="secondary" @click="closeDialog" />
        <Button :label="dialogType === 'create' ? 'Crear' : 'Guardar'" :loading="isLoading" @click="submitForm" />
      </template>
    </Dialog>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { useAbility } from '@casl/vue';
import useEventTypes from '@/composables/eventTypes';

const { can } = useAbility();
const {
  eventTypes, eventType, isLoading,
  hasError, getError,
  getEventTypes, createEventType, updateEventType, destroyEventType, resetEventType,
} = useEventTypes();

const dialogOpen = ref(false);
const dialogType = ref('create');
const dialogTitle = computed(() => dialogType.value === 'create' ? 'Crear Tipo' : 'Editar Tipo');

const openCreateDialog = () => { resetEventType(); dialogType.value = 'create'; dialogOpen.value = true; };
const openEditDialog = (t) => { eventType.value = { ...t }; dialogType.value = 'edit'; dialogOpen.value = true; };
const closeDialog = () => { resetEventType(); dialogOpen.value = false; };

const submitForm = async () => {
  const payload = {
    name: eventType.value.name,
    description: eventType.value.description || null,
    active: !!eventType.value.active,
  };
  const ok = dialogType.value === 'create'
    ? await createEventType(payload)
    : await updateEventType(eventType.value.id, payload);
  if (ok) { await getEventTypes(); closeDialog(); }
};

const confirmDelete = async (id) => {
  if (!confirm('¿Eliminar este tipo?')) return;
  await destroyEventType(id);
};

onMounted(getEventTypes);
</script>
