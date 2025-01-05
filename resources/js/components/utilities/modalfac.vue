<template>
    <div>
        <div class="row">
            <div class="col-12">
                <table class="table">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Nit</th>
                            <th scope="col">Nombre</th>
                            <th scope="col">Tel</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="(item, index) in facUnique"
                            :key="'g' + index"
                        >
                            <th scope="row">{{ item.id }}</th>
                            <td>{{ item.nit }}</td>
                            <td>{{ item.fullname }}</td>
                            <th>{{ item.phone }}</th>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="row">
            <div class="col-12" id="facture2">
                <div class="table-responsive">
                    <table class="table table-borderless table-striped">
                        <thead>
                            <tr>
                                <th scope="col">Art.</th>
                                <th scope="col">Pre.</th>
                                <th scope="col">Cant.</th>
                                <th scope="col">Sub.</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="(item, index) in details"
                                :key="'d' + index"
                            >
                                <th scope="row">{{ item.name }}</th>
                                <td>${{ item.price | currency }}</td>
                                <td>{{ item.quantity }}</td>
                                <td>${{ item.sub | currency }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div v-for="item2 in facUnique" :key="item2.id">
                {{ item2.note }}
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <table class="table">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col">Sub.</th>
                            <th scope="col">Desc.</th>
                            <th scope="col">Tot.</th>
                            <th scope="col">Cantp.</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="(item, index) in facUnique"
                            :key="'g2' + index"
                        >
                            <th>${{ item.sub | currency }}</th>
                            <td>${{ item.disc | currency }}</td>
                            <td scope="row">${{ item.tot | currency }}</td>
                            <th>{{ sumProducts }}</th>
                        </tr>
                    </tbody>
                </table>
                <button type="button" class="btn btn-primary" @click="got()">
                    atras
                </button>
            </div>
        </div>
    </div>
</template>
<script>
import { mapState } from "vuex";

import VueHtml2Canvas from "vue-html2canvas";
Vue.use(VueHtml2Canvas);

export default {
    name: "modaldetails",
    props: {
        cod: {
            type: Number,
            required: true,
        },
    },

    data() {
        return {
            output: null,
            currentPage: 1,
            totalPages: 0,
        };
    },
    created() {
        this.getlistProducts();
    },
    computed: {
        ...mapState(["details", "facUnique"]),
        sumProducts() {
            let tot = 0;
            this.details.map((data) => {
                tot = parseInt(tot) + parseInt(data.quantity);
            });
            return tot;
        },
    },
    methods: {
        print() {
            this.$htmlToPaper("facture2");
        },
        getlistProducts() {
            this.$store.dispatch("FactureDetailactions", this.cod);
            this.$store.dispatch("FactureUniquections", this.cod);
        },
        got() {
            location.reload();
        },
    },
};
</script>
