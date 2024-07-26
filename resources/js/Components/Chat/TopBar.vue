<script setup>
import Dropdown from "@/Components/Dropdown.vue";
import DropdownLink from "@/Components/DropdownLink.vue";
import {useForm} from "@inertiajs/vue3";
const props = defineProps({
    chat: {
        required: true
    }
})

const form = useForm({})
const deleteChat = () => {
    form.delete(route('chat.delete', props.chat), {
        onSuccess() {
            console.log('deleted')
        }
    })
}
</script>
<template>
    <div class="flex justify-between border-b-2 w-full p-2 mt-0">
        <div class="flex-shrink-0 group block">
            <div class="flex items-center">
                <div class="-space-x-4">
                    <template v-if="chat.users.length > 1" v-for="user in chat.users" :key="user.id">
                        <img class="relative z-30 inline object-cover w-12 h-12 border-2 border-white rounded-full"
                             :src="user.avatar"
                             :alt="`Profile image of ${user.name}`" />
                    </template>
                </div>
                <template v-if="chat.users.length === 1">
                    <img :src="chat.users[0].avatar"
                         class="inline-block flex-shrink-0 size-[62px] rounded-full"
                         :alt="`Profile picture of ${chat.users[0].name}`">
                    <div class="ms-3">
                        <h3 class="font-semibold text-gray-800">
                            {{ chat.users[0].name }}
                        </h3>
                        <p class="text-sm font-medium text-gray-400 dark:text-neutral-500">Last Active 2m ago</p>
                    </div>
                </template>
            </div>
        </div>
        <div class="items-center flex">
            <Dropdown align="right" width="48">
                <template #trigger>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5ZM12 12.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5ZM12 18.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5Z" />
                    </svg>
                </template>

                <template #content>
                    <DropdownLink v-on:click="deleteChat">
                        Delete Chat
                    </DropdownLink>
                </template>
            </Dropdown>
        </div>
    </div>
</template>
