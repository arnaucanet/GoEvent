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
        title: "",
        description: "",
        start_date: "",
        end_date: "",
        capacity: "",
        city: "",
        featured: 0,
        status: 1,
        category_id: "",
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
        title: yup
            .string()
            .trim()
            .required("The title is required")
            .min(3, "Must be at least 3 characters long"),
        description: yup
            .string()
            .required("The description is required"),
        start_date: yup
            .string()
            .required("Start date is required"),
        end_date: yup
            .string()
            .required("End date is required"),
        capacity: yup
            .number()
            .typeError("Capacity must be a number")
            .required("Capacity is required")
            .positive("Capacity must be positive")
            .integer("Capacity must be an integer"),
        category_id: yup
            .number()
            .typeError("Select Category")
            .required("Category is required"),
        city: yup
            .string()
            .nullable()
            .min(2, "City must be at least 2 characters"),
        status: yup
            .string()
            .oneOf(
                ["borrador", "publicado", "cancelado"],
                "Status must be borrador, publicado, or cancelado",
            )
            .required("Status is required"),
        featured: yup.boolean().required("Featured is required"),
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

    const getPublicEvents = async (params = {}) => {
        return await withLoading(async () => {
            try {
                const response = await axios.get("/api/events/public", { params });
                const data = response.data.data || response.data;
                events.value = data;
                eventsList.value = data.map((e) => ({
                    id: e.id,
                    name: e.title,
                }));
            } catch (e) {
                toast.error("Error loading events");
            }
        });
    };

    const getPublicEvent = async (id) => {
        return await withLoading(async () => {
            try {
                const response = await axios.get(`/api/events/public/${id}`);
                event.value = response.data.data || response.data;
            } catch (e) {
                toast.error("Event not found");
            }
        });
    };

    const getEvent = async (id) => {
        return await withLoading(async () => {
            try {
                const response = await axios.get(`/api/events/${id}`);
                event.value = response.data.data || response.data;
            } catch (e) {
                toast.error("Event not found");
            }
        });
    };

    const createEvent = async (data) => {
        clearErrors();
        const isValid = await validate(eventSchema, data);
        if (!isValid.isValid) return false;

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
        if (!isValid.isValid) return false;

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
        getPublicEvents,
        getPublicEvent,
        getEvent,
        createEvent,
        updateEvent,
        destroyEvent,
        resetEvent,
    };
}
