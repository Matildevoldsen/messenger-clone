<script setup>
import {Link} from "@inertiajs/vue3";
defineProps({
    attachments: {
        required: true,
        type: Array
    }
})

function isImage(type) {
    return type && type.startsWith('image/');
}
</script>
<template>
    <div class="flex flex-wrap gap-2">
        <div v-for="(file, index) in attachments" :key="index" class="relative">
            <img v-if="isImage(file.type)" :src="file.path" :alt="`Image ${index}`" class="w-32 h-32 object-cover rounded-lg" />
            <div v-else>
                <Link :href="`/storage/${file.path}`" class="text-blue-600" target="_blank">
                    {{ file.path.split('/').pop() }} ({{ file.type }})
                </Link>
            </div>
        </div>
    </div>
</template>
