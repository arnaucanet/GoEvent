import { ref } from "vue";
import * as yup from "yup";
import axios from "axios";
import { useToast } from "./useToast";
import { useValidation } from "./useValidation";

export default function useVenues() {
    const venues = ref([]);
    const venueList = ref([]);
    const cityOptions = ref([]);
    const initialVenue = {
        id: null,
        name: "",
        address: "",
        city: "",
        capacity: 100,
        active: true,
    };
    const venue = ref({ ...initialVenue });
    const isLoading = ref(false);
    const toast = useToast();
    const { errors, validate, handleRequestError, clearErrors, hasError, getError } = useValidation();

    const venueSchema = yup.object({
        name: yup.string().trim().required("El nombre es obligatorio").min(2),
        address: yup.string().trim().required("La dirección es obligatoria"),
        city: yup.string().trim().required("La ciudad es obligatoria"),
        capacity: yup.number().typeError("Aforo inválido").required().min(1),
    });

    const withLoading = async (fn) => {
        if (isLoading.value) return;
        isLoading.value = true;
        try { return await fn(); } finally { isLoading.value = false; }
    };

    const getVenues = async (params = {}) => {
        return withLoading(async () => {
            try {
                const { data } = await axios.get("/api/venues", { params });
                venues.value = data?.data ?? data;
            } catch { toast.error("Error cargando recintos"); }
        });
    };

    const getVenueList = async () => {
        const { data } = await axios.get("/api/venues/list");
        venueList.value = data?.data ?? data ?? [];
        return venueList.value;
    };

    const getCities = async () => {
        const { data } = await axios.get("/api/venues/cities");
        const list = data?.data ?? data ?? [];
        cityOptions.value = list.map((c) => ({ label: c.name, value: c.name }));
        return cityOptions.value;
    };

    const createVenue = async (data) => {
        clearErrors();
        const { isValid } = await validate(venueSchema, data);
        if (!isValid) return false;
        return withLoading(async () => {
            try {
                await axios.post("/api/venues", data);
                toast.success("Recinto creado");
                return true;
            } catch (e) { handleRequestError(e); return false; }
        });
    };

    const updateVenue = async (id, data) => {
        clearErrors();
        const { isValid } = await validate(venueSchema, data);
        if (!isValid) return false;
        return withLoading(async () => {
            try {
                await axios.put(`/api/venues/${id}`, data);
                toast.success("Recinto actualizado");
                return true;
            } catch (e) { handleRequestError(e); return false; }
        });
    };

    const destroyVenue = async (id) => {
        return withLoading(async () => {
            try {
                await axios.delete(`/api/venues/${id}`);
                toast.success("Recinto eliminado");
                await getVenues();
            } catch { toast.error("No se pudo eliminar el recinto"); }
        });
    };

    const resetVenue = () => { venue.value = { ...initialVenue }; clearErrors(); };

    return {
        venues, venue, venueList, cityOptions, isLoading,
        errors, hasError, getError,
        getVenues, getVenueList, getCities,
        createVenue, updateVenue, destroyVenue, resetVenue,
    };
}
