<template>
    <Panel class="flex flex-col justify-center my-10">
        <h2 class="mb-4 text-xl font-semibold">Create Event</h2>

        <form @submit.prevent="submitForm">

            <!-- NAME -->
            <div class="mb-3">
                <div class="flex items-center gap-3">
                    <label for="event-name" class="w-32">Name:</label>
                    <InputText
                        v-model="event.name"
                        id="event-name"
                        type="text"
                        size="small"
                        :invalid="!!errors.name"
                        class="w-full"
                    />
                </div>

                <div class="text-red-400 mt-1">
                    {{ errors.name }}
                </div>

                <div class="mt-1">
                    <div
                        v-for="message in validationErrors?.name"
                        :key="message"
                        class="text-red-400"
                    >
                        {{ message }}
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <div class="flex items-center gap-3">
                    <label for="event-description" class="w-32">Description:</label>
                    <InputText
                        v-model="event.description"
                        id="event-description"
                        type="text"
                        size="small"
                        :invalid="!!errors.description"
                        class="w-full"
                    />
                </div>

                <div class="text-red-400 mt-1">
                    {{ errors.description }}
                </div>

                <div class="mt-1">
                    <div
                        v-for="message in validationErrors?.description"
                        :key="message"
                        class="text-red-400"
                    >
                        {{ message }}
                    </div>
                </div>
                
            </div>
            <div class="mb-3">
                <div class="flex items-center gap-3">
                    <label for="event-Estado" class="w-32">Estado:</label>
                    <Select 
                    v-model="event.active" 
                    :options="statusOptions" 
                    optionLabel="label" 
                    optionValue="value" 
                    placeholder="Selecciona un estado" 
                    class="w-full"
                    />
                </div>

                <div class="text-red-400 mt-1">
                    {{ errors.description }}
                </div>

                <div class="mt-1">
                    <div
                        v-for="message in validationErrors?.active"
                        :key="message"
                        class="text-red-400"
                    >
                        {{ message }}
                    </div>
                </div>
                
            </div>
            <div class="mt-4 text-right flex justify-end gap-2">
                <RouterLink :to="{ name: 'events.index' }">
                    <Button severity="secondary" type="button">
                        Cancel
                    </Button>
                </RouterLink>

                <Button :disabled="isLoading" type="submit">
                    <span v-if="isLoading">Saving...</span>
                    <span v-else>Save</span>
                </Button>
            </div>

        </form>
    </Panel>
</template>

<script setup>
import { onMounted } from "vue"
import { useRouter } from "vue-router"
import useEvents from "@/composables/events"
import { ref } from 'vue'

const router = useRouter()

const {
    event,
    createEvent,
    resetEvent,
    validationErrors,
    errors,
    isLoading
} = useEvents()

onMounted(() => {
    resetEvent()
})

async function submitForm() {
    const success = await createEvent(event.value)

    if (success !== false) {
        router.push({ name: 'events.index' })
    }
}

const statusOptions = ref([
  { label: 'Activo', value: 1 },
  { label: 'Inactivo', value: 0 }
]);

</script>
