<template>
    <div>
        <div
            class="card text-center"
            v-for="(item, index) in company"
            :key="index"
        >
            <div class="card-header">Nit: {{ item.nit }}</div>
            <div class="card-footer text-muted">
                <strong>Empresa: </strong>{{ item.name }}
            </div>
            <div class="card-footer text-muted">
                <strong>Representante: </strong> {{ item.representative }}
            </div>
            <div class="card-footer text-muted">
                <strong>Tel: </strong>{{ item.phone }}
            </div>
            <div class="card-footer text-muted">
                <strong>Dirección: </strong>{{ item.direction }}
            </div>
            <div class="card-footer text-muted">
                <strong>Ciudad: </strong>{{ item.city }}
            </div>
            <div class="mt-3">
                <button
                    type="button"
                    @click="$emit('show', item)"
                    class="btn bg-warning btn-sm"
                >
                    <i class="fi fi-eye"></i>
                </button>
                <div class="mt-3"></div>
            </div>
        </div>
    </div>
</template>
<script>
import { mapState } from "vuex";
export default {
    data() {
        return {
            totalPages: 1,
            currentPage: 1,
        };
    },
    computed: {
        ...mapState(["company", "status", "urlcompany", "urlcompanies"]),
    },
    created() {
        this.getList();
    },
    methods: {
        getList() {
            this.$store.dispatch("Companyactions");
        },
    },
};
</script>
