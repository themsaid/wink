import Vue from 'vue';
import Base from './base';
import {Bus} from './bus.js';
import Routes from './routes';
import VueRouter from 'vue-router';
import VueTextareaAutosize from 'vue-textarea-autosize';

Vue.use(VueRouter);
Vue.use(VueTextareaAutosize);

const router = new VueRouter({
    routes: Routes,
    mode: 'history',
    base: '/' + Wink.path,
});

Vue.component('page-header', require('./components/PageHeader'));
Vue.component('preloader', require('./partials/Preloader'));

Vue.component('alert', require('./components/Alert'));
Vue.component('dropdown', require('./components/DropDown'));
Vue.component('modal', require('./components/Modal'));
Vue.component('fullscreen-modal', require('./components/FullscreenModal'));
Vue.component('notification', require('./components/Notification'));
Vue.component('mini-editor', require('./components/MiniEditor'));
Vue.component('editor', require('./components/Editor'));
Vue.component('form-errors', require('./components/FormErrors'));
Vue.component('image-picker', require('./components/ImagePicker'));
Vue.component('date-time-picker', require('./components/DateTimePicker'));
Vue.component('multiselect', require('./components/MultiSelect'));
Vue.directive('loading', require('./components/loadingButton'));
Vue.directive('click-outside', require('./components/clickOutside'));

Vue.mixin(Base);

new Vue({
    el: '#wink',

    router,

    data() {
        return {
            alert: {
                type: null,
                autoClose: 0,
                message: '',
                confirmationProceed: null,
                confirmationCancel: null,
            },

            notification: {
                type: null,
                autoClose: 0,
                message: ''
            }
        }
    },

    mounted() {
        Bus.$on('httpError', message => this.alertError(message));
    },

    methods: {}
});
