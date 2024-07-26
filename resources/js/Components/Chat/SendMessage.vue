<script setup>
import {useForm, usePage} from "@inertiajs/vue3";
import { ref } from "vue";
import EmojiPicker from 'vue3-emoji-picker';

const props = defineProps({
    chat: {
        required: true
    }
})

const showEmojis = ref(false)

function sendMessage() {
    if (form.content.trim === '' && form.attachments.length === 0) return;

    form.post(route('message.send', props.chat), {
        preserveScroll: true,
        onSuccess: () => {
            form.content = '';
            form.attachments = []
        },
        onError: () => console.log('error')
    });
}

function handleFileUpload (event) {
    console.log(event)
    form.attachments = Array.from(event.target.files).filter(file => file.type);
    console.log(form.attachments)
}


const form = useForm({
    content: '',
    attachments: []
})

function isImage(type) {
    return type && type.startsWith('image/');
}

function renderImage(file) {
    return URL.createObjectURL(file);
}

function onSelectEmoji(emoji) {
    form.content += emoji.i;

    showEmojis.value = false;
}

function removeAttachment(index) {
    form.attachments.splice(index, 1);
}

function handleKeydown(event) {
    if ((event.ctrlKey || event.metaKey) && event.shiftKey && event.key === 'Enter') {
        sendMessage();
        event.preventDefault();
    }

    if (event.key === 'Enter' && !event.shiftKey && !event.ctrlKey && !event.metaKey) {
        event.preventDefault();
        form.content += '\n';
    }
}

function userIsTyping() {
    window.Echo.private(`chats.${props.chat.id}`).whisper('typing', {
        user: usePage().props.auth.user
    });

    form.put(route('chat.user.typing', props.chat), {
        is_typing: true
    })
}
</script>
<template>
    <div class="sticky bottom-0 bg-white p-4">
        <div v-if="form.attachments.length" class="flex space-x-2 mb-2">
            <div class="relative" v-for="(file, index) in form.attachments" :key="index">
                <img v-if="isImage(file.type)"
                     :src="renderImage(file)"
                     class="w-24 h-24 object-cover rounded-lg"
                     :alt="`Preview of image ${file.name}`">

                <button class="absolute top-0 right-0 text-white rounded-full py-0.5 px-1.5 text-sm bg-red-400 dark:bg-red-500">
                    x
                </button>
            </div>
        </div>
        <div class="relative">
            <textarea name="message"
                      v-model="form.content"
                      @keydown="handleKeydown"
                      @input="userIsTyping"
                      placeholder="What's up?"
                      class="p-4 pb-12 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600"
                      id="message"></textarea>

            <div class="absolute bottom-px inset-x-px p-2 rounded-b-md bg-white dark:bg-neutral-900">
                <div class="flex justify-between items-center">
                    <div class="flex items-center relative">
                        <svg v-on:click="showEmojis = !showEmojis" xmlns="http://www.w3.org/2000/svg" fill="none"
                             viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 cursor-pointer">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M15.182 15.182a4.5 4.5 0 0 1-6.364 0M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0ZM9.75 9.75c0 .414-.168.75-.375.75S9 10.164 9 9.75 9.168 9 9.375 9s.375.336.375.75Zm-.375 0h.008v.015h-.008V9.75Zm5.625 0c0 .414-.168.75-.375.75s-.375-.336-.375-.75.168-.75.375-.75.375.336.375.75Zm-.375 0h.008v.015h-.008V9.75Z"/>
                        </svg>

                        <div class="absolute bottom-12 left-0 bg-white border border-gray-200 rounded-lg shadow-lg"
                            v-if="showEmojis">
                            <EmojiPicker @select="onSelectEmoji" :native="true" />
                        </div>

                        <input type="file" id="attachments" accept="image/*" multiple @change="handleFileUpload" class="hidden">
                        <label for="attachments"
                               class="inline-flex flex-shrink-0 justify-center items-center size-8 rounded-lg text-gray-500 hover:text-blue-600 focus:z-10 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:text-neutral-500 dark:hover:text-blue-500">
                            <svg class="flex-shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24"
                                 height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                 stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path
                                    d="m21.44 11.05-9.19 9.19a6 6 0 0 1-8.49-8.49l8.57-8.57A4 4 0 1 1 18 8.84l-8.59 8.57a2 2 0 0 1-2.83-2.83l8.49-8.48"></path>
                            </svg>
                        </label>
                    </div>

                    <div class="flex items-center gap-x-1">
                        <button @click="sendMessage" type="button"
                                class="inline-flex flex-shrink-0 justify-center items-center size-8 rounded-lg text-white bg-blue-600 hover:bg-blue-500 focus:z-10 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <svg class="flex-shrink-0 size-3.5" xmlns="http://www.w3.org/2000/svg" width="16"
                                 height="16" fill="currentColor" viewBox="0 0 16 16">
                                <path
                                    d="M15.964.686a.5.5 0 0 0-.65-.65L.767 5.855H.766l-.452.18a.5.5 0 0 0-.082.887l.41.26.001.002 4.995 3.178 3.178 4.995.002.002.26.41a.5.5 0 0 0 .886-.083l6-15Zm-1.833 1.89L6.637 10.07l-.215-.338a.5.5 0 0 0-.154-.154l-.338-.215 7.494-7.494 1.178-.471-.47 1.178Z"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
