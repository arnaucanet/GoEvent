<template>
  <div class="venues-page p-4">
    <div class="flex justify-between items-center mb-4 gap-2">
      <h2 class="text-xl font-semibold">Gestión de Recintos</h2>
      <div class="flex gap-2">
        <Button label="Actualizar" icon="pi pi-refresh" size="small" outlined severity="secondary"
                :loading="isLoading" @click="getVenues" />
        <Button v-if="can('venue-create')" label="Nuevo Recinto" icon="pi pi-plus"
                severity="primary" @click="openCreateDialog" />
      </div>
    </div>

    <DataTable :value="venues" data-key="id" striped-rows :loading="isLoading"
               :paginator="true" :rows="10" :global-filter-fields="['name','city','address']">
      <Column field="id" header="ID" sortable />
      <Column field="name" header="Nombre" sortable />
      <Column field="city" header="Ciudad" sortable />
      <Column field="address" header="Dirección" sortable />
      <Column field="capacity" header="Aforo" sortable />
      <Column field="active" header="Activo" sortable>
      </Column>
      <Column header="Acciones">
        <template #body="{ data }">
          <Button v-if="can('venue-edit')" icon="pi pi-pencil" text size="small" @click="openEditDialog(data)" />
          <Button v-if="can('venue-delete')" icon="pi pi-trash" text severity="danger" size="small"
                  class="ml-2" @click="confirmDelete(data.id)" />
        </template>
      </Column>
    </DataTable>

    <Dialog v-model:visible="dialogOpen" modal :header="dialogTitle" style="width:480px">
      <div class="flex flex-col gap-3">
        <div>
          <label class="block mb-1 font-medium">Nombre</label>
          <InputText v-model="venue.name" class="w-full" :class="{ 'p-invalid': hasError('name') }" />
          <small v-if="hasError('name')" class="text-red-500">{{ getError('name') }}</small>
        </div>
        <div>
          <label class="block mb-1 font-medium">Dirección</label>
          <InputText v-model="venue.address" class="w-full" :class="{ 'p-invalid': hasError('address') }" />
          <small v-if="hasError('address')" class="text-red-500">{{ getError('address') }}</small>
        </div>
        <div>
          <label class="block mb-1 font-medium">Ciudad</label>
          <InputText v-model="venue.city" class="w-full" :class="{ 'p-invalid': hasError('city') }" />
          <small v-if="hasError('city')" class="text-red-500">{{ getError('city') }}</small>
        </div>
        <div>
          <label class="block mb-1 font-medium">Aforo</label>
          <InputNumber v-model="venue.capacity" :min="1" class="w-full" :class="{ 'p-invalid': hasError('capacity') }" />
          <small v-if="hasError('capacity')" class="text-red-500">{{ getError('capacity') }}</small>
        </div>
        <div class="flex items-center gap-2">
          <Checkbox v-model="venue.active" :binary="true" inputId="venue-active" />
          <label for="venue-active">Activo</label>
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
import useVenues from '@/composables/venues';

const { can } = useAbility();
const {
  venues, venue, isLoading,
  hasError, getError,
  getVenues, createVenue, updateVenue, destroyVenue, resetVenue,
} = useVenues();

const dialogOpen = ref(false);
const dialogType = ref('create');
const dialogTitle = computed(() => dialogType.value === 'create' ? 'Crear Recinto' : 'Editar Recinto');

const openCreateDialog = () => { resetVenue(); dialogType.value = 'create'; dialogOpen.value = true; };
const openEditDialog = (v) => { venue.value = { ...v }; dialogType.value = 'edit'; dialogOpen.value = true; };
const closeDialog = () => { resetVenue(); dialogOpen.value = false; };

const submitForm = async () => {
  const payload = {
    name: venue.value.name,
    address: venue.value.address,
    city: venue.value.city,
    capacity: Number(venue.value.capacity),
    active: !!venue.value.active,
  };
  const ok = dialogType.value === 'create'
    ? await createVenue(payload)
    : await updateVenue(venue.value.id, payload);
  if (ok) { await getVenues(); closeDialog(); }
};

const confirmDelete = async (id) => {
  if (!confirm('¿Eliminar este recinto?')) return;
  await destroyVenue(id);
};

onMounted(getVenues);
</script>
