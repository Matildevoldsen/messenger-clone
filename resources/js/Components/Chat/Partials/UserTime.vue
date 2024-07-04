<script setup>
defineProps({
    message: {
        required: true
    }
})
</script>
<template>
    <span class="text-sm ml-2 mb-1 text-gray-500">
        {{ message?.user?.name }} - <time>{{ message?.created_at }}</time>
    </span>
</template>
