<template>
    <div>
        <div class="table-responsive">
            <div class="alert alert-warning" role="alert">
                Consulta por fecha
            </div>
            <div class="input-group mt-3">
                <input
                    class="form-control form-control-sm"
                    type="date"
                    v-model="dateList"
                    placeholder=".form-control-sm"
                />
                <button
                    v-if="this.dateList != ''"
                    class="btn btn-outline-secondary btn-sm"
                    @click="getList_two()"
                    type="button"
                >
                    Buscar
                </button>
            </div>
            <VTable :data="income" class="table table-dark mt-3">
                <template #head>
                    <tr>
                        <VTh sortKey="product">Producto</VTh>
                        <th>Cantidad</th>
                        <th>Usuario</th>
                        <th>Fecha</th>
                        <th>Registro</th>
                        <th></th>
                    </tr>
                </template>
                <template #body="{ rows }">
                    <tr v-for="row in rows" :key="row.id">
                        <td>{{ row.product }}</td>
                        <td>{{ row.quantity }}</td>
                        <td>{{ row.user }}</td>
                        <td>{{ row.date_income }}</td>
                        <td>{{ row.created_at }}</td>

                        <td>
                            <button
                                v-can="'eliminar entrada'"
                                type="button"
                                @click="destroy(row.id)"
                                class="btn bg-danger btn-sm"
                            >
                                <i class="fi fi-trash"></i>
                            </button>
                        </td>
                    </tr>
                </template>
            </VTable>
        </div>

        <div class="alert alert-primary" role="alert">
            Consulta en rango de fecha
        </div>
        <div class="table-responsive">
            <div class="form-group">
                <input
                    type="text"
                    class="form-control"
                    v-model="filters.product.value"
                    placeholder="Buscar entrada"
                />
            </div>
            <div class="input-group mt-3">
                <input
                    class="form-control form-control-sm"
                    type="date"
                    v-model="date"
                    placeholder=".form-control-sm"
                />
                <input
                    class="form-control form-control-sm"
                    type="date"
                    v-model="datetwo"
                    placeholder=".form-control-sm"
                />

                <button
                    v-if="this.datetwo != ''"
                    class="btn btn-outline-secondary btn-sm"
                    @click="getDate()"
                    type="button"
                >
                    Buscar
                </button>
            </div>
            <VTable
                :data="incometot"
                :filters="filters"
                :page-size="10"
                :currentPage.sync="currentPage"
                @totalPagesChanged="totalPages = $event"
                class="table table-borderless table-dark mt-3"
            >
                <template #head>
                    <tr>
                        <VTh sortKey="product">Producto</VTh>
                        <th>Cantidad</th>
                    </tr>
                </template>
                <template #body="{ rows }">
                    <tr v-for="row in rows" :key="row.id">
                        <td>{{ row.product }}</td>
                        <td>{{ row.quantity }}</td>
                    </tr>
                </template>
            </VTable>
        </div>
        <div class="text-xs-center">
            <VTPagination
                :currentPage.sync="currentPage"
                :total-pages="totalPages"
                :boundary-links="true"
            />
        </div>
    </div>
</template>
<script>
import { mapState } from "vuex";
import date_now from "../../mixins/date";
export default {
    data() {
        return {
            totalPages: 1,
            currentPage: 1,
            date: "",
            dateList: "",
            datetwo: "",
            filters: {
                product: { value: "", keys: ["product"] },
            },
        };
    },
    computed: {
        ...mapState(["income", "incometot", "urlincome"]),
    },
    created() {
        this.getList();
    },
    methods: {
        getList() {
            this.$store.dispatch("Incomeactions", date_now);
            this.getDate();
        },
        getList_two() {
            this.$store.dispatch("Incomeactions", this.dateList);
        },
        getDate() {
            let obj = {
                prop1: this.date,
                prop2: this.datetwo,
            };
            this.$store.dispatch("IncomeTwoactions", obj);
        },

        destroy(id) {
            let url = this.urlincome + "/" + id;
            let response = axios.delete(url);

            this.getList();
            Swal.fire({
                title: `${response.data.message}`,
                icon: "success",
            });
        },
    },
};
</script>
