<template>
    <div>
        <Modal-Resource
            v-on:clear="clear"
            title="Roles de usuarios"
            sone="modal-dialog modal-sm"
        >
            <section slot="titlebutton">Registrar Rol</section>
            <section v-if="!form.id" slot="title">Registro de rol</section>
            <section v-else slot="title">Editar Rol</section>
            <section slot="closebtn">
                <button
                    type="button"
                    class="close"
                    @click="clearrolesitem"
                    data-dismiss="modal"
                    aria-label="Close"
                >
                    <span aria-hidden="true">&times;</span>
                </button>
            </section>
            <section slot="body">
                <form
                    method="POST"
                    @submit.enter.prevent="add(form.id, actions, urlroles)"
                    autocomplete="off"
                    onKeyPress="if(event.keyCode == 13) event.returnValue = false;"
                >
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for>Nombre</label>
                                <input
                                    type="text"
                                    ref="name"
                                    v-validate="'required|max:20|min:3'"
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
                    <div v-if="form.id">
                        <table class="table table-dark table-striped">
                            <thead>
                                <tr>
                                    <th>Permiso</th>
                                    <th>
                                        <i class="fi fi-wink"></i>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="(item, index) in permissions"
                                    :key="index"
                                >
                                    <td>{{ item.name }}</td>
                                    <td>
                                        <div class="checkbox">
                                            <label>
                                                <input
                                                    type="checkbox"
                                                    v-model="form.permissions"
                                                    :value="item.name"
                                                />
                                            </label>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
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
            actions: "Roleactions",
            submitted: true,
            send: 1,
            price_default: 1000,
            rolesitem: [],
            form: {
                id: null,
                name: "",
                permissions: [],
            },
        };
    },
    mixins: [add],
    computed: {
        ...mapState(["urlroles", "permissions"]),
    },
    created() {
        this.getlist();
    },
    methods: {
        getlist() {
            this.$store.dispatch("Permissionactions");
        },
        show(row) {
            this.form.id = row.id;
            this.form.name = row.name;
            row.permissions.forEach((element) => {
                this.rolesitem.push(element.name);
            });
            this.form.permissions = this.rolesitem;
            $("#model").modal("show");
            this.send = true;
        },
        clearrolesitem() {
            this.rolesitem = [];
            $("#model").modal("hide");
        },
        clear() {
            this.form.id = null;
            this.form.name = null;
            this.$validator.reset();
            this.rolesitem = [];
            this.form.permissions = [];
            this.send = true;
        },
    },
};
</script>
