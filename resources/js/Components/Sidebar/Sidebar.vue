<script setup>
import {Link, usePage, router} from "@inertiajs/vue3";
import SidebarItem from "@/Components/Sidebar/SidebarItem.vue";
import {onMounted, ref, watch} from "vue";

const props = defineProps({
    chats: {
        required: true
    },
    chat: {
        required: true
    }
})

const chatList = ref([...props.chats])
watch(() => props.chats, (newChats) => {
    chatList.value = [...newChats]
})
onMounted(() => {
    updateChats();

    newChat();
});

function updateChats() {
    chatList.value.forEach(chat => {
        window.Echo.private(`chats.${chat.id}`)
            .listen('ChatUpdated', (e) => {
                const updatedChat = e.chat;

                // Find and update the chat in the list
                const index = chatList.value.findIndex(c => c.id === updatedChat.id);
                if (index !== -1) {
                    chatList.value[index] = updatedChat;

                    // Move updated chat to the top
                    chatList.value = [
                        chatList.value[index],
                        ...chatList.value.filter(c => c.id !== updatedChat.id)
                    ];
                }
            })
            .listen('DeletedChat', (e) => {
                const deletedChatId = e.chat.id;

                const index = chatList.value.findIndex(chat => chat.id === deletedChatId);

                if (index !== -1) {
                    chatList.value.splice(index, 1);
                }

                if (deletedChatId === props.chat.id) {
                    router.replace(route('chat.new.show'))
                }
            });
    });
}

function newChat() {
    window.Echo.private(`App.Models.User.${usePage().props.auth.user.id}`)
        .listen('NewChat', (e) => {
            chatList.value.push(e.chat)
        });
}
</script>
<template>
    <div class="max-w-xs xl:w-64 xl:shrink-0 xl:border-b-0 xl:border-r flex-col border-b border-gray-200">
        <div class="flex p-3 justify-between">
            <h1>Chats</h1>


            <Link :href="route('chat.new.show')">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                     stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10"/>
                </svg>
            </Link>
        </div>

        <input type="search"
               ref="searchInput"
               placeholder="Search chats.."
               class="border-gray-300 w-full dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 shadow-sm">

        <template v-for="chat in chatList" :key="chat.id">
            <SidebarItem :chat="chat" />
        </template>
    </div>
</template>
