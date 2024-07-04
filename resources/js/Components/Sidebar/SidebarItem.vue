<script setup>
import {Link} from "@inertiajs/vue3";
defineProps({
    chat: {
        required: true
    }
})
</script>
<template>
    <Link :href="route('chat.show', chat)">
        <li :class="route().current('chat.show', chat) ? 'bg-gray-100' : ''" class="p-4 list-none border space-y-2">
            <div class="flex justify-between">
                <div class="flex space-x-2 5">
                    <img class="rounded-full w-6"
                         :src="chat?.last_message?.user?.avatar ?? $page.props.auth.user.profile_photo_url"
                         :alt="chat?.last_message?.user?.name ?? $page.props.auth.user.name">

                    <span>
                        {{ chat?.last_message?.user?.name ?? $page.props.auth.user.name }}
                    </span>
                </div>
            </div>
            <div class="flex justify-between">
                <p v-if="chat?.last_message?.content"
                   class="text-sm truncate text-neutral-800 dark:text-neutral-200">
                    {{ chat?.last_message?.content }}
                </p>
                <p class="text-sm truncate text-natural-800 dark:text-neutral-200"
                   v-if="chat?.last_message?.attachments && !chat?.last_message?.content">
                    Sent a file
                </p>
                <i class="text-sm truncate text-natural-800 dark:text-neutral-200"
                   v-if="!chat?.last_message?.attachments && !chat?.last_message?.content">
                    Draft...
                </i>
                <span class="relative inline-flex text-xs bg-indigo-500 text-white rounded-full py-0.5 px-1.5"
                      v-if="chat.unread_count > 0">
                    {{ chat.unread_count }}
                </span>
            </div>
        </li>
    </Link>
</template>
