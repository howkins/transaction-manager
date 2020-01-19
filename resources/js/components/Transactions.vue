<template>
    <div>
        <hr />
        <div class="row">
            <div class="col-6">
                <div class="input-group mb-3">
                    <input
                        v-model="search"
                        type="text"
                        class="form-control"
                        placeholder="Type here"
                        aria-label="Type here"
                        aria-describedby="search"
                    />
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="button" id="search">Search</button>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="btn-group float-right">
                    <button
                        type="button"
                        class="btn btn-danger dropdown-toggle"
                        data-toggle="dropdown"
                        aria-haspopup="true"
                        aria-expanded="false"
                    >Sort</button>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" @click="ChangeSort('id', 'asc')" href="#">ID Asc (default)</a>
                        <a class="dropdown-item" @click="ChangeSort('id', 'desc')" href="#">ID Desc</a>
                    </div>
                </div>
            </div>
        </div>
        <table class="table">
            <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">User(Balance)</th>
                <th scope="col">Type</th>
                <th scope="col">Amount</th>
            </tr>
            </thead>
            <tbody>
                <tr v-for="transaction in transactions" v-bind:class="'bg-'+transaction.color" v-bind:key="transaction.id">
                    <td>{{transaction.id}}</td>
                    <transaction-field v-bind:update="update" v-bind:save="save" type="name" v-bind:label="transaction.name+' ($'+transaction.balance+')'" v-bind:value="transaction.name" v-bind:data="transaction" />
                    <transaction-field v-bind:update="update" v-bind:save="save" type="type" v-bind:label="transaction.type" v-bind:value="transaction.type" v-bind:data="transaction" />
                    <transaction-field v-bind:update="update" v-bind:save="save" type="amount" v-bind:label="`$`+transaction.amount" v-bind:value="transaction.amount" v-bind:data="transaction" />
                </tr>
                <tr v-show="isDisabled">
                    <td colspan="4">
                        <div class="input-group">
                            <input
                                type="text"
                                v-model="newData.name"
                                class="form-control"
                                placeholder="Name"
                                aria-describedby="basic-addon2"
                            />
                            <input
                                type="text"
                                v-model="newData.balance"
                                class="form-control"
                                placeholder="Balance"
                                aria-describedby="basic-addon2"
                            />
                            <input
                                type="text"
                                v-model="newData.email"
                                class="form-control"
                                placeholder="Email"
                                aria-describedby="basic-addon2"
                            />
                            <input
                                type="text"
                                v-model="newData.type"
                                class="form-control"
                                placeholder="Type"
                                aria-describedby="basic-addon2"
                            />
                            <input
                                type="text"
                                v-model="newData.amount"
                                class="form-control"
                                placeholder="Amount"
                                aria-describedby="basic-addon2"
                            />
                            <div class="input-group-append">
                                <button @click="save(newData)" class="btn btn-primary" type="button">&#10003;</button>
                                <button @click="Reset" class="btn btn-secondary" type="button">&#10005;</button>
                            </div>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>

        <button class="btn btn-primary" type="button" id="add-transaction" @click="NewTransaction" :disabled="isDisabled">
            <i class="glyphicon glyphicon-plus"></i> Add transaction
        </button>
        
    </div>
</template>

<style>
.bg-green {
  background-color: #28a745 !important;
}
.bg-red {
  background-color: #dc3545 !important;
}
</style>

<script>
export default {
    data() {
        return {
            newTransaction: null,
            sortBy: {column:'id', direction:'ASC'},
            search: "",
            data: [],
            newId: 0,
            newData: {},
        };
    },
    created() {
        this.fetchTransactions();
    },
    methods: {
        NewTransaction () {
            this.Reset();

            this.newData = {
                id: this.newId--,
                balance: '',
                name: '',
                email: '',
                type: '',
                amount: '',
                email: '',
            }
        },
        Reset(){
            this.sortBy = {column:'id', direction:'ASC'};
            this.search = '';
            this.newTransaction = null;
            this.newId = 0;
            this.newData = {};
        },
        fetchTransactions() {
            axios.get(Vue.domain+"transactions").then(response => {
                this.data = response.data;
            });
        },
        Coloring(transaction){
            if (transaction.type == "Credit") {
                transaction.color = "green";
            } else {
                transaction.color = "red";
            }
            return transaction;      
        },
        ChangeSort(column, direction){
            this.sortBy = {column, direction};
        },
        Sorting(){
            const {column, direction}= this.sortBy;
            return _.orderBy(this.data, [function(o) { return o[column]; }], [direction]);
        },
        Searching(transaction){
            var search = this.search;
            return (
                transaction.email.toLowerCase().indexOf(search.toLowerCase()) >-1 ||
                transaction.name.toLowerCase().indexOf(search.toLowerCase()) >-1 ||
                transaction.balance.toString().toLowerCase().indexOf(search.toLowerCase()) > -1 ||
                transaction.amount.toString().toLowerCase().indexOf(search.toLowerCase()) > -1 ||
                transaction.type.toLowerCase().indexOf(search.toLowerCase()) > -1
            );
        },
        update(id, {type, value}, triggerMode) {
            axios.put(Vue.domain+'transactions/'+id, {[type]: value}).then(response => {            
                this.data = response.data
                this.Reset()
                triggerMode()
            })
            .catch(function (error) {
                alert(error.message)
            });
        },
        save(data) {
            axios.post(Vue.domain+'transactions', {...data}).then(response => {            
                this.data = response.data
                this.Reset()
            })
            .catch(function (error) {
                alert(error.message)
            });
        },
    },
    computed: {
        isDisabled() {
            return this.newId < 0;
        },
        transactions: function() {
            return this.Sorting()
                .map(this.Coloring)
                .filter(this.Searching)
        },
    }
};
</script>