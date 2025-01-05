<template>
    <div>
        <Modal-Resource
            v-on:clear="clear"
            sone="modal-dialog modal-sm"
            title="Registro de clientes"
        >
            <section slot="titlebutton">Registrar cuentas</section>
            <section v-if="!form.id" slot="title">Registro de cuentas</section>
            <section v-else slot="title">Editar cuentas</section>
            <section slot="closebtn">
                <button
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
                    @submit.enter.prevent="add(form.id, actions, urlmoney)"
                    autocomplete="off"
                    onKeyPress="if(event.keyCode == 13) event.returnValue = false;"
                >
                    <div class="row">
                        <div class="col-lg-6 col-12">
                            <div class="form-group">
                                <label for>Cuenta</label>
                                <input
                                    v-validate="'required|max:30|min:1'"
                                    class="form-control form-control-sm"
                                    :class="{
                                        'is-invalid':
                                            submitted && errors.has('cuenta'),
                                    }"
                                    placeholder="Ejemplo: Nequi/Bancolombia"
                                    v-model="form.name"
                                    name="cuenta"
                                />
                                <div
                                    v-if="submitted && errors.has('cuenta')"
                                    class="invalid-feedback"
                                >
                                    {{ errors.first("cuenta") }}
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-12">
                            <div class="form-group">
                                <label for="my-select">Estado</label>
                                <select
                                    v-model="form.status"
                                    class="form-control form-control-sm"
                                >
                                    <option value="1">Activa</option>
                                    <option value="0">Anulada</option>
                                </select>
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
    computed: {
        ...mapState(["urlmoney"]),
    },
    data() {
        return {
            actions: "Moneyactions",
            submitted: true,
            send: true,
            form: {
                id: null,
                name: null,
                status: 1,
            },
        };
    },
    mixins: [add],
    methods: {
        show(row) {
            this.form.id = row.id;
            this.form.name = row.name;
            this.form.status = row.status;

            $("#model").modal("show");
            this.send = true;
        },
        clear() {
            this.form.id = null;
            this.form.name = null;
            this.form.status = 1;
            this.$validator.reset();
            this.send = true;
        },
    },
};
</script>
