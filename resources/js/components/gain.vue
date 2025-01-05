<template>
    <div>
        <meta charset="utf-8" />
        <div><br /></div>

        <div class="row">
            <div class="col-lg-6 col-xs-12 col-sm-12 mt-3">
                <div class="row">
                    <div class="col-12 col-lg-6">
                        <div class="small-box bg-warning">
                            <div
                                v-for="item in Totcost"
                                :key="item.id"
                                class="inner"
                            >
                                <h3>${{ item.cost | currency }}</h3>
                                <p>Total en inventario <strong>Neto</strong></p>
                            </div>
                            <div class="icon">
                                <i class="fi fi-bar-chart"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-lg-6">
                        <div class="small-box bg-info">
                            <div
                                v-for="item in Totcost"
                                :key="item.id"
                                class="inner"
                            >
                                <h3 v-if="item.stock > 0">{{ item.stock }}</h3>
                                <h3 v-else>0</h3>
                                <p>
                                    Total en productos <strong>Cantidad</strong>
                                </p>
                            </div>
                            <div class="icon">
                                <i class="fi fi-bar-chart"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 col-lg-6">
                        <div class="small-box bg-info">
                            <div
                                v-for="item in gaintotg"
                                :key="item.id"
                                class="inner"
                            >
                                <h3>${{ item.gaintot | currency }}</h3>
                                <p>Total venta <strong>General</strong></p>
                            </div>
                            <div class="icon">
                                <i class="fi fi-bar-chart"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-lg-6">
                        <div class="small-box bg-success">
                            <div
                                v-for="item in gaintotfg"
                                :key="item.id"
                                class="inner"
                            >
                                <h3>${{ item.gain | currency }}</h3>
                                <p>Total ganancias <strong>General</strong></p>
                            </div>
                            <div class="icon">
                                <i class="fi fi-bar-chart"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="alert alert-primary" role="alert">
                    Resultado de ventas, ganancias y total individual
                </div>
                <div class="input-group">
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
                        min=""
                        placeholder=".form-control-sm"
                    />
                    <!-- select categorie -->
                    <select v-model="form.type" v-if="this.datetwo != ''">
                        <option
                            v-for="item in categories"
                            :key="item.id"
                            :value="item.id"
                        >
                            {{ item.name }}
                        </option>
                    </select>

                    <button
                        v-if="this.datetwo != ''"
                        class="btn btn-outline-secondary btn-sm"
                        @click="getDate()"
                        type="button"
                    >
                        Buscar
                    </button>
                </div>

                <div class="form-group mt-3">
                    <input
                        type="text"
                        class="form-control form-control-sm"
                        v-model="filters.name.value"
                        placeholder="Buscar referencia"
                    />
                </div>
                <div class="table-responsive mt-3">
                    <VTable
                        :data="gain"
                        :filters="filters"
                        :page-size="5"
                        :currentPage.sync="currentPage2"
                        @totalPagesChanged="totalPages2 = $event"
                        class="table table-dark"
                    >
                        <template #head>
                            <tr>
                                <VTh sortKey="name">Nombre</VTh>
                                <th>Cant</th>
                                <th>Total</th>
                                <th>Costo</th>
                                <th>Ganancia</th>
                            </tr>
                        </template>
                        <template #body="{ rows }">
                            <tr v-for="row in rows" :key="row.name">
                                <td>{{ row.name }}</td>
                                <td>{{ row.quantity }}</td>
                                <td v-can="'ver indicador'">
                                    {{ row.tot | currency }}
                                </td>
                                <td v-can="'ver indicador'">
                                    {{ row.cost | currency }}
                                </td>
                                <td v-can="'ver indicador'">
                                    {{ row.gain | currency }}
                                </td>
                            </tr>
                        </template>
                    </VTable>
                    <div class="text-xs-center">
                        <VTPagination
                            :currentPage.sync="currentPage2"
                            :total-pages="totalPages2"
                            :boundary-links="true"
                            :maxPageLinks="5"
                        />
                    </div>
                </div>

                <div
                    class="alert alert-dark mt-3"
                    v-for="(item, index) in gaintot"
                    :key="'b1' + index"
                    role="alert"
                >
                    <p>TOTAL VENTA ${{ item.gaintot | currency }}</p>
                </div>

                <div
                    class="alert alert-dark mt-3"
                    v-for="(item, index) in gaintotf"
                    :key="'b' + index"
                    role="alert"
                >
                    <p v-can="'ver indicador'">
                        TOT GANANCIA ${{ item.gain | currency }}
                    </p>
                </div>
            </div>
            <div class="col-lg-6 col-xs-12 col-sm-12 mt-3">
                <div class="alert alert-warning" role="alert">
                    Resultado de ventas por vendedor
                </div>
                <div class="input-group">
                    <input
                        class="form-control form-control-sm"
                        type="date"
                        v-model="dateuser"
                        placeholder=".form-control-sm"
                    />
                    <input
                        class="form-control form-control-sm"
                        type="date"
                        v-model="datetwouser"
                        min=""
                        placeholder=".form-control-sm"
                    />

                    <button
                        v-if="this.datetwouser != ''"
                        class="btn btn-outline-secondary btn-sm"
                        @click="getDateUser()"
                        type="button"
                    >
                        Buscar
                    </button>
                </div>

                <table class="table table-dark mt-3">
                    <thead>
                        <tr>
                            <th scope="col">Vendedor</th>
                            <th scope="col">total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(item, index) in usertot" :key="'u' + index">
                            <td>{{ item.name }}</td>
                            <td>{{ item.tot | currency }}</td>
                        </tr>
                    </tbody>
                </table>
                <div class="alert alert-primary mt-3" role="alert">
                    Productos agotados
                </div>
                <!-- table products stock 0 -->
                <div class="table-responsive">
                    <VTable
                        :data="productstock"
                        :page-size="5"
                        :currentPage.sync="currentPage"
                        @totalPagesChanged="totalPages = $event"
                        class="table"
                    >
                        <template #head>
                            <tr>
                                <VTh sortKey="nit">Nombre</VTh>
                                <th>Costo</th>
                                <th>Categoria</th>
                                <th></th>
                            </tr>
                        </template>
                        <template #body="{ rows }">
                            <tr v-for="row in rows" :key="row.id">
                                <td>{{ row.name }}</td>
                                <td>${{ row.cost | currency }}</td>
                                <td>{{ row.type }}</td>
                                <td>
                                    <span class="badge bg-danger">Agotado</span>
                                </td>
                            </tr>
                        </template>
                    </VTable>
                    <div class="text-xs-center">
                        <VTPagination
                            :currentPage.sync="currentPage"
                            :total-pages="totalPages"
                            :boundary-links="true"
                            :maxPageLinks="2"
                        />
                    </div>
                </div>

                <!-- end -->
                <div class="alert alert-primary mt-3" role="alert">
                    Productos con más <strong>rotación</strong>
                </div>
                <div class="table-responsive">
                    <VTable
                        :page-size="3"
                        :currentPage.sync="currentPage3"
                        @totalPagesChanged="totalPages3 = $event"
                        class="table"
                        :data="productsr"
                    >
                        <template #head>
                            <tr>
                                <th>Nombre</th>
                                <VTh sortKey="tot">Total</VTh>
                            </tr>
                        </template>
                        <template #body="{ rows }">
                            <tr v-for="row in rows" :key="row.id">
                                <td>{{ row.name }}</td>
                                <td>${{ row.tot | currency }}</td>
                            </tr>
                        </template>
                    </VTable>
                    <div class="text-xs-center">
                        <VTPagination
                            :currentPage.sync="currentPage3"
                            :total-pages="totalPages3"
                            :boundary-links="true"
                        />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import { mapState } from "vuex";
import date_now from "../mixins/date";

export default {
    data() {
        return {
            form: {
                type: 1,
                type_payment: 1,
            },
            totalPages: 1,
            currentPage: 1,
            totalPages2: 1,
            currentPage2: 1,
            totalPages3: 1,
            currentPage3: 1,
            date: "",
            datetwo: "",
            dateuser: "",
            datetwouser: "",
            filters: {
                name: { value: "", keys: ["name"] },
            },
        };
    },
    computed: {
        ...mapState([
            "gain",
            "gaintot",
            "gaintotg",
            "gaintotf",
            "gaintotfg",
            "Totcost",
            "usertot",
            "categories",
            "productstock",
            "productsr",
        ]),
    },
    created() {
        this.getList();
        this.getListUser();
        this.getCategorie();
    },
    methods: {
        getCategorie() {
            this.$store.dispatch("Categorieactions");
        },
        getList() {
            let obj = {
                prop1: date_now,
                prop2: date_now,
                type: this.form.type,
            };
            this.$store.dispatch("Gainactions", obj);
            this.$store.dispatch("Gaintotactions", obj);
            this.$store.dispatch("Gaintotgactions", obj);
            this.$store.dispatch("Gaintotfactions", obj);
            this.$store.dispatch("Gaintotfgactions", obj);
            this.$store.dispatch("Totcostactions");
            this.$store.dispatch("Productstockactions");
            this.$store.dispatch("Productractions");
        },
        getListUser() {
            let obj = {
                prop1: date_now,
                prop2: date_now,
            };
            this.$store.dispatch("Usertotactions", obj);
        },

        getDate() {
            let obj = {
                prop1: this.date,
                prop2: this.datetwo,
                type: this.form.type,
            };
            this.$store.dispatch("Gainactions", obj);
            this.$store.dispatch("Gaintotactions", obj);
            this.$store.dispatch("Gaintotgactions", obj);
            this.$store.dispatch("Gaintotfactions", obj);
            this.$store.dispatch("Gaintotfgactions", obj);
        },

        getDateUser() {
            let obj = {
                prop1: this.dateuser,
                prop2: this.datetwouser,
            };
            this.$store.dispatch("Useractions", obj);
            this.$store.dispatch("Usertotactions", obj);
        },
    },
};
</script>
