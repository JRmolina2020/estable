<template>
    <div>
        <section class="section">
            <store-client ref="form_client"></store-client>
            <list-client v-on:show="show" class="mt-3"></list-client>
        </section>
    </div>
</template>
<script>
import StoreClient from "./StoreClient.vue";
import ListClient from "./ListClient.vue";

export default {
    components: {
        StoreClient,
        ListClient,
    },
    methods: {
        show(row) {
            this.$refs.form_client.show(row);
        },
    },
};
</script>
