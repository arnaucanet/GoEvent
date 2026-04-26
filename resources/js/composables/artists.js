import { ref } from "vue";
import * as yup from "yup";
import axios from "axios";
import { useToast } from "./useToast";
import { useValidation } from "./useValidation";

export default function useArtists() {
    const artists = ref([]);
    const artistList = ref([]);
    const initialArtist = {
        id: null,
        name: "",
        description: "",
        image: "",
        active: true,
    };
    const artist = ref({ ...initialArtist });
    const isLoading = ref(false);
    const toast = useToast();
    const { errors, validate, handleRequestError, clearErrors, hasError, getError } = useValidation();

    const artistSchema = yup.object({
        name: yup.string().trim().required("El nombre es obligatorio").min(2),
    });

    const withLoading = async (fn) => {
        if (isLoading.value) return;
        isLoading.value = true;
        try { return await fn(); } finally { isLoading.value = false; }
    };

    const getArtists = async (params = {}) => {
        return withLoading(async () => {
            try {
                const { data } = await axios.get("/api/artists", { params });
                artists.value = data?.data ?? data;
            } catch { toast.error("Error cargando artistas"); }
        });
    };

    const getArtistList = async () => {
        const { data } = await axios.get("/api/artists/list");
        artistList.value = data?.data ?? data ?? [];
        return artistList.value;
    };

    const createArtist = async (data) => {
        clearErrors();
        const { isValid } = await validate(artistSchema, data);
        if (!isValid) return false;
        return withLoading(async () => {
            try {
                await axios.post("/api/artists", data);
                toast.success("Artista creado");
                return true;
            } catch (e) { handleRequestError(e); return false; }
        });
    };

    const updateArtist = async (id, data) => {
        clearErrors();
        const { isValid } = await validate(artistSchema, data);
        if (!isValid) return false;
        return withLoading(async () => {
            try {
                await axios.put(`/api/artists/${id}`, data);
                toast.success("Artista actualizado");
                return true;
            } catch (e) { handleRequestError(e); return false; }
        });
    };

    const destroyArtist = async (id) => {
        return withLoading(async () => {
            try {
                await axios.delete(`/api/artists/${id}`);
                toast.success("Artista eliminado");
                await getArtists();
            } catch { toast.error("No se pudo eliminar el artista"); }
        });
    };

    const resetArtist = () => { artist.value = { ...initialArtist }; clearErrors(); };

    return {
        artists, artist, artistList, isLoading,
        errors, hasError, getError,
        getArtists, getArtistList,
        createArtist, updateArtist, destroyArtist, resetArtist,
    };
}
