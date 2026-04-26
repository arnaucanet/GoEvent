import { ref } from "vue";
import * as yup from "yup";
import axios from "axios";
import { useToast } from "./useToast";
import { useValidation } from "./useValidation";

export default function useEventTypes() {
    const eventTypes = ref([]);
    const eventTypeList = ref([]);
    const initialType = {
        id: null,
        name: "",
        description: "",
        active: true,
    };
    const eventType = ref({ ...initialType });
    const isLoading = ref(false);
    const toast = useToast();
    const { errors, validate, handleRequestError, clearErrors, hasError, getError } = useValidation();

    const typeSchema = yup.object({
        name: yup.string().trim().required("El nombre es obligatorio").min(2),
    });

    const withLoading = async (fn) => {
        if (isLoading.value) return;
        isLoading.value = true;
        try { return await fn(); } finally { isLoading.value = false; }
    };

    const getEventTypes = async (params = {}) => {
        return withLoading(async () => {
            try {
                const { data } = await axios.get("/api/event-types", { params });
                eventTypes.value = data?.data ?? data;
            } catch { toast.error("Error cargando tipos"); }
        });
    };

    const getEventTypeList = async () => {
        const { data } = await axios.get("/api/event-types/list");
        eventTypeList.value = data?.data ?? data ?? [];
        return eventTypeList.value;
    };

    const createEventType = async (data) => {
        clearErrors();
        const { isValid } = await validate(typeSchema, data);
        if (!isValid) return false;
        return withLoading(async () => {
            try {
                await axios.post("/api/event-types", data);
                toast.success("Tipo creado");
                return true;
            } catch (e) { handleRequestError(e); return false; }
        });
    };

    const updateEventType = async (id, data) => {
        clearErrors();
        const { isValid } = await validate(typeSchema, data);
        if (!isValid) return false;
        return withLoading(async () => {
            try {
                await axios.put(`/api/event-types/${id}`, data);
                toast.success("Tipo actualizado");
                return true;
            } catch (e) { handleRequestError(e); return false; }
        });
    };

    const destroyEventType = async (id) => {
        return withLoading(async () => {
            try {
                await axios.delete(`/api/event-types/${id}`);
                toast.success("Tipo eliminado");
                await getEventTypes();
            } catch { toast.error("No se pudo eliminar el tipo"); }
        });
    };

    const resetEventType = () => { eventType.value = { ...initialType }; clearErrors(); };

    return {
        eventTypes, eventType, eventTypeList, isLoading,
        errors, hasError, getError,
        getEventTypes, getEventTypeList,
        createEventType, updateEventType, destroyEventType, resetEventType,
    };
}
