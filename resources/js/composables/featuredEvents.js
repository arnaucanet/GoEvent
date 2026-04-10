import { computed, ref } from "vue";
import axios from "axios";

const formatDate = (dateStr) => {
    if (!dateStr) return "Fecha por confirmar";

    const date = new Date(dateStr);
    return date.toLocaleDateString("es-ES", {
        year: "numeric",
        month: "long",
        day: "numeric",
        hour: "2-digit",
        minute: "2-digit",
    });
};

const mapToCard = (event) => ({
    id: event.id,
    title: event.title || "Evento sin titulo",
    description: event.description || "Sin descripcion disponible.",
    city: event.city || "-",
    categoryName: event.category?.name || "General",
    startDateLabel: formatDate(event.start_date),
    capacity: event.capacity || 0,
    statusLabel: "Publicado",
});

export default function useFeaturedEvents() {
    const featuredEvents = ref([]);
    const isLoadingFeatured = ref(false);
    const featuredError = ref("");

    const featuredCards = computed(() => {
        return featuredEvents.value.slice(0, 3).map(mapToCard);
    });

    const loadFeaturedEvents = async (limit = 3) => {
        if (isLoadingFeatured.value) return;

        isLoadingFeatured.value = true;
        featuredError.value = "";

        try {
            const response = await axios.get("/api/events/featured", {
                params: { limit: Math.max(1, Math.min(limit, 3)) },
            });

            featuredEvents.value = response.data?.data || response.data || [];
        } catch (error) {
            featuredEvents.value = [];
            featuredError.value = "No se pudieron cargar los eventos destacados.";
        } finally {
            isLoadingFeatured.value = false;
        }
    };

    return {
        featuredCards,
        isLoadingFeatured,
        featuredError,
        loadFeaturedEvents,
    };
}
