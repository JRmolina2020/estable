<template>
    <div>
        <div class="form-group">
            <input
                type="text"
                class="form-control"
                v-model="filters.name.value"
                placeholder="Buscar usuarios"
            />
        </div>
        <div class="table-responsive">
            <VTable
                :data="users"
                :filters="filters"
                :page-size="5"
                :currentPage.sync="currentPage"
                @totalPagesChanged="totalPages = $event"
                class="table"
            >
                <template #head>
                    <tr>
                        <th>Nombre</th>
                        <th>Correo</th>
                        <th>Rol</th>
                        <th>Estado</th>
                        <th>Op</th>
                    </tr>
                </template>
                <template #body="{ rows }">
                    <tr v-for="row in rows" :key="row.id">
                        <td>{{ row.name }}</td>
                        <td>{{ row.email }}</td>
                        <td>{{ row.roles[0].name }}</td>
                        <td>
                            <button
                                type="button"
                                @click="theState(row)"
                                v-bind:class="{
                                    'btn btn-sm': true,
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
                        <td>
                            <button
                                type="button"
                                @click="$emit('show', row)"
                                class="btn bg-warning btn-sm"
                            >
                                <i class="fi fi-eye"></i>
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
    name: "ListUser",
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
        ...mapState(["users", "status", "urlusers"]),
    },
    created() {
        this.getList();
    },
    methods: {
        getList() {
            this.$store.dispatch("Useractions");
        },
        async theState(row) {
            if (row.status == 1) {
                let url = this.urlusers + "/locked/" + row.id;
                let response = await axios.put(url);
                try {
                    Swal.fire({
                        title: `El usuario ${row.name} ${response.data.message}`,
                        icon: "success",
                    });
                    this.getList();
                } catch (error) {
                    console.log(error);
                }
            } else {
                let url = this.urlusers + "/available/" + row.id;
                let response = await axios.put(url);
                try {
                    Swal.fire({
                        title: `El usuario ${row.name} ${response.data.message}`,
                        icon: "success",
                    });
                    this.getList();
                } catch (error) {
                    console.log(error);
                }
            }
        },
    },
};
</script>
