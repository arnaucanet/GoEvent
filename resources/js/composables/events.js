import { ref } from "vue";
import * as yup from "yup";
import axios from "axios";
import { useRouter } from "vue-router";
import { useToast } from "./useToast";
import { useValidation } from "./useValidation";

export default function useEvents() {
    const events = ref([]);
    const eventsList = ref([]);
    const initialEvent = {
        id: null,
        name: "",
        description: "",
        active: false,
    };
    const event = ref({ ...initialEvent });
    const isLoading = ref(false);
    const toast = useToast();
    const router = useRouter();
    const {
        errors,
        validate,
        handleRequestError,
        clearErrors,
        hasError,
        getError,
    } = useValidation();

    const eventSchema = yup.object({
        name: yup
            .string()
            .trim()
            .required("The name is required")
            .min(3, "Must be at least 3 characters long"),
        active: yup
            .number()
            .typeError("Select Status")
            .required("Status is required")
    });

    const withLoading = async (fn) => {
        if (isLoading.value) return;
        isLoading.value = true;
        try {
            return await fn();
        } finally {
            isLoading.value = false;
        }
    };

    const getEvents = async () => {
        return await withLoading(async () => {
            try {
                const response = await axios.get("/api/events");

                const data = response.data.data || response.data;
                events.value = data;
                eventsList.value = data.map((e) => ({
                    id: e.id,
                    name: e.name,
                }));
            } catch (e) {
                toast.error("Error loading events");
            }
        });
    };

    const getEvent = async (id) => {
        return await withLoading(async () => {
            try {
                const response = await axios.get(`/api/events/${id}`);
                event.value = response.data.data;
            } catch (e) {
                toast.error("Event not found");
            }
        });
    };

    const createEvent = async (data) => {
        clearErrors();
        const isValid = await validate(eventSchema, data);
        if (!isValid) return false;

        return await withLoading(async () => {
            try {
                await axios.post("/api/events", data);
                toast.success("Event created successfully");
                router.push({ name: "events.index" });
            } catch (e) {
                handleRequestError(e);
            }
        });
    };

    const updateEvent = async (id, data) => {
        clearErrors();
        const isValid = await validate(eventSchema, data);
        if (!isValid) return false;

        return await withLoading(async () => {
            try {
                await axios.put(`/api/events/${id}`, data);
                toast.success("Event updated successfully");
                router.push({ name: "events.index" });
            } catch (e) {
                handleRequestError(e);
            }
        });
    };

    const destroyEvent = async (id) => {
        return await withLoading(async () => {
            try {
                await axios.delete(`/api/events/${id}`);
                toast.success("Event deleted successfully");
                await getEvents();
            } catch (e) {
                toast.error("Failed to delete event");
            }
        });
    };

    const resetEvent = () => {
        event.value = { ...initialEvent };
        clearErrors();
    };

    return {
        events,
        event,
        eventsList,
        isLoading,
        errors,
        hasError,
        getError,
        clearErrors,
        getEvents,
        getEvent,
        createEvent,
        updateEvent,
        destroyEvent,
        resetEvent,
    };
}
