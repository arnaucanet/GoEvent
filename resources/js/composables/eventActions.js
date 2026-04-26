import { ref } from "vue";
import axios from "axios";
import { useToast } from "./useToast";

export default function useEventActions() {
    const toast = useToast();
    const isToggling = ref(false);
    const isRegistering = ref(false);

    const toggleFavorite = async (eventId) => {
        if (isToggling.value) return;
        isToggling.value = true;
        try {
            const { data } = await axios.post(`/api/favorites/${eventId}/toggle`);
            toast.success(data.favorited ? "Añadido a favoritos" : "Quitado de favoritos");
            return data.favorited;
        } catch (e) {
            toast.error("No se pudo actualizar el favorito");
            return null;
        } finally {
            isToggling.value = false;
        }
    };

    const registerToEvent = async (eventId) => {
        if (isRegistering.value) return;
        isRegistering.value = true;
        try {
            await axios.post(`/api/registrations/${eventId}`);
            toast.success("Inscripción realizada");
            return true;
        } catch (e) {
            const msg = e?.response?.data?.message || "Error al inscribirte";
            toast.error(msg);
            return false;
        } finally {
            isRegistering.value = false;
        }
    };

    const cancelRegistration = async (eventId) => {
        if (isRegistering.value) return;
        isRegistering.value = true;
        try {
            await axios.delete(`/api/registrations/${eventId}`);
            toast.success("Inscripción cancelada");
            return true;
        } catch (e) {
            toast.error("No se pudo cancelar la inscripción");
            return false;
        } finally {
            isRegistering.value = false;
        }
    };

    const fetchFavorites = async (params = {}) => {
        const { data } = await axios.get("/api/favorites", { params });
        return data;
    };

    const fetchRegistrations = async (params = {}) => {
        const { data } = await axios.get("/api/registrations", { params });
        return data;
    };

    return {
        isToggling,
        isRegistering,
        toggleFavorite,
        registerToEvent,
        cancelRegistration,
        fetchFavorites,
        fetchRegistrations,
    };
}
