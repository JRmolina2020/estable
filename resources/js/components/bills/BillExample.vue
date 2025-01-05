<template>
    <div>
        <section class="section">
            <store-bill ref="form_bill"></store-bill>
            <list-bill v-on:show="show" class="mt-3"></list-bill>
        </section>
    </div>
</template>
<script>
import StoreBill from "./StoreBill.vue";
import ListBill from "./ListBill.vue";

export default {
    components: {
        StoreBill,
        ListBill,
    },
    methods: {
        show(row) {
            this.$refs.form_bill.show(row);
        },
    },
};
</script>
