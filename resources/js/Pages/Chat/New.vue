<script setup>
import ChatLayout from "@/Layouts/ChatLayout.vue";
import Sidebar from "@/Components/Sidebar/Sidebar.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import Multiselect from 'vue-multiselect';
import {ref} from "vue";
import {useForm} from "@inertiajs/vue3";

const props = defineProps({
    chats: {
        required: true
    },
    users: {
        type: Array,
        default: () => []
    }
})
const value = ref([]);

const options = ref(props.users.map(user => ({
    name: user.name,
    code: user.id,
    avatar: user.avatar // Assuming the avatar URL is part of the user object
})));

const form = useForm({
    users: []
})

const createChat = () => {
    form.users = value.value.map(user => user.code);
    form.post(route('chat.new'), {
        onSuccess: () => {
            form.reset();
            value.value = [];
        }
    });
}
</script>

<template>
    <ChatLayout>
        <template v-slot:sidebar>
            <Sidebar :chats="chats"/>
        </template>

        <div class="flex">
            <multiselect v-model="value"
                         tag-placeholder="Add user to chat"
                         placeholder="Search or add user"
                         label="name"
                         :options="options"
                         :multiple="true"
                         :taggable="false"
                         track-by="code">
                <template #option="{ option }">
                    <div class="flex items-center">
                        <img :src="option.avatar" alt="" class="w-6 h-6 rounded-full mr-2">
                        <span>{{ option.name }}</span>
                    </div>
                </template>
                <template #single-label="{ option }">
                    <div class="flex items-center">
                        <img :src="option.avatar" alt="" class="w-6 h-6 rounded-full mr-2">
                        <span>{{ option.name }}</span>
                    </div>
                </template>
                <template #multiple-label="{ option }">
                    <div class="flex items-center">
                        <img :src="option.avatar" alt="" class="w-6 h-6 rounded-full mr-2">
                        <span>{{ option.name }}</span>
                    </div>
                </template>
            </multiselect>
            <PrimaryButton v-on:click="createChat">
                +
            </PrimaryButton>
        </div>
    </ChatLayout>
</template>
<style src="node_modules/vue-multiselect/dist/vue-multiselect.css"></style>
