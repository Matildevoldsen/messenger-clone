<script setup>
import UserTime from "@/Components/Chat/Partials/UserTime.vue";

defineProps({
    sender: {
        type: Boolean
    },
    message: {
        type: Object,
        required: true
    }
})
</script>
<template>
    <div>
        <li v-if="sender && (message.content || message.attachments)"
            class="max-w-lg flex gap-x-2 sm:gap-x-4 me-11">
            <img :src="message?.user?.avatar"
                 class="inline-block size-9 rounded-full"
                 :alt="message?.user?.name">

            <div class="flex flex-col">
                <UserTime :message="message" />

                <div class="bg-gray-100 rounded-2xl border-border-gray-200 roundex-2xl p-4 space-y-3 dark:bg-neutral-900 dark:border-neutral-700">
                    <div v-if="message.content">{{ message.content }}</div>
                </div>
            </div>
        </li>
        <li v-if="!sender && (message.content || message.attachments)"
            class="max-w-lg justify-end flex ms-auto gap-x-2 sm:gap-x-4">
            <div class="flex flex-col text-end justify-end space-y-3">
                <div class="flex flex-col text-end justify-end">
                    <UserTime :message="message" />

                    <div class="inline-block max-w-lg text-white bg-blue-600 rounded-2xl p-4 shadow-sm">
                        <div v-if="message.content">{{ message.content }}</div>
                    </div>
                </div>
            </div>

            <img class="inline-block size-9 rounded-full"
                 :src="message?.user?.avatar"
                 :alt="message?.user?.name">
        </li>
    </div>
</template>
