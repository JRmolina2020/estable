<template>
    <div>
        <section class="section">
            <store-product ref="form_product"></store-product>
            <list-product v-on:show="show" class="mt-3"></list-product>
        </section>
    </div>
</template>
<script>
import StoreProduct from "./StoreProduct.vue";
import ListProduct from "./ListProduct.vue";

export default {
    components: {
        StoreProduct,
        ListProduct,
    },
    methods: {
        show(row) {
            this.$refs.form_product.show(row);
        },
    },
};
</script>
