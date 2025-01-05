<template>
    <div>
        <section class="section">
            <store-user ref="form_user"></store-user>
            <list-user v-on:show="show" class="mt-3"></list-user>
        </section>
    </div>
</template>
<script>
import StoreUser from "./StoreUser.vue";
import ListUser from "./ListUser.vue";
export default {
    components: {
        StoreUser,
        ListUser,
    },
    methods: {
        show(row) {
            this.$refs.form_user.show(row);
        },
    },
};
</script>
