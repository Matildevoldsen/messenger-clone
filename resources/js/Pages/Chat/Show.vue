<script setup>
import ChatLayout from "@/Layouts/ChatLayout.vue";
import Sidebar from "@/Components/Sidebar/Sidebar.vue";
import Messages from "@/Components/Chat/Messages.vue";
import SendMessage from "@/Components/Chat/SendMessage.vue";
import Message from "@/Components/Chat/Message.vue";
import {onMounted, ref} from "vue";

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

onMounted(() => {
    window.Echo.private(`chats.${props.chat.id}`)
        .listen('MessageSent', (e) => {
            console.log(e.message)
            messageList.value.push(e.message)
        })
})
</script>

<template>
    <ChatLayout>
        <template v-slot:sidebar>
            <Sidebar :chats="chats"/>
        </template>

        <div class="flex flex-col flex-grow overflow-hidden py-6 px-4 sm:px-6 lg:pl-8 xl:pl-6">
            <div class="flex-grow overflow-auto space-y-5 pb-24"
                v-if="messageList"
            >
                <Messages>
                    <template v-for="message in messageList" :key="message.id">
                        <Message :message="message" :sender="$page.props.auth.user.id === message?.user?.id" />
                    </template>
                </Messages>

                <div class="text-center" v-if="messages.length === 0">
                    This looks rather empty...
                </div>
            </div>

            <SendMessage :chat="chat"/>
        </div>
    </ChatLayout>
</template>
