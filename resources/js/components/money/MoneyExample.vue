<template>
    <div>
        <section class="section">
            <store-money ref="form_money"></store-money>
            <list-money v-on:show="show" class="mt-3"></list-money>
        </section>
    </div>
</template>
<script>
import StoreMoney from "./StoreMoney.vue";
import ListMoney from "./ListMoney.vue";

export default {
    components: {
        StoreMoney,
        ListMoney,
    },
    methods: {
        show(row) {
            this.$refs.form_money.show(row);
        },
    },
};
</script>
