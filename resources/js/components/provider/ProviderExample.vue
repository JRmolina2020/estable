<template>
    <div>
        <section class="section">
            <store-provider ref="form_provider"></store-provider>
            <list-provider v-on:show="show" class="mt-3"></list-provider>
        </section>
    </div>
</template>
<script>
import StoreProvider from "./StoreProvider.vue";
import ListProvider from "./ListProvider.vue";

export default {
    components: {
        StoreProvider,
        ListProvider,
    },
    methods: {
        show(row) {
            this.$refs.form_provider.show(row);
        },
    },
};
</script>
