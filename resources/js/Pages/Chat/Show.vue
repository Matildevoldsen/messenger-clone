<script setup>
import ChatLayout from "@/Layouts/ChatLayout.vue";
import Sidebar from "@/Components/Sidebar/Sidebar.vue";
import Messages from "@/Components/Chat/Messages.vue";
import SendMessage from "@/Components/Chat/SendMessage.vue";
import Message from "@/Components/Chat/Message.vue";
import {nextTick, onMounted, ref} from "vue";
import {usePage} from "@inertiajs/vue3";
import TopBar from "@/Components/Chat/TopBar.vue";

const props = defineProps({
    chat: {
        required: true
    },
    chats: {
        required: true
    },
    messages: {
        required: true
    }
})

const messageList = ref([...props.messages.data.reverse()]);
const typingUsers = ref([]);
onMounted(() => {
    nextTick(() => {
        const container = document.querySelector('.message-container');
        container.scrollTop = container.scrollHeight;
    })
    window.Echo.private(`chats.${props.chat.id}`)
        .listen('MessageSent', (e) => {
            messageList.value.push(e.message)
        }).listen('UserTyping', (e) => {
            updateTypingIndicator(e)
        })
})

function updateTypingIndicator(e) {
    const currentUser = usePage().props.auth.user;

    if (e.user.id !== currentUser.id) {
        if (!typingUsers.value.some(user => user.id === e.user.id)) {
            typingUsers.value.push(e.user);
        }
    }

    setTimeout(() => {
        typingUsers.value = typingUsers.value.filter(user => user.id !== e.user.id);
    }, 3000);
}
</script>

<template>
    <ChatLayout>
        <template v-slot:sidebar>
            <Sidebar :chat="chat" :chats="chats"/>
        </template>

        <TopBar :chat="chat" />
        <div class="flex flex-col flex-grow overflow-hidden py-6 px-4 sm:px-6 lg:pl-8 xl:pl-6">
            <div class="message-container flex-grow overflow-auto space-y-5 pb-24"
                v-if="messageList"
            >
                <Messages>
                    <template v-for="message in messageList" :key="message.id">
                        <Message :message="message" :sender="$page.props.auth.user.id === message?.user?.id" />
                    </template>
                    <div v-if="typingUsers.length" class="flex ms-auto justify-end gap-x-2 sm:gap-x-4">
                        <div class="flex items-center space-x-1 bg-blue-600 p-2 rounded-full">
                            <div class="dot bg-gray-300"></div>
                            <div class="dot bg-gray-300"></div>
                            <div class="dot bg-gray-300"></div>
                        </div>
                        <span v-for="user in typingUsers" :key="user.id">{{ user.name }} is typing...</span>
                    </div>
                </Messages>

                <div class="text-center" v-if="messages.length === 0">
                    This looks rather empty...
                </div>
            </div>

            <SendMessage :chat="chat"/>
        </div>
    </ChatLayout>
</template>
<style scoped>
.dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    animation: typing 1s infinite ease-in-out;
}

.dot:nth-child(1) {
    animation-delay: 0s;
}

.dot:nth-child(2) {
    animation-delay: 0.2s;
}

.dot:nth-child(3) {
    animation-delay: 0.4s;
}

@keyframes typing {
    0%, 60%, 100% {
        transform: translateY(0);
    }
    30% {
        transform: translateY(-4px);
    }
}
</style>
