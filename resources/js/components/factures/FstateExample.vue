<template>
    <div>
        <div class="alert alert-danger alert-dismissible">
            <h5><i class="icon fas fa-ban"></i> Alert!</h5>
            Facturas con estado pendiente
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
                placeholder=".form-control-sm"
            />

            <button
                v-if="this.datetwo != ''"
                class="btn btn-outline-secondary btn-sm"
                @click="getListG()"
                type="button"
            >
                Buscar
            </button>
        </div>
        <div class="table-responsive">
            <VTable
                :data="factureState"
                :page-size="5"
                :currentPage.sync="currentPage"
                @totalPagesChanged="totalPages = $event"
                class="table table-striped table-borderless mt-3"
            >
                <template #head>
                    <tr style="color: #fff; background: black">
                        <th>#</th>
                        <th>Total</th>
                        <th>E</th>
                        <th>O</th>
                        <th>Banco</th>
                        <th>Vendedor</th>
                        <th>Estado</th>
                        <th></th>
                    </tr>
                </template>
                <template #body="{ rows }">
                    <tr v-for="row in rows" :key="row.id">
                        <td>{{ row.id }}</td>
                        <td>{{ row.tot | currency }}</td>
                        <td>{{ row.efecty | currency }}</td>
                        <td>{{ row.other | currency }}</td>
                        <td v-if="row.type_sale == 1">
                            <i class="fi fi-dollar"></i>
                        </td>
                        <td v-else>{{ row.type_sale }}</td>
                        <th>{{ row.name }}</th>
                        <td>
                            <span
                                @click="statusModified(row.id)"
                                class="badge badge-danger"
                                >Deuda</span
                            >
                        </td>
                        <td></td>
                    </tr>
                </template>
            </VTable>
            <div class="text-xs-center">
                <VTPagination
                    :currentPage.sync="currentPage"
                    :total-pages="totalPages"
                    :boundary-links="true"
                    :page-size="10"
                />
            </div>
        </div>
    </div>
</template>
<script>
import { mapState } from "vuex";
import date_now from "../../mixins/date";
import ModalFac from "../utilities/modalfac";
export default {
    data() {
        return {
            totalPages: 1,
            currentPage: 1,
            search_sale: "",
            date: "",
            datetwo: "",
        };
    },
    computed: {
        ...mapState(["factureState", "urlfactures"]),
    },
    components: {
        ModalFac,
    },
    created() {
        this.getList();
    },
    methods: {
        getList() {
            let obj = {
                prop1: date_now,
                prop2: date_now,
            };
            this.$store.dispatch("FactureStateactions", obj);
        },
        //filter list global range
        getListG() {
            let obj = {
                prop1: this.date,
                prop2: this.datetwo,
            };
            this.$store.dispatch("FactureStateactions", obj);
        },
        async statusModified(id) {
            let url = this.urlfactures + "/" + id;
            let response = await axios.put(url);
            try {
                this.getList();
                Swal.fire({
                    title: `${response.data.message}`,
                    icon: "success",
                });
            } catch (error) {
                console.log(error);
            }
        },
    },
};
</script>
