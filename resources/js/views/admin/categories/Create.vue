<template>
    <Panel class="flex flex-col justify-center my-10">
        <h2 class="mb-4 text-xl font-semibold">Create Category</h2>

        <form @submit.prevent="submitForm">

            <!-- NAME -->
            <div class="mb-3">
                <div class="flex items-center gap-3">
                    <label for="category-name" class="w-32">Name:</label>
                    <InputText
                        v-model="category.name"
                        id="category-name"
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
                    <label for="category-description" class="w-32">Description:</label>
                    <InputText
                        v-model="category.description"
                        id="category-description"
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

            <div class="mb-4">
                <div class="flex items-center gap-3">
                    <label for="category-active" class="w-32">Active:</label>
                    <InputSwitch v-model="category.active" />
                </div>
            </div>

            <div class="mt-4 text-right flex justify-end gap-2">
                <RouterLink :to="{ name: 'categories.index' }">
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
import useCategories from "@/composables/categories"

const router = useRouter()

const {
    category,
    createCategory,
    resetCategory,
    validationErrors,
    errors,
    isLoading
} = useCategories()

onMounted(() => {
    resetCategory()
})

async function submitForm() {
    const success = await createCategory(category.value)

    if (success !== false) {
        router.push({ name: 'categories.index' })
    }
}

</script>
