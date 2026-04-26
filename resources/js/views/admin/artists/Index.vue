<template>
  <div class="artists-page p-4">
    <div class="flex justify-between items-center mb-4 gap-2">
      <h2 class="text-xl font-semibold">Gestión de Artistas</h2>
      <div class="flex gap-2">
        <Button label="Actualizar" icon="pi pi-refresh" size="small" outlined severity="secondary"
                :loading="isLoading" @click="getArtists" />
        <Button v-if="can('artist-create')" label="Nuevo Artista" icon="pi pi-plus"
                severity="primary" @click="openCreateDialog" />
      </div>
    </div>

    <DataTable :value="artists" data-key="id" striped-rows :loading="isLoading"
               :paginator="true" :rows="10">
      <Column field="id" header="ID" sortable />
      <Column field="name" header="Nombre" sortable />
      <Column field="description" header="Descripción">
        <template #body="{ data }">
          <span class="line-clamp-2 max-w-md inline-block">{{ data.description }}</span>
        </template>
      </Column>
      <Column field="active" header="Activo" sortable>
      </Column>
      <Column header="Acciones">
        <template #body="{ data }">
          <Button v-if="can('artist-edit')" icon="pi pi-pencil" text size="small" @click="openEditDialog(data)" />
          <Button v-if="can('artist-delete')" icon="pi pi-trash" text severity="danger" size="small"
                  class="ml-2" @click="confirmDelete(data.id)" />
        </template>
      </Column>
    </DataTable>

    <Dialog v-model:visible="dialogOpen" modal :header="dialogTitle" style="width:480px">
      <div class="flex flex-col gap-3">
        <div>
          <label class="block mb-1 font-medium">Nombre</label>
          <InputText v-model="artist.name" class="w-full" :class="{ 'p-invalid': hasError('name') }" />
          <small v-if="hasError('name')" class="text-red-500">{{ getError('name') }}</small>
        </div>
        <div>
          <label class="block mb-1 font-medium">Descripción</label>
          <Textarea v-model="artist.description" autoResize rows="4" class="w-full" />
        </div>
        <div>
          <label class="block mb-1 font-medium">Imagen (URL/path)</label>
          <InputText v-model="artist.image" class="w-full" placeholder="opcional" />
        </div>
        <div class="flex items-center gap-2">
          <Checkbox v-model="artist.active" :binary="true" inputId="artist-active" />
          <label for="artist-active">Activo</label>
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
import useArtists from '@/composables/artists';

const { can } = useAbility();
const {
  artists, artist, isLoading,
  hasError, getError,
  getArtists, createArtist, updateArtist, destroyArtist, resetArtist,
} = useArtists();

const dialogOpen = ref(false);
const dialogType = ref('create');
const dialogTitle = computed(() => dialogType.value === 'create' ? 'Crear Artista' : 'Editar Artista');

const openCreateDialog = () => { resetArtist(); dialogType.value = 'create'; dialogOpen.value = true; };
const openEditDialog = (a) => { artist.value = { ...a }; dialogType.value = 'edit'; dialogOpen.value = true; };
const closeDialog = () => { resetArtist(); dialogOpen.value = false; };

const submitForm = async () => {
  const payload = {
    name: artist.value.name,
    description: artist.value.description || null,
    image: artist.value.image || null,
    active: !!artist.value.active,
  };
  const ok = dialogType.value === 'create'
    ? await createArtist(payload)
    : await updateArtist(artist.value.id, payload);
  if (ok) { await getArtists(); closeDialog(); }
};

const confirmDelete = async (id) => {
  if (!confirm('¿Eliminar este artista?')) return;
  await destroyArtist(id);
};

onMounted(getArtists);
</script>
