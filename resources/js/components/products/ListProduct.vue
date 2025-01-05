<template>
    <div>
        <div class="form-group">
            <input
                type="text"
                class="form-control"
                v-model="filters.name.value"
                placeholder="Buscar productos"
            />
        </div>

        <select @change="state()" v-model="state_v" class="custom-select">
            <option value="1">Activos</option>
            <option value="0">Inactivos</option>
        </select>

        <div class="table-responsive mt-3">
            <VTable
                :data="products"
                :filters="filters"
                :page-size="10"
                :currentPage.sync="currentPage"
                @totalPagesChanged="totalPages = $event"
                class="table"
            >
                <template #head>
                    <tr>
                        <VTh sortKey="name">Nombre</VTh>
                        <th>Stock</th>
                        <th>Precio M</th>
                        <th>Precio D</th>
                        <th>Tipo</th>
                        <th></th>
                        <th>Op</th>
                        <th></th>
                    </tr>
                </template>
                <template #body="{ rows }">
                    <tr v-for="row in rows" :key="row.id">
                        <td class="bg-danger" v-if="row.cost == 0">
                            {{ row.name }}
                        </td>
                        <td v-else>{{ row.name }}</td>
                        <td>{{ row.stock }}</td>

                        <td>
                            {{ row.price | currency }}
                        </td>

                        <td>{{ row.price_two | currency }}</td>

                        <td>{{ row.type }}</td>
                        <td v-can="'gestion producto'">
                            <button
                                type="button"
                                @click="thestatus(row, urlproducts, prefijo)"
                                v-bind:class="{
                                    'btn btn-block  btn-sm': true,
                                    'btn-success': row.status,
                                    'btn-danger': row.status == 0,
                                }"
                            >
                                <i
                                    :class="
                                        row.status
                                            ? 'fi fi-toggle-on'
                                            : 'fi fi-toggle-off'
                                    "
                                    aria-hidden="true"
                                ></i>
                            </button>
                        </td>

                        <td v-can="'gestion producto'">
                            <button
                                type="button"
                                @click="$emit('show', row)"
                                class="btn bg-warning btn-sm"
                            >
                                <i class="fi fi-eye"></i>
                            </button>
                        </td>
                        <td v-can="'gestion producto'">
                            <modal-info>
                                <section slot="body">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>PVM</th>
                                                <th>PVD</th>
                                                <th>Tipo</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    ${{
                                                        row.priceSv | currency
                                                    }}
                                                </td>
                                                <td>
                                                    ${{
                                                        row.price_twoSv
                                                            | currency
                                                    }}
                                                </td>
                                                <td>{{ row.type_iva }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>costo</th>
                                                <th>Ganacia M</th>
                                                <th>Ganancia D</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    ${{ row.cost | currency }}
                                                </td>
                                                <td>
                                                    ${{
                                                        (row.price - row.cost)
                                                            | currency
                                                    }}
                                                </td>
                                                <td>
                                                    ${{
                                                        (row.price_two -
                                                            row.cost)
                                                            | currency
                                                    }}
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </section>
                            </modal-info>
                        </td>
                    </tr>
                </template>
            </VTable>
            <div class="text-xs-center">
                <VTPagination
                    :currentPage.sync="currentPage"
                    :total-pages="totalPages"
                    :boundary-links="true"
                    :maxPageLinks="4"
                />
            </div>
        </div>
    </div>
</template>
<script>
import { mapState } from "vuex";
import state_modified from "../../mixins/statem";
import ModalInfo from "../utilities/ModalInfo.vue";

export default {
    data() {
        return {
            state_v: 1,
            prefijo: "el producto",
            barcodeValue: "",
            barcodeint: 0,
            barcodeint2: 0,
            totalPages: 1,
            currentPage: 1,
            filters: {
                name: { value: "", keys: ["name"] },
            },
        };
    },
    mixins: [state_modified],
    computed: {
        ...mapState(["products", "status", "urlproducts"]),
    },
    components: {
        ModalInfo,
    },
    created() {
        this.getList();
    },
    methods: {
        barcodeTot() {
            this.barcodeint = this.barcodeint2;
        },
        state() {
            if (this.state_v == 1) {
                this.$store.dispatch("Productactions", 1);
            } else {
                this.$store.dispatch("Productactions", 0);
            }
        },
        getList() {
            this.$store.dispatch("Productactions", this.state_v);
        },
    },
};
</script>
