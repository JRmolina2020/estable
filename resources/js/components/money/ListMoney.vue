<template>
    <div>
        <div class="form-group">
            <input
                type="text"
                class="form-control"
                v-model="filters.name.value"
                placeholder="Buscar cuenta"
            />
        </div>
        <div class="table-responsive">
            <VTable
                :data="money"
                :filters="filters"
                :page-size="5"
                :currentPage.sync="currentPage"
                @totalPagesChanged="totalPages = $event"
                class="table table-dark table-striped"
            >
                <template #head>
                    <tr>
                        <VTh sortKey="name">Cuenta</VTh>
                        <th>Estado</th>
                        <th>Op</th>
                    </tr>
                </template>
                <template #body="{ rows }">
                    <tr v-for="row in rows" :key="row.id">
                        <td>{{ row.name }}</td>
                        <td v-if="row.status">
                            <i class="fi fi-like"></i>
                        </td>
                        <td v-else>
                            <i class="fi fi-dislike"></i>
                        </td>
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
    </div>
</template>
<script>
import { mapState } from "vuex";
export default {
    data() {
        return {
            totalPages: 1,
            currentPage: 1,
            filters: {
                name: { value: "", keys: ["name"] },
            },
        };
    },
    computed: {
        ...mapState(["money", "status", "urlmoney"]),
    },
    created() {
        this.getList();
    },
    methods: {
        getList() {
            this.$store.dispatch("Moneyactions");
        },
        async destroy(id) {
            let url = this.urlmoney + "/" + id;
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
