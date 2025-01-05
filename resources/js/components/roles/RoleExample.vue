<template>
    <div>
        <section class="section">
            <store-rol ref="form_role"></store-rol>
            <list-rol v-on:show="show" class="mt-3"></list-rol>
        </section>
    </div>
</template>
<script>
import StoreRol from "./StoreRole.vue";
import ListRol from "./ListRole.vue";

export default {
    components: {
        StoreRol,
        ListRol,
    },
    methods: {
        show(row) {
            this.$refs.form_role.show(row);
        },
    },
};
</script>
