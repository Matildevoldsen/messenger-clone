<template>
    <div class="mx-auto h-screen w-full max-w-7xl grow lg:flex xl:px-2">
        <div class="flex">
            <slot name="sidebar"/>
        </div>
        <div class="flex min-h-full flex-col flex-grow overflow-auto">
            <slot />
        </div>
    </div>
</template>
