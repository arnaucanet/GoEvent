<template>
    <Panel class="flex flex-col justify-center my-10">
        <h2 class="mb-4 text-xl font-semibold">Create Event</h2>

        <form @submit.prevent="submitForm">

            <!-- TITLE -->
            <div class="mb-3">
                <div class="flex items-center gap-3">
                    <label for="event-title" class="w-32">Title:</label>
                    <InputText
                        v-model="event.title"
                        id="event-title"
                        type="text"
                        size="small"
                        :invalid="!!errors.title"
                        class="w-full"
                    />
                </div>
                <div class="text-red-400 mt-1" v-if="errors.title">
                    {{ errors.title }}
                </div>
            </div>

            <!-- DESCRIPTION -->
            <div class="mb-3">
                <div class="flex items-center gap-3">
                    <label for="event-description" class="w-32">Description:</label>
                    <Textarea 
                        v-model="event.description" 
                        autoResize 
                        rows="5" 
                        cols="30" 
                        class="w-full"
                        :invalid="!!errors.description"
                    />
                </div>
                <div class="text-red-400 mt-1" v-if="errors.description">
                    {{ errors.description }}
                </div>
            </div>

            <!-- START DATE -->
            <div class="mb-3">
                <div class="flex items-center gap-3">
                    <label for="event-start-date" class="w-32">Start Date:</label>
                    <InputText
                        v-model="event.start_date"
                        id="event-start-date"
                        type="datetime-local"
                        size="small"
                        :invalid="!!errors.start_date"
                        class="w-full"
                    />
                </div>
                <div class="text-red-400 mt-1" v-if="errors.start_date">
                    {{ errors.start_date }}
                </div>
            </div>

            <!-- END DATE -->
            <div class="mb-3">
                <div class="flex items-center gap-3">
                    <label for="event-end-date" class="w-32">End Date:</label>
                    <InputText
                        v-model="event.end_date"
                        id="event-end-date"
                        type="datetime-local"
                        size="small"
                        :invalid="!!errors.end_date"
                        class="w-full"
                    />
                </div>
                <div class="text-red-400 mt-1" v-if="errors.end_date">
                    {{ errors.end_date }}
                </div>
            </div>

            <!-- CAPACITY -->
            <div class="mb-3">
                <div class="flex items-center gap-3">
                    <label for="event-capacity" class="w-32">Capacity:</label>
                    <InputText
                        v-model="event.capacity"
                        id="event-capacity"
                        type="number"
                        size="small"
                        :invalid="!!errors.capacity"
                        class="w-full"
                    />
                </div>
                <div class="text-red-400 mt-1" v-if="errors.capacity">
                    {{ errors.capacity }}
                </div>
            </div>

            <!-- VENUE -->
            <div class="mb-3">
                <div class="flex items-center gap-3">
                    <label for="event-venue" class="w-32">Recinto:</label>
                    <Select
                        v-model="event.venue_id"
                        :options="venueList"
                        :optionLabel="(v) => `${v.name} — ${v.city} (aforo ${v.capacity})`"
                        optionValue="id"
                        placeholder="Selecciona un recinto"
                        class="w-full"
                        :class="{'p-invalid': !!errors.venue_id}"
                        filter
                    />
                </div>
                <div class="text-red-400 mt-1" v-if="errors.venue_id">
                    {{ errors.venue_id }}
                </div>
            </div>

            <!-- CATEGORY -->
            <div class="mb-3">
                <div class="flex items-center gap-3">
                    <label for="event-category" class="w-32">Category:</label>
                    <Select 
                        v-model="event.category_id" 
                        :options="categoryList" 
                        optionLabel="name" 
                        optionValue="id" 
                        placeholder="Select a category" 
                        class="w-full"
                        :class="{'p-invalid': !!errors.category_id}"
                    />
                </div>
                <div class="text-red-400 mt-1" v-if="errors.category_id">
                    {{ errors.category_id }}
                </div>
            </div>

            <!-- FEATURED -->
            <div class="mb-3">
                <div class="flex items-center gap-3">
                    <label for="event-featured" class="w-32">Featured:</label>
                    <Checkbox v-model="event.featured" :binary="true" />
                </div>
            </div>

            <!-- STATUS -->
             <div class="mb-3">
                <div class="flex items-center gap-3">
                    <label for="event-status" class="w-32">Status:</label>
                    <Select
                        v-model="event.status"
                        :options="[{label: 'Borrador', value: 'borrador'}, {label: 'Publicado', value: 'publicado'}, {label: 'Cancelado', value: 'cancelado'}]"
                        optionLabel="label"
                        optionValue="value"
                        placeholder="Select status"
                        class="w-full"
                    />
                </div>
                 <div class="text-red-400 mt-1" v-if="errors.status">
                    {{ errors.status }}
                </div>
            </div>

            <!-- IMAGE -->
            <div class="mb-3">
                <div class="flex items-start gap-3">
                    <label class="w-32 mt-2">Imagen:</label>
                    <div class="flex-1">
                        <div
                            class="relative border-2 border-dashed rounded-xl cursor-pointer transition-colors overflow-hidden"
                            :class="imagePreview ? 'border-blue-400' : 'border-gray-300 hover:border-blue-400'"
                            @click="$refs.imageInput.click()"
                        >
                            <img v-if="imagePreview" :src="imagePreview" class="w-full max-h-52 object-cover" />
                            <div v-else class="flex flex-col items-center justify-center gap-2 py-10 text-gray-400">
                                <i class="pi pi-image text-4xl"></i>
                                <span class="text-sm font-medium">Haz clic para subir imagen</span>
                                <span class="text-xs">PNG, JPG, WEBP — máx. 4 MB</span>
                            </div>
                            <button v-if="imagePreview" type="button"
                                class="absolute top-2 right-2 bg-red-500 text-white rounded-full w-7 h-7 flex items-center justify-center hover:bg-red-600 transition-colors"
                                @click.stop="removeImage">
                                <i class="pi pi-times text-xs"></i>
                            </button>
                        </div>
                        <input ref="imageInput" type="file" accept="image/*" class="hidden" @change="onImageSelected" />
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
import { ref, onMounted } from "vue"
import useEvents from "@/composables/events"
import useCategories from "@/composables/categories"
import useVenues from "@/composables/venues"

const {
    event,
    createEvent,
    resetEvent,
    isLoading,
    errors,
} = useEvents()

const { categoryList, getCategories } = useCategories()
const { venueList, getVenueList } = useVenues()

const imageFile = ref(null)
const imagePreview = ref(null)

const onImageSelected = (e) => {
    const file = e.target.files[0]
    if (!file) return
    imageFile.value = file
    imagePreview.value = URL.createObjectURL(file)
}

const removeImage = () => {
    imageFile.value = null
    imagePreview.value = null
}

onMounted(() => {
    resetEvent()
    getCategories()
    getVenueList()
})

const submitForm = async () => {
    await createEvent(event.value, imageFile.value)
}
</script>
