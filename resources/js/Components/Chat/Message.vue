<script setup>
import UserTime from "@/Components/Chat/Partials/UserTime.vue";
import Avatar from "@/Components/Chat/Avatar.vue";
import MessageContent from "@/Components/Chat/MessageContent.vue";
import MessageAttachments from "@/Components/Chat/MessageAttachments.vue";

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
            <Avatar :user="message?.user" />

            <div class="flex flex-col">
                <UserTime :message="message" />

                <div class="bg-gray-100 inline-block w-fit max-w-fit rounded-2xl border-border-gray-200 roundex-2xl p-4 space-y-3 dark:bg-neutral-900 dark:border-neutral-700">
                    <MessageContent :message="message" />

                    <MessageAttachments v-if="message.attachments.length" :attachments="message.attachments" />
                </div>
            </div>
        </li>
        <li v-if="!sender && (message.content || message.attachments)"
            class="max-w-lg justify-end flex ms-auto gap-x-2 sm:gap-x-4">
            <div class="flex flex-col text-end justify-end space-y-3">
                <div class="flex flex-col text-end justify-end">
                    <UserTime :message="message" />

                    <div class="inline-block w-fit max-w-fit max-w-lg text-white bg-blue-600 rounded-2xl p-4 shadow-sm">
                        <MessageContent :message="message" />

                        <MessageAttachments :attachments="message.attachments" />
                    </div>
                </div>
            </div>

            <Avatar :user="message?.user" />
        </li>
    </div>
</template>
