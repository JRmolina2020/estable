<template>
    <div>
        <div v-if="update">
            <form
                method="POST"
                @submit.enter.prevent="updateFacture()"
                autocomplete="off"
                onKeyPress="if(event.keyCode == 13) event.returnValue = false;"
            >
                <div class="alert alert-primary" role="alert">
                    TOT <strong>{{ form.tot | currency }} </strong> Venta #
                    {{ form.id }}
                </div>

                <p></p>

                <div class="row">
                    <div class="col-4">
                        <div class="form-group">
                            <label for>Efectivo</label>
                            <currency-input
                                v-validate="{
                                    min_value: 0,
                                    required: true,
                                    max_value: form.tot,
                                }"
                                :class="{
                                    'is-invalid':
                                        submitted && errors.has('efectivo'),
                                }"
                                v-currency="{
                                    currency: 'USD',
                                    precision: 0,
                                    locale: 'en',
                                }"
                                name="efectivo"
                                class="form-control form-control-sm"
                                v-model="form.efecty"
                                @keyup="equalsTot()"
                            />
                            <div
                                v-if="submitted && errors.has('efectivo')"
                                class="invalid-feedback"
                            >
                                {{ errors.first("efectivo") }}
                            </div>
                        </div>
                    </div>
                    <div class="col-4" v-if="form.efecty < form.tot">
                        <div class="form-group">
                            <label for>otros</label>
                            <input
                                class="form-control form-control-sm"
                                v-model.number="form.other"
                                disabled
                            />
                        </div>
                    </div>
                    <div v-if="form.other > 0" class="col-4">
                        <div class="form-group">
                            <label for="my-select">Banco</label>
                            <select
                                v-model="form.type_sale"
                                class="form-control form-control-sm"
                                required
                            >
                                <option
                                    v-for="(item, index) in moneySingle"
                                    :key="index"
                                    :value="item.name"
                                >
                                    {{ item.name }}
                                </option>
                            </select>
                        </div>
                    </div>
                    <div v-else></div>
                </div>
                <div class="row">
                    <div class="col-4">
                        <div class="form-group">
                            <label>Vendedor</label>
                            <select
                                v-model="form.user_id"
                                class="form-control form-control-sm"
                                required
                            >
                                <option
                                    v-for="(item, index) in users"
                                    :value="item.id"
                                    :key="index"
                                >
                                    {{ item.name }}
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-4 col-6">
                        <div class="form-group">
                            <label>Cliente</label>
                            <v-select
                                :options="clients"
                                v-model="form.client_id"
                                :reduce="(clients) => clients.id"
                                label="fullname"
                            >
                                <template #search="{ attributes, events }">
                                    <input
                                        class="vs__search"
                                        :required="!form.client_id"
                                        v-bind="attributes"
                                        v-on="events"
                                    />
                                </template>
                            </v-select>
                        </div>
                    </div>
                </div>

                <button
                    v-if="!send"
                    class="btn btn-primary"
                    type="button"
                    disabled
                >
                    <span
                        class="spinner-border spinner-border-sm"
                        role="status"
                        aria-hidden="true"
                    ></span>
                    Loading...
                </button>
                <button
                    v-if="send"
                    :hidden="errors.any()"
                    type="submit"
                    class="btn btn-outline-primary"
                >
                    <i class="fi fi-like"></i>
                </button>
            </form>
        </div>
        <div class="table-responsive">
            <div class="form-group">
                <input
                    type="text"
                    class="form-control mt-3"
                    v-model="filters.id.value"
                    placeholder="Buscar factura"
                />
            </div>
            <VTable
                :data="factures"
                :filters="filters"
                class="table table-striped table-dark table-borderless mt-3"
            >
                <template #head>
                    <tr>
                        <th>#</th>
                        <th>Total</th>
                        <th>E</th>
                        <th>O</th>
                        <th>Banco</th>
                        <th>Vendedor</th>
                        <th>Cliente</th>
                        <th>E</th>
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
                        <td>{{ row.name }}</td>
                        <td>{{ row.fullname }}</td>

                        <td>
                            <button
                                type="button"
                                v-on:click="show(row)"
                                class="btn bg-success btn-sm"
                            >
                                <i class="fi fi-eye"></i>
                            </button>
                        </td>
                    </tr>
                </template>
            </VTable>
        </div>
    </div>
</template>
<script>
import { mapState } from "vuex";
import date_now from "../../mixins/date";

export default {
    $_veeValidate: {
        validator: "new",
    },
    data() {
        return {
            update: false,
            submitted: true,
            send: 1,
            url: "api/fupdate",
            form: {
                id: null,
                other: 0,
                efecty: 0,
                type_sale: 0,
                user_id: null,
                client_id: null,
                tot: 0,
            },
            filters: {
                id: { value: "", keys: ["id"] },
            },
        };
    },
    computed: {
        ...mapState(["factures", "moneySingle", "users", "clients"]),
    },
    created() {
        this.getList();
    },
    methods: {
        show(row) {
            this.form.id = row.id;
            this.form.efecty = parseFloat(row.efecty);
            this.form.other = parseFloat(row.other);
            this.form.tot = parseFloat(row.tot);
            this.form.user_id = row.idu;
            this.form.client_id = row.idc;
            this.form.type_sale = row.type_sale;
            this.send = true;
            this.update = true;
            this.filters.id = "";
        },
        route() {
            this.$validator.validate().then((valid) => {
                if (valid) {
                    this.updateFacture();
                }
            });
        },
        async updateFacture() {
            this.send = false;
            this.update = false;
            if (this.form.other == 0) {
                this.form.type_sale = 1;
            }
            let response = await axios.put(
                this.url + "/" + this.form.id,
                this.form
            );
            try {
                Swal.fire({
                    position: "center",
                    icon: "success",
                    title: `${response.data.message}`,
                    showConfirmButton: false,
                    timer: 1500,
                });
                this.getList();
                this.clear();
            } catch (error) {
                console.log(error.response);
            }
        },
        getList() {
            this.$store.dispatch("Factureactions", date_now);
            this.$store.dispatch("MoneySigleactions");
            this.$store.dispatch("Useractions");
            this.$store.dispatch("Clientactions");
        },
        equalsTot() {
            this.form.other = this.form.tot - this.form.efecty;
        },
        clear() {
            this.form.id = null;
            this.form.other = 0;
            this.form.efecty = 0;
            this.form.user_id = null;
            this.send = true;
        },
    },
};
</script>
