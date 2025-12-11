<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
  variant: { type: String, default: 'card' },
  imageSrc: { type: String, default: null },
  imageAlt: { type: String, default: '' },
});
</script>

<template>
  <div class="min-h-screen bg-gray-100 flex items-center justify-center">
    <div v-if="props.variant === 'split'" class="w-full max-w-5xl mt-6 grid grid-cols-1 md:grid-cols-2">
      <div v-if="props.imageSrc" class="hidden md:block">
        <img :src="props.imageSrc" :alt="props.imageAlt" class="h-full w-full object-cover rounded-l-lg shadow-md" />
      </div>
      <div class="bg-white shadow-md overflow-hidden md:rounded-r-lg rounded-lg px-6 py-4">
        <div class="flex justify-center mb-4">
          <Link href="/">
            <ApplicationLogo class="w-16 h-16 fill-current text-gray-500" />
          </Link>
        </div>
        <slot />
      </div>
    </div>

    <div v-else-if="props.variant === 'overlay'" class="relative w-full sm:max-w-md mt-6">
      <div v-if="props.imageSrc" class="absolute inset-0 -z-10">
        <img :src="props.imageSrc" :alt="props.imageAlt" class="h-full w-full object-cover rounded-lg opacity-60" />
      </div>
      <div class="px-6 py-4 bg-white/80 backdrop-blur-sm shadow-md overflow-hidden sm:rounded-lg">
        <div class="flex justify-center mb-4">
          <Link href="/">
            <ApplicationLogo class="w-16 h-16 fill-current text-gray-500" />
          </Link>
        </div>
        <slot />
      </div>
    </div>

    <div v-else class="flex flex-col sm:justify-center items-center pt-6 sm:pt-0 w-full">
      <div>
        <Link href="/">
          <ApplicationLogo class="w-20 h-20 fill-current text-gray-500" />
        </Link>
      </div>
      <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
        <slot />
      </div>
    </div>
  </div>
</template>
