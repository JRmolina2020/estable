<template>
    <div>
        <div class="row">
            <div class="col-6">
                <div class="form-group">
                    <input
                        type="text"
                        class="form-control form-control-sm"
                        v-model="filters.name.value"
                        placeholder="Buscar gastos"
                    />
                </div>
            </div>
            <div class="col-6">
                <div class="input-group">
                    <input
                        class="form-control form-control-sm"
                        type="date"
                        v-model="date"
                        placeholder=".form-control-sm"
                    />
                    <div class="input-group-append">
                        <button
                            class="btn btn-outline-secondary btn-sm"
                            @click="getDate()"
                            type="button"
                        >
                            Buscar
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <VTable
                :data="bills"
                :filters="filters"
                :page-size="5"
                :currentPage.sync="currentPage"
                @totalPagesChanged="totalPages = $event"
                class="table table-dark"
            >
                <template #head>
                    <tr>
                        <VTh sortKey="user_id">Empleado</VTh>
                        <VTh sortKey="name">Concepto</VTh>
                        <th>Monto</th>
                        <th>Op</th>
                    </tr>
                </template>
                <template #body="{ rows }">
                    <tr v-for="row in rows" :key="row.id">
                        <td>{{ row.users }}</td>
                        <td>{{ row.name }}</td>
                        <td>{{ row.price | currency }}</td>

                        <td>
                            <button
                                type="button"
                                @click="$emit('show', row)"
                                class="btn bg-warning btn-sm"
                            >
                                <i class="fi fi-eye"></i>
                            </button>
                            <button
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

        <div class="text-xs-center">
            <VTPagination
                :currentPage.sync="currentPage"
                :total-pages="totalPages"
                :boundary-links="true"
            />
        </div>
        <div
            class="alert alert-dark"
            v-for="(item, index) in billstot"
            :key="'b' + index"
            role="alert"
        >
            <p>TOT HOY ${{ item.price | currency }}</p>
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
            filters: {
                name: { value: "", keys: ["name"] },
            },
        };
    },
    computed: {
        ...mapState(["bills", "billstot", "status", "urlbills"]),
    },
    created() {
        this.getList();
    },
    methods: {
        getList() {
            this.$store.dispatch("Billactions", date_now);
            this.$store.dispatch("Billtotactions", date_now);
        },
        getDate() {
            let date = this.date;
            this.$store.dispatch("Billactions", date);
            this.$store.dispatch("Billtotactions", date);
        },

        async destroy(id) {
            let url = this.urlbills + "/" + id;
            let response = await axios.delete(url);
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
