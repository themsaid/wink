<script type="text/ecmascript-6">

    export default {
        computed:{
            hideLogoOnSmallScreens(){
                return this.$slots['left-side']
            }
        }
    }
</script>

<template>
    <div id="pageHeader">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="d-flex align-items-center py-2">
                        <div class="mr-auto d-flex align-items-center">
                            <h5 class="mb-0 mr-4 logo" :class="{'d-none': hideLogoOnSmallScreens, 'd-sm-block': hideLogoOnSmallScreens}">
                                <span class="text-secondary">W</span>ink.
                            </h5>
                            <slot name="left-side"></slot>
                        </div>

                        <slot name="right-side"></slot>

                        <div class="dropdown ml-3">
                            <button type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <img :src="Config.author.avatar" class="avatar" :title="Config.author.name">
                            </button>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                                <router-link :to="{name:'team-edit', params:{id: Config.author.id}}" class="dropdown-item">
                                    Profile
                                </router-link>
                                <div class="dropdown-divider"></div>
                                <router-link to="/posts" class="dropdown-item">
                                    Posts
                                </router-link>
                                <router-link to="/pages" class="dropdown-item">
                                    Pages
                                </router-link>

                                <router-link to="/tags" class="dropdown-item">
                                    Tags
                                </router-link>

                                <router-link to="/team" class="dropdown-item">
                                    Team
                                </router-link>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="/wink/logout">Log out</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
