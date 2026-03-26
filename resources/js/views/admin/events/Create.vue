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
import useEvents from "@/composables/events"
import useCategories from "@/composables/categories"

const {
    event,
    createEvent,
    resetEvent,
    isLoading,
    errors,
} = useEvents()

const {
    categoryList,
    getCategories
} = useCategories()

onMounted(() => {
    resetEvent()
    getCategories()
})

const submitForm = async () => {
    await createEvent(event.value)
}
</script>
