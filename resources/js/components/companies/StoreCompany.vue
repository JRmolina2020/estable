<template>
    <div>
        <section>
            <form
                v-if="view"
                method="POST"
                @submit.enter.prevent="add(form.id, actions, urlcompany)"
                autocomplete="off"
                onKeyPress="if(event.keyCode == 13) event.returnValue = false;"
            >
                <div class="row">
                    <div class="col-lg-4">
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
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label for>Empresa</label>
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
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label for>Teléfono</label>
                            <input
                                type="number"
                                v-validate="'required|between:1,10000000000'"
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
                <div class="row">
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label for>Representante</label>
                            <input
                                type="text"
                                ref="representante"
                                v-validate="'required|max:50|min:3'"
                                class="form-control form-control-sm"
                                :class="{
                                    'is-invalid':
                                        submitted &&
                                        errors.has('representante'),
                                }"
                                v-model="form.representative"
                                name="representante"
                            />
                            <div
                                v-if="submitted && errors.has('representante')"
                                class="invalid-feedback"
                            >
                                {{ errors.first("representante") }}
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label for>Dirección</label>
                            <input
                                type="text"
                                ref="direction"
                                v-validate="'required|max:50|min:3'"
                                class="form-control form-control-sm"
                                :class="{
                                    'is-invalid':
                                        submitted && errors.has('dirección'),
                                }"
                                v-model="form.direction"
                                name="dirección"
                            />
                            <div
                                v-if="submitted && errors.has('dirección')"
                                class="invalid-feedback"
                            >
                                {{ errors.first("dirección") }}
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label for>Ciudad</label>
                            <input
                                type="text"
                                ref="city"
                                v-validate="'required|max:50|min:3'"
                                class="form-control form-control-sm"
                                :class="{
                                    'is-invalid':
                                        submitted && errors.has('ciudad'),
                                }"
                                v-model="form.city"
                                name="ciudad"
                            />
                            <div
                                v-if="submitted && errors.has('ciudad')"
                                class="invalid-feedback"
                            >
                                {{ errors.first("ciudad") }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label for>Nota</label>
                            <textarea
                                type="text"
                                rows="3"
                                class="form-control form-control-sm"
                                v-model="form.note"
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
        </section>
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
    computed: {
        ...mapState(["urlcompany", "company"]),
    },
    created() {
        if (this.company.length == 0) {
            this.view = 1;
        } else {
            this.view = 0;
        }
    },
    data() {
        return {
            actions: "Companyactions",
            submitted: true,
            send: true,
            view: 1,
            form: {
                id: null,
                nit: null,
                name: "",
                representative: "",
                phone: 0,
                direction: "",
                city: "",
                note: "",
            },
        };
    },
    mixins: [add],
    methods: {
        show(row) {
            this.view = 1;
            this.form.id = row.id;
            this.form.nit = row.nit;
            this.form.name = row.name;
            this.form.representative = row.representative;
            this.form.phone = row.phone;
            this.form.direction = row.direction;
            this.form.city = row.city;
            this.form.note = row.note;
            $("#model").modal("show");
            this.send = true;
        },
        clear() {
            this.view = 0;
            this.form.id = "";
            this.form.nit = "";
            this.form.name = "";
            this.form.representative = "";
            this.form.phone = "";
            this.form.direction = "";
            this.form.city = "";
            this.form.note = "";
            this.$validator.reset();
            this.send = true;
        },
    },
};
</script>
