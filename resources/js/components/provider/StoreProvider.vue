<template>
    <div>
        <Modal-Resource
            v-on:clear="clear"
            title="Registro de proveedores"
            sone="modal-dialog modal-sm"
        >
            <section slot="titlebutton">Registrar</section>

            <section v-if="!form.id" slot="title">
                Registro de proveedores
            </section>
            <section v-else slot="title">Editar proveedores</section>
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
                    @submit.enter.prevent="add(form.id, actions, urlprovider)"
                    autocomplete="off"
                    onKeyPress="if(event.keyCode == 13) event.returnValue = false;"
                >
                    <div class="row">
                        <div class="col 12">
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
                                    v-model="form.name"
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
                    </div>
                    <div class="row">
                        <div class="col 12">
                            <div class="form-group">
                                <label for>Empresa</label>
                                <input
                                    type="text"
                                    ref="name"
                                    v-validate="'required|max:50|min:3'"
                                    class="form-control form-control-sm"
                                    :class="{
                                        'is-invalid':
                                            submitted && errors.has('company'),
                                    }"
                                    placeholder="company"
                                    v-model="form.company"
                                    name="company"
                                />
                                <div
                                    v-if="submitted && errors.has('company')"
                                    class="invalid-feedback"
                                >
                                    {{ errors.first("company") }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col 12">
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
            actions: "Provideractions",
            submitted: true,
            send: true,
            price_default: 1000,
            form: {
                id: null,
                name: "",
                company: "",
                phone: 0,
            },
        };
    },
    mixins: [add],

    computed: {
        ...mapState(["urlprovider"]),
    },
    methods: {
        show(row) {
            this.form.id = row.id;
            this.form.name = row.name;
            this.form.company = row.company;
            this.form.phone = row.phone;

            $("#model").modal("show");
            this.send = true;
        },

        clear() {
            this.form.id = null;
            this.form.name = null;
            this.form.company = null;
            this.form.phone = null;
            this.$validator.reset();
            this.send = true;
        },
    },
};
</script>
