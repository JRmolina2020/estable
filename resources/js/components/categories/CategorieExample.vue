<template>
    <div>
        <section class="section">
            <store-categorie ref="form_categorie"></store-categorie>
            <list-categorie v-on:show="show" class="mt-3"></list-categorie>
        </section>
    </div>
</template>
<script>
import StoreCategorie from "./StoreCategorie.vue";
import ListCategorie from "./ListCategorie.vue";

export default {
    components: {
        StoreCategorie,
        ListCategorie,
    },
    methods: {
        show(row) {
            this.$refs.form_categorie.show(row);
        },
    },
};
</script>
