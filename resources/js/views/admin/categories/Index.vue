<template>
  <div class="categories-page p-4">
    <div class="flex justify-between items-center mb-4">
      <h2 class="text-xl font-semibold">Gestión de Categorías</h2>
       <Button
        label="Actualizar"
        icon="pi pi-refresh"
        size="small"
        outlined
        severity="secondary"
        :loading="isLoading"
        @click="refreshCategories"
      />
      <Button
        label="Nueva Categoría"
        icon="pi pi-plus"
        severity="primary"
        @click="router.push('/admin/categories/create')"
      />
    </div>

    <DataTable
      :value="categories"
      data-key="id"
      striped-rows
      :loading="isLoading"
      :paginator="true"
      :rows="10"
    >
      <!-- ID -->
      <Column field="id" header="ID" sortable />

      <!-- Name -->
      <Column field="name" header="Nombre" sortable />

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
            @click="confirmDeleteCategory(data.id)"
          />
        </template>
      </Column>
    </DataTable>

    <!-- Dialog Editar -->
    <Dialog v-model:visible="dialogOpen" modal :header="dialogTitle" style="width:400px">
      <div class="flex flex-col gap-4">
        <label>Nombre de la categoría</label>
        <InputText v-model="category.name" :class="{ 'p-invalid': hasError('name') }" />
        <small v-if="hasError('name')" class="text-red-500">{{ getError('name') }}</small>
        <label>Descripción</label>
        <InputText v-model="category.description" :class="{ 'p-invalid': hasError('description') }" />
        <small v-if="hasError('description')" class="text-red-500">{{ getError('description') }}</small>
          <div class="flex flex-col gap-2">
            <label class="font-bold">Estado</label>
              <Select 
                v-model="category.active" 
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
import useCategories from '@/composables/categories';
import { useRouter } from "vue-router";


const {
  categories,
  category,
  getCategories,
  createCategory,
  updateCategory,
  destroyCategory,
  resetCategory,
  hasError,
  getError,
  isLoading
} = useCategories();

const router = useRouter()

const dialogOpen = ref(false);
const dialogType = ref('create');
const dialogTitle = computed(() => (dialogType.value === 'create' ? 'Crear Categoría' : 'Editar Categoría'));

const openCreateDialog = () => {
  resetCategory();
  dialogType.value = 'create';
  dialogOpen.value = true;
};

const openEditDialog = (cat) => {
  category.value = { ...cat };
  dialogType.value = 'edit';
  dialogOpen.value = true;
};

const refreshCategories = () => {
    isLoading.value = true;
    getCategories().finally(() => {
        isLoading.value = false;
    });
};
const closeDialog = () => {
  resetCategory();
  dialogOpen.value = false;
};

const submitForm = async () => {
  if (dialogType.value === 'create') {
    await createCategory({ name: category.value.name, description: category.value.description, active: category.value.active });
  } else {
    await updateCategory(category.value.id, { name: category.value.name, description: category.value.description, active: category.value.active });
  }
  await getCategories();
  closeDialog();
};

const confirmDeleteCategory = async (id) => {
  if (!confirm('¿Deseas eliminar esta categoría?')) return;
  await destroyCategory(id);
  await getCategories();
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
  { label: 'Activa', value: 1 },
  { label: 'Inactiva', value: 0 }
]);

onMounted(getCategories);
</script>
