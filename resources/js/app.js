import Vue from 'vue';
import Base from './base';
import {Bus} from './bus.js';
import Routes from './routes';
import VueRouter from 'vue-router';
import VueTextareaAutosize from 'vue-textarea-autosize';
import VueCroppie from 'vue-croppie';
import 'croppie/croppie.css'


Vue.use(VueRouter);
Vue.use(VueTextareaAutosize);
Vue.use(VueCroppie);

const router = new VueRouter({
    routes: Routes,
    mode: 'history',
    base: '/' + Wink.path,
});

Vue.component('page-header', require('./components/PageHeader').default);
Vue.component('preloader', require('./partials/Preloader').default);

Vue.component('alert', require('./components/Alert').default);
Vue.component('dropdown', require('./components/DropDown').default);
Vue.component('modal', require('./components/Modal').default);
Vue.component('fullscreen-modal', require('./components/FullscreenModal').default);
Vue.component('notification', require('./components/Notification').default);
Vue.component('mini-editor', require('./components/MiniEditor').default);
Vue.component('editor', require('./components/Editor').default);
Vue.component('form-errors', require('./components/FormErrors').default);
Vue.component('image-picker', require('./components/ImagePicker').default);
Vue.component('cropper-modal', require('./components/CropperModal').default);
Vue.component('date-time-picker', require('./components/DateTimePicker').default);
Vue.component('multiselect', require('./components/MultiSelect').default);
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
