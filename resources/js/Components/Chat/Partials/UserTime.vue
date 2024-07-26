<script setup>
defineProps({
    message: {
        required: true
    }
})
</script>
<template>
    <span class="text-sm ml-2 mb-1 text-gray-500">
        {{ message?.user?.name }} -
        <time :datetime="message?.created_at.datetime" :title="message?.created_at.datetime">
            {{ message?.created_at.human }}
        </time>
    </span>
</template>
