import { computed, ref } from "vue";
import axios from "axios";

export default function usePublicEvents() {
    const featuredEvents = ref([]);
    const visibleEvents = ref([]);
    const categories = ref([]);
    const isLoadingFeatured = ref(false);
    const isLoadingEvents = ref(false);
    const featuredError = ref("");
    const eventsError = ref("");

    const cityOptions = computed(() => {
        const source = visibleEvents.value.length ? visibleEvents.value : featuredEvents.value;
        const cities = source
            .map((item) => item.city)
            .filter((value) => !!value)
            .filter((value, index, array) => array.indexOf(value) === index)
            .sort((a, b) => a.localeCompare(b));

        return cities.map((city) => ({
            label: city,
            value: city,
        }));
    });

    const loadCategories = async () => {
        try {
            const response = await axios.get("/api/category-list");
            categories.value = response.data?.data || response.data || [];
        } catch (error) {
            categories.value = [];
        }
    };

    const loadFeaturedEvents = async (params = { limit: 8 }) => {
        if (isLoadingFeatured.value) return;

        isLoadingFeatured.value = true;
        featuredError.value = "";

        try {
            const response = await axios.get("/api/events/featured", { params });
            featuredEvents.value = response.data?.data || response.data || [];
            visibleEvents.value = [...featuredEvents.value];
        } catch (error) {
            featuredEvents.value = [];
            visibleEvents.value = [];
            featuredError.value = "No se pudieron cargar los eventos destacados.";
        } finally {
            isLoadingFeatured.value = false;
        }
    };

    const searchPublicEvents = async (params = {}) => {
        if (isLoadingEvents.value) return;

        isLoadingEvents.value = true;
        eventsError.value = "";

        try {
            const response = await axios.get("/api/events/public", { params });
            visibleEvents.value = response.data?.data || response.data || [];
        } catch (error) {
            visibleEvents.value = [];
            eventsError.value = "No se pudieron cargar eventos con esos filtros.";
        } finally {
            isLoadingEvents.value = false;
        }
    };

    const loadHomeInitialData = async () => {
        await Promise.all([
            loadCategories(),
            loadFeaturedEvents({ limit: 8 }),
        ]);
    };

    return {
        featuredEvents,
        visibleEvents,
        categories,
        cityOptions,
        isLoadingFeatured,
        isLoadingEvents,
        featuredError,
        eventsError,
        loadHomeInitialData,
        loadCategories,
        loadFeaturedEvents,
        searchPublicEvents,
    };
}
