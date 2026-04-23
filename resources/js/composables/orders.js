import { ref } from 'vue';
import axios from 'axios';

export default function useOrders() {
    const orders = ref([]);
    const isLoading = ref(false);
    const error = ref(null);

    const getOrders = async (type = 'upcoming') => {
        isLoading.value = true;
        error.value = null;
        try {
            const response = await axios.get('/api/orders', { params: { type } });
            orders.value = response.data;
        } catch (e) {
            error.value = 'No se pudieron cargar las entradas.';
            orders.value = [];
        } finally {
            isLoading.value = false;
        }
    };

    const createOrder = async (payload) => {
        const response = await axios.post('/api/orders', payload);
        return response.data;
    };

    return { orders, isLoading, error, getOrders, createOrder };
}
