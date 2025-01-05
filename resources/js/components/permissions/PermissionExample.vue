<template>
    <div>
        <section class="section">
            <store-permission ref="form_permissions"></store-permission>
            <list-permission v-on:show="show" class="mt-3"></list-permission>
        </section>
    </div>
</template>
<script>
import StorePermission from "./StorePermission.vue";
import ListPermission from "./ListPermission.vue";

export default {
    components: {
        StorePermission,
        ListPermission,
    },
    methods: {
        show(row) {
            this.$refs.form_permissions.show(row);
        },
    },
};
</script>
