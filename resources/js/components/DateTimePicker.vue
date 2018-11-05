<script type="text/ecmascript-6">
    import _ from 'lodash';
    import moment from 'moment';

    export default {
        props: ['value'],

        data(){
            return {
                dateComponents: {
                    day: '',
                    month: '',
                    year: '',
                    hour: '',
                    minute: '',
                },

                result: ''
            }
        },


        mounted() {
        },


        watch: {
            value(val){
                let date = moment(val + ' Z').utc();

                this.dateComponents = {month: date.format('MM'), day: date.format('DD'), year: date.format('YYYY'), hour: date.format('HH'), minute: date.format('mm')};
            },


            dateComponents: {
                handler: function () {
                    this.result = this.dateComponents.year + '-' + this.dateComponents.month + '-' + this.dateComponents.day + ' ' + this.dateComponents.hour + ':' + this.dateComponents.minute + ':00';

                    this.$emit('input', this.result);
                },
                deep: true
            },
        },


        computed: {},


        methods: {}
    }
</script>

<template>
    <div>
        <div class="d-flex flex-row">
            <select class="inline-form-control text-body-color pr-2"
                    v-model="dateComponents.month">
                <option v-for="value in Array.from({length: 12}, (_, i) => String(i + 1).padStart(2, '0'))" :value="value">{{value}}</option>
            </select>
            <span class="px-1">/</span>
            <select class="inline-form-control text-body-color px-2"
                    v-model="dateComponents.day">
                <option v-for="value in Array.from({length: 31}, (_, i) => String(i + 1).padStart(2, '0'))" :value="value">{{value}}</option>
            </select>
            <span class="px-1">/</span>
            <select class="inline-form-control text-body-color px-2"
                    v-model="dateComponents.year">
                <option v-for="value in Array.from({length: 5}, (_, i) => i + (new Date()).getFullYear())" :value="value">{{value}}</option>
            </select>
            <span class="pl-5"> </span>
            <select class="inline-form-control text-body-color px-2"
                    v-model="dateComponents.hour">
                <option v-for="value in Array.from({length: 24}, (_, i) => String(i).padStart(2, '0'))" :value="value">{{value}}</option>
            </select>
            <span class="px-1">:</span>
            <select class="inline-form-control text-body-color pl-2"
                    v-model="dateComponents.minute">
                <option v-for="value in Array.from({length: 60}, (_, i) => String(i).padStart(2, '0'))" :value="value">{{value}}</option>
            </select>
        </div>
    </div>
</template>

<style scoped>
    select {
        width: auto;
    }
</style>
