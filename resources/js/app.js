require('./bootstrap');

require('./helpers');

import Vue from 'vue';

window.Vue = require('vue');

window.moment = require('moment');

window.eventBus = new Vue;

Vue.component('table-repo-component', require('./components/sample/TableRepoComponent.vue').default);

Vue.component('table-service-component', require('./components/sample/TableServiceComponent.vue').default);

Vue.component('info-repo-component', require('./components/sample/InfoRepoComponent.vue').default);

Vue.component('mange-repo-component', require('./components/sample/ManageRepoComponent.vue').default);

Vue.component('table-sample-component', require('./components/sample/TableSampleComponent.vue').default);

Vue.component('table-users-component', require('./components/sample/TableUsersComponent.vue').default);

Vue.component('modal-manage-ie-component', require('./components/sample/ModalManageIEComponent.vue').default);

Vue.component('modal-manage-csv-component', require('./components/sample/ModalManageCSVComponent.vue').default);

Vue.component('form-main-component', require('./components/config/FormMainComponent.vue').default);

window.axiosR = axios.create({

    baseURL : 'http://localhost:3000'

});

const app = new Vue({
    el: '#app'
});
