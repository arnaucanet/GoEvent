import { ref } from "vue";
import * as yup from "yup";
import axios from "axios";
import { useRouter } from "vue-router";
import { useToast } from "./useToast";
import { useValidation } from "./useValidation";

export default function useEvents() {
    const events = ref([]);
    const eventsList = ref([]);
    const pagination = ref({
        total: 0,
        per_page: 15,
        current_page: 1,
        last_page: 1,
    });
    const initialEvent = {
        title: "",
        description: "",
        start_date: "",
        end_date: "",
        capacity: "",
        venue_id: null,
        featured: 0,
        status: "borrador",
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
        venue_id: yup
            .number()
            .typeError("Select Venue")
            .required("Venue is required"),
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

    const getEvents = async (params = {}) => {
        return await withLoading(async () => {
            try {
                const response = await axios.get("/api/events", { params });
                const payload = response.data;
                const data = payload.data ?? payload;
                events.value = data;
                eventsList.value = data.map((e) => ({
                    id: e.id,
                    name: e.title ?? e.name,
                }));
                if (payload.meta || payload.current_page !== undefined) {
                    const meta = payload.meta ?? payload;
                    pagination.value = {
                        total: meta.total ?? data.length,
                        per_page: meta.per_page ?? data.length,
                        current_page: meta.current_page ?? 1,
                        last_page: meta.last_page ?? 1,
                    };
                }
            } catch (e) {
                toast.error("Error loading events");
            }
        });
    };

    const getPublicEvents = async (params = {}) => {
        return await withLoading(async () => {
            try {
                const response = await axios.get("/api/events/public", { params });
                const payload = response.data;
                const data = payload.data ?? payload;
                events.value = data;
                eventsList.value = data.map((e) => ({
                    id: e.id,
                    name: e.title,
                }));
                if (payload.meta || payload.current_page !== undefined) {
                    const meta = payload.meta ?? payload;
                    pagination.value = {
                        total: meta.total ?? data.length,
                        per_page: meta.per_page ?? data.length,
                        current_page: meta.current_page ?? 1,
                        last_page: meta.last_page ?? 1,
                    };
                }
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

    const limpiarDatos = (datos) => {
        const resultado = {};

        for (const campo in datos) {
            const valor = datos[campo];

            if (typeof valor === "boolean") {
                resultado[campo] = valor ? "1" : "0";
            } else {
                resultado[campo] = valor;
            }
        }

        return resultado;
    };

    const crearFormData = (datos, imagen) => {
        const formData = new FormData();
        const datosLimpios = limpiarDatos(datos);

        for (const campo in datosLimpios) {
            formData.append(campo, datosLimpios[campo]);
        }

        if (imagen) {
            formData.append("image", imagen);
        }

        return formData;
    };

    const createEvent = async (datos, imagen = null) => {
        clearErrors();
        const resultadoValidacion = await validate(eventSchema, datos);
        if (!resultadoValidacion.isValid) return false;

        return await withLoading(async () => {
            try {
                if (imagen) {
                    const formData = crearFormData(datos, imagen);
                    await axios.post("/api/events", formData);
                } else {
                    await axios.post("/api/events", datos);
                }

                toast.success("Event created successfully");
                router.push({ name: "events.index" });
            } catch (e) {
                handleRequestError(e);
            }
        });
    };

    const updateEvent = async (id, datos, imagen = null) => {
        clearErrors();
        const resultadoValidacion = await validate(eventSchema, datos);
        if (!resultadoValidacion.isValid) return false;

        const datosLimpios = limpiarDatos(datos);

        return await withLoading(async () => {
            try {
                if (imagen) {
                    const formData = crearFormData(datosLimpios, imagen);
                    formData.append("_method", "PUT");
                    await axios.post(`/api/events/${id}`, formData);
                } else {
                    await axios.put(`/api/events/${id}`, datosLimpios);
                }

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
        pagination,
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
