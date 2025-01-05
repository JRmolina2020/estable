<template>
    <div>
        <Modal-Resource
            v-on:clear="clear"
            title="Registro de usuarios"
            sone="modal-dialog modal-sm"
        >
            <section slot="titlebutton">Registrar usuarios</section>
            <section v-if="!form.id" slot="title">Registro de usuarios</section>
            <section v-else slot="title">Editar usuarios</section>
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
                    @submit.enter.prevent="add(form.id, actions, urlusers)"
                    autocomplete="off"
                    onKeyPress="if(event.keyCode == 13) event.returnValue = false;"
                >
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for>Nombre</label>
                                <input
                                    type="text"
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
                        <div class="col-12">
                            <div class="form-group">
                                <label for>Email</label>
                                <input
                                    type="email"
                                    v-validate="'required|max:30|min:3'"
                                    class="form-control form-control-sm"
                                    :class="{
                                        'is-invalid':
                                            submitted && errors.has('email'),
                                    }"
                                    placeholder="Email"
                                    v-model="form.email"
                                    name="email"
                                />
                                <div
                                    v-if="submitted && errors.has('email')"
                                    class="invalid-feedback"
                                >
                                    {{ errors.first("email") }}
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div>
                                <div class="form-group">
                                    <label>Rol</label>
                                    <select
                                        v-model="form.rol"
                                        v-validate="'required'"
                                        class="form-control form-control-sm"
                                        name="rol"
                                        :class="{
                                            'is-invalid':
                                                submitted && errors.has('rol'),
                                        }"
                                    >
                                        <option
                                            v-for="(item, index) in roles"
                                            :value="item.name"
                                            :key="index"
                                        >
                                            {{ item.name }}
                                        </option>
                                    </select>
                                    <div
                                        v-if="submitted && errors.has('rol')"
                                        class="invalid-feedback"
                                    >
                                        {{ errors.first("rol") }}
                                    </div>
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
            actions: "Useractions",
            submitted: true,
            send: true,
            form: {
                id: null,
                name: "",
                email: "",
                rol: [],
            },
        };
    },
    mixins: [add],
    created() {
        this.getlist();
    },
    computed: {
        ...mapState(["urlusers", "roles"]),
    },
    methods: {
        getlist() {
            this.$store.dispatch("Roleactions");
        },
        show(row) {
            this.form.id = row.id;
            this.form.name = row.name;
            this.form.email = row.email;
            this.form.rol = row.roles[0].name;
            $("#model").modal("show");
            this.send = true;
        },
        clear() {
            this.form.id = null;
            this.form.name = null;
            this.form.email = null;
            this.form.rol = null;
            this.$validator.reset();
            this.send = true;
        },
    },
};
</script>
