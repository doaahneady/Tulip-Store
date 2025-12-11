<script setup>
import { onMounted, ref } from 'vue';

const model = defineModel({
    type: String,
    required: true,
});

const input = ref(null);

onMounted(() => {
    if (input.value.hasAttribute('autofocus')) {
        input.value.focus();
    }
});

defineExpose({ focus: () => input.value.focus() });
</script>

<template>
    <input
        class="w-full border-b-2 border-gray-300 rounded-none focus:outline-none focus:ring-0 focus:border-indigo-500 transition-colors"
        v-model="model"
        ref="input"
    />
</template>
