<template>
    <div>
        <section class="section">
            <store-company ref="form_company"></store-company>
            <list-company v-on:show="show" class="mt-3"></list-company>
        </section>
    </div>
</template>
<script>
import StoreCompany from "./StoreCompany.vue";
import ListCompany from "./ListCompany.vue";

export default {
    components: {
        StoreCompany,
        ListCompany,
    },
    methods: {
        show(row) {
            this.$refs.form_company.show(row);
        },
    },
};
</script>
