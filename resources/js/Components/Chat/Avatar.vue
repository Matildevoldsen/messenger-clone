<script setup>
defineProps({
    user: {
        required: true
    }
})
</script>
<template>
    <img :src="user?.avatar"
         class="inline-block size-9 rounded-full"
         :alt="user?.name">
</template>
