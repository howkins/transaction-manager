require('./bootstrap');

Vue.component('transactions', require('./components/Transactions.vue').default);
Vue.component('transaction-field', require('./components/TransactionField.vue').default);

Vue.domain = 'http://howkins.eu/';

const app = new Vue({
    el: '#app'
})