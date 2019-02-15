<script type="text/ecmascript">
    export default {
        props: ['isFiltered'],
    }
</script>

<template>
    <dropdown class="relative ml-4" @showing="$emit('showing')">
        <button slot="trigger" class="focus:outline-none text-light hover:text-primary h-8">
                        <span v-if="isFiltered"
                              class="block rounded-full pulse bg-red absolute w-3 h-3 pin-t pin-r opacity-75"></span>

            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" class="w-4 h-4 fill-current">
                <path d="M12.9 14.32a8 8 0 1 1 1.41-1.41l5.35 5.33-1.42 1.42-5.33-5.34zM8 14A6 6 0 1 0 8 2a6 6 0 0 0 0 12z"/>
            </svg>
        </button>

        <div slot="content" class="dropdown-content w-64 pin-r p-3">
            <slot/>
        </div>
    </dropdown>
</template>
