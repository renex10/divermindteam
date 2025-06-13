<script setup>
import DashboardLayout from '@/Layouts/DashboardLayout.vue';
// Importa tus componentes personalizados aquí
// import MiComponente from '@/Components/MiComponente.vue';
</script>

<template>
    <DashboardLayout title="Mi Dashboard">
        <template #header>
            <h1 class="text-2xl font-semibold text-gray-900">
                Mi Dashboard Personalizado
            </h1>
        </template>

        <!-- Tu contenido personalizado aquí -->
        <div class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Tus tarjetas/widgets -->
                <div class="bg-white p-6 rounded-lg shadow">
                    <h3 class="text-lg font-medium">Card 1</h3>
                    <p class="text-gray-600">Contenido de prueba</p>
                </div>
                
                <div class="bg-white p-6 rounded-lg shadow">
                    <h3 class="text-lg font-medium">Card 2</h3>
                    <p class="text-gray-600">Contenido de prueba</p>
                </div>
                
                <!-- Más contenido -->
            </div>
        </div>
    </DashboardLayout>
</template>