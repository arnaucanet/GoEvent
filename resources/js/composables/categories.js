import { ref } from 'vue'
import * as yup from 'yup'
import axios from 'axios'
import { useRouter } from 'vue-router'
import { useToast } from './useToast'
import { useValidation } from './useValidation'

export default function useCategories() {
  const categories = ref([])
  const categoryList = ref([])
  const initialCategory = { id: null, name: '' }
  const category = ref({ ...initialCategory })
  const isLoading = ref(false)
  const toast = useToast()
  const router = useRouter()
  const {
    errors,
    validate,
    handleRequestError,
    clearErrors,
    hasError,
    getError
  } = useValidation()

  const categorySchema = yup.object({
    name: yup
      .string()
      .trim()
      .required('The name is required')
      .min(3, 'Must be at least 3 characters long')
  })

  const withLoading = async (fn) => {
    if (isLoading.value) return 
    isLoading.value = true
    try {
      return await fn()
    } finally {
      isLoading.value = false
    }
  }

  const getCategories = async () => {
    return await withLoading(async () => {
      try {
        const response = await axios.get('/api/categories')
        categories.value = response.data.data
        categoryList.value = response.data.data.map(c => ({ id: c.id, name: c.name }))
      } catch (e) {
        toast.error('Error loading categories')
      }
    })
  }

  const getCategory = async (id) => {
    return await withLoading(async () => {
      try {
        const response = await axios.get(`/api/categories/${id}`)
        category.value = response.data.data
      } catch (e) {
        toast.error('Category not found')
      }
    })
  }

  const createCategory = async (data) => {
    clearErrors()
    const isValid = await validate(categorySchema, data)
    if (!isValid) return false

    return await withLoading(async () => {
      try {
        await axios.post('/api/categories', data)
        toast.success('Category created successfully')
        router.push({ name: 'categories.index' })
      } catch (e) {
        handleRequestError(e)
      }
    })
  }

  const updateCategory = async (id, data) => {
    clearErrors()
    const isValid = await validate(categorySchema, data)
    if (!isValid) return false

    return await withLoading(async () => {
      try {
        await axios.put(`/api/categories/${id}`, data)
        toast.success('Category updated successfully')
        router.push({ name: 'categories.index' })
      } catch (e) {
        handleRequestError(e)
      }
    })
  }

  const destroyCategory = async (id) => {
    return await withLoading(async () => {
      try {
        await axios.delete(`/api/categories/${id}`)
        toast.success('Category deleted successfully')
        await getCategories()
      } catch (e) {
        toast.error('Failed to delete category')
      }
    })
  }

  const resetCategory = () => {
    category.value = { ...initialCategory }
    clearErrors()
  }

  return {
    categories,
    category,
    categoryList,
    isLoading,
    errors,
    hasError,
    getError,
    clearErrors,
    getCategories,
    getCategory,
    createCategory,
    updateCategory,
    destroyCategory,
    resetCategory
  }
}