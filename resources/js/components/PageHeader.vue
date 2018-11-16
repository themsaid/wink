<script type="text/ecmascript-6">

    export default {
        computed: {
            hideLogoOnSmallScreens(){
                return this.$slots['left-side']
            }
        }
    }
</script>

<template>
    <div class="border-b border-very-light mb-10">
        <div class="container">
            <div class="flex items-center py-2">
                <div class="flex items-center mr-auto">
                    <h3 class="mr-5 font-semibold font-serif" :class="{'hidden': hideLogoOnSmallScreens, 'sm:block': hideLogoOnSmallScreens}">
                        <span class="text-light">W</span>ink.
                    </h3>

                    <slot name="left-side"></slot>
                </div>

                <div class="flex items-center">
                    <slot name="right-side"></slot>

                    <dropdown class="relative ml-6">
                        <button slot="trigger" type="button" class="focus:outline-none">
                            <img :src="Wink.author.avatar" class="rounded-full w-8" :title="Wink.author.name">
                        </button>

                        <div slot="content" class="bg-white border border-lighter rounded absolute z-50 whitespace-no-wrap min-w-dropdown pin-r mt-1 text-sm py-2">
                            <router-link :to="{name:'team-edit', params:{id: Wink.author.id}}" class="no-underline text-black font-sans hover:text-primary w-full block py-2 px-4 border-b border-very-light">
                                Profile
                            </router-link>
                            <router-link to="/posts" class="no-underline text-black hover:text-primary w-full block py-2 px-4">
                                Posts
                            </router-link>
                            <router-link to="/pages" class="no-underline text-black hover:text-primary w-full block py-2 px-4">
                                Pages
                            </router-link>

                            <router-link to="/tags" class="no-underline text-black hover:text-primary w-full block py-2 px-4">
                                Tags
                            </router-link>

                            <router-link to="/team" class="no-underline text-black hover:text-primary w-full block py-2 px-4">
                                Team
                            </router-link>
                            <a href="/wink/logout" class="no-underline text-black hover:text-primary w-full block py-2 px-4 border-t border-very-light">
                                Log out
                            </a>
                        </div>
                    </dropdown>
                </div>
            </div>
        </div>
    </div>
</template>
