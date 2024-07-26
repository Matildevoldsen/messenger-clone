<script setup>
defineProps({
    message: {
        required: true
    }
})
</script>
<template>
    <div v-if="message.content">{{ message.content }}</div>
</template>
