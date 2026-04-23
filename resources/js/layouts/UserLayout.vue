<template>
    <MainLayout :menuItems="items" />
</template>

<script setup>
import { ref } from 'vue';
import MainLayout from './MainLayout.vue';

const items = ref([
    {
        label: 'Cuenta',
        items: [
            {
                label: 'Perfil',
                icon: 'pi pi-user',
                route: '/app/profile'
            }
        ]
    },
    {
        label: 'Entradas',
        items: [
            {
                label: 'Próximos eventos',
                icon: 'pi pi-ticket',
                route: '/orders'
            },
            {
                label: 'Eventos pasados',
                icon: 'pi pi-history',
                route: '/orders/past-events'
            }
        ]
    }
]);
</script>
