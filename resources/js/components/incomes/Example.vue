<template>
    <div>
        <section class="section">
            <store-income></store-income>
            <list-income class="mt-3"></list-income>
        </section>
    </div>
</template>
<script>
import StoreIncome from "./Store.vue";
import ListIncome from "./List.vue";

export default {
    components: {
        StoreIncome,
        ListIncome,
    },
};
</script>
