<template>
    <td @dblclick="triggerMode">
    <div v-if="edited">{{label}}</div>
    <div v-else>
        <div class="input-group">
            <input
                type="text"
                v-model="v"
                class="form-control"
                placeholder="Recipient's username"
                aria-label="Recipient's username"
                aria-describedby="basic-addon2"
            />
            <div class="input-group-append">
                <button @click="update(data.id, {type, value:v}, triggerMode)" class="btn btn-primary" type="button">&#10003;</button>
                <button @click="triggerMode" class="btn btn-secondary" type="button">&#10005;</button>
            </div>
        </div>
    </div>
    </td>
</template>

<script>
export default {
    props: [ "label", "type", "value", "data", "update", "save" ],
    data() {

        return {
            edited: !this.data.isNew,
            v:this.value
        };
    },
    methods: {
        triggerMode() {
            this.edited = !this.edited;
        },
    },
    computed: {
    }
};
</script>