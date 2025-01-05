<template>
    <div>
        <Modal-Resource
            v-on:clear="clear"
            title="Registro de clientes"
            sone="modal-dialog modal-lg"
        >
            <section slot="titlebutton">Registrar clientes</section>

            <section v-if="!form.id" slot="title">Registro de clientes</section>
            <section v-else slot="title">Editar clientes</section>
            <section slot="closebtn">
                <button
                    @click="clear()"
                    type="button"
                    class="close"
                    data-dismiss="modal"
                    aria-label="Close"
                >
                    <span aria-hidden="true">&times;</span>
                </button>
            </section>
            <section slot="body">
                <form
                    method="POST"
                    @submit.enter.prevent="add(form.id, actions, urlclients)"
                    autocomplete="off"
                    onKeyPress="if(event.keyCode == 13) event.returnValue = false;"
                >
                    <div class="row">
                        <div class="col-lg-3 col-12">
                            <div class="form-group">
                                <label for>Nit</label>
                                <input
                                    type="number"
                                    v-validate="'required|numeric|max:11|min:1'"
                                    class="form-control form-control-sm"
                                    :class="{
                                        'is-invalid':
                                            submitted && errors.has('nit'),
                                    }"
                                    placeholder="Identificación"
                                    v-model.number="form.nit"
                                    name="nit"
                                />
                                <div
                                    v-if="submitted && errors.has('nit')"
                                    class="invalid-feedback"
                                >
                                    {{ errors.first("nit") }}
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-12">
                            <div class="form-group">
                                <label for>Nombre</label>
                                <input
                                    type="text"
                                    ref="name"
                                    v-validate="'required|max:30|min:3'"
                                    class="form-control form-control-sm"
                                    :class="{
                                        'is-invalid':
                                            submitted && errors.has('nombre'),
                                    }"
                                    placeholder="Nombre"
                                    v-model="form.fullname"
                                    name="nombre"
                                />
                                <div
                                    v-if="submitted && errors.has('nombre')"
                                    class="invalid-feedback"
                                >
                                    {{ errors.first("nombre") }}
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-2 col-12">
                            <div class="form-group">
                                <label for>Tel</label>
                                <input
                                    type="number"
                                    v-validate="
                                        'required|between:1,10000000000'
                                    "
                                    class="form-control form-control-sm"
                                    :class="{
                                        'is-invalid':
                                            submitted && errors.has('teléfono'),
                                    }"
                                    v-model.number="form.phone"
                                    name="teléfono"
                                />
                                <div
                                    v-if="submitted && errors.has('teléfono')"
                                    class="invalid-feedback"
                                >
                                    {{ errors.first("teléfono") }}
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4 col-12">
                            <div class="form-group">
                                <label for>Email</label>
                                <input
                                    type="email"
                                    class="form-control form-control-sm"
                                    placeholder="Email"
                                    v-model="form.email"
                                    name="email"
                                />
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
                        v-bind:class="{
                            'btn btn-outline-primary ': !this.form.id,
                            'btn btn-outline-danger ': this.form.id,
                        }"
                    >
                        <i
                            v-bind:class="{
                                'fi fi-wink': !this.form.id,
                                'fi fi-like': this.form.id,
                            }"
                            aria-hidden="true"
                        ></i>
                    </button>
                </form>
                <div v-if="this.form.id != null">
                    <div class="alert alert-primary mt-3" role="alert">
                        Últimas 5 compras del clienteS.
                    </div>

                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">Nombre</th>
                                <th scope="col">Fecha venta</th>
                                <th scope="col">Total</th>
                                <th scope="col">Vendedor</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="item in clientot" :key="item.id">
                                <th scope="row">{{ item.name }}</th>
                                <td>{{ item.date }}</td>
                                <td>{{ item.tot }}</td>
                                <td>{{ item.user }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>
        </Modal-Resource>
    </div>
</template>
<script>
import { mapState } from "vuex";
import ModalResource from "../utilities/modal.vue";
import add from "../../mixins/add";

export default {
    $_veeValidate: {
        validator: "new",
    },
    name: "add",
    components: {
        ModalResource,
    },

    data() {
        return {
            actions: "Clientactions",
            submitted: true,
            send: true,
            price_default: 1000,
            form: {
                id: null,
                nit: null,
                fullname: "",
                phone: 0,
                email: null,
            },
        };
    },
    mixins: [add],

    computed: {
        ...mapState(["urlclients", "clientot"]),
    },
    methods: {
        show(row) {
            this.$store.dispatch("clientTotactions", row.id);
            this.form.id = row.id;
            this.form.nit = row.nit;
            this.form.fullname = row.fullname;
            this.form.phone = row.phone;
            this.form.email = row.email;

            $("#model").modal("show");
            this.send = true;
        },

        clear() {
            this.form.id = null;
            this.form.nit = null;
            this.form.fullname = null;
            this.form.phone = null;
            this.form.email = null;

            this.$validator.reset();
            this.send = true;
            this.clientot.length = "";
        },
    },
};
</script>
