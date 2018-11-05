import Vue from 'vue';
import Base from './base';
import {Bus} from './bus.js';
import Routes from './routes';
import VueRouter from 'vue-router';
import VueTextareaAutosize from 'vue-textarea-autosize';

require('bootstrap');

Vue.use(VueRouter);
Vue.use(VueTextareaAutosize);

window.Popper = require('popper.js').default;

const router = new VueRouter({
    routes: Routes,
    mode: 'history',
    base: '/wink',
});

Vue.component('alert', require('./components/Alert.vue'));
Vue.component('notification', require('./components/Notification.vue'));
Vue.component('page-header', require('./components/PageHeader.vue'));
Vue.component('mini-editor', require('./components/MiniEditor.vue'));
Vue.component('editor', require('./components/Editor.vue'));
Vue.component('form-errors', require('./components/FormErrors.vue'));
Vue.component('image-picker', require('./components/ImagePicker.vue'));
Vue.component('date-time-picker', require('./components/DateTimePicker.vue'));
Vue.component('multiselect', require('./components/MultiSelect.vue'));
Vue.directive('loading', require('./components/loadingButton'));
Vue.directive('click-outside', require('./components/clickOutside'));

Vue.mixin(Base);

new Vue({
    el: '#wink',

    router,

    data(){
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

    mounted(){
        Bus.$on('httpError', message => this.alertError(message));
    },

    methods: {}
});
