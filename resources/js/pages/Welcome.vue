<script setup>
import { Link } from '@inertiajs/vue3';
import DefaultLayout from '@/layouts/DefaultLayout.vue';
</script>

<template>
    <DefaultLayout>


    <!-- Centered image and text -->
    <main class="flex-grow flex flex-col items-center justify-center px-4 text-center">
        <h2 class="text-3xl font-extrabold text-gray-900">Smart Pawn Services</h2>
        <p class="mt-4 text-lg text-gray-600">
            Get quick cash loans using your valuables as collateral.
            Simple, secure, and fair terms.
        </p>
      <img
        src="https://images.unsplash.com/photo-1601597111158-2fceff292cdc?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80"
        alt="Pawn service illustration"
        class="w-full max-w-md rounded-lg shadow-md mb-6"
      />

    </main>
    </DefaultLayout>
 
</template>
