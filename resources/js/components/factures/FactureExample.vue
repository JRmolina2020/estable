<template>
    <div>
        <section class="section">
            <store-facture v-if="viewfx"></store-facture>
            <list-facture class="mt-3"></list-facture>
        </section>
    </div>
</template>
<script>
import { mapState } from "vuex";
import ListFacture from "./ListFacture.vue";
import StoreFacture from "./StoreFacture.vue";

export default {
    components: {
        ListFacture,
        StoreFacture,
    },
    computed: {
        ...mapState(["viewfx"]),
    },
};
</script>
