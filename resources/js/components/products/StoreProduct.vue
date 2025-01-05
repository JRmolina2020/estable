<template>
    <div>
        <Modal-Resource
            v-on:clear="clear"
            sone="modal-dialog modal-xs"
            title="Registro de productos"
        >
            <section slot="titlebutton">Registrar productos</section>
            <section v-if="!form.id" slot="title">
                Registro de productos
            </section>
            <section v-else slot="title">Editar producto</section>
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
                    @submit.enter.prevent="add(form.id, urlproducts)"
                    autocomplete="off"
                    onKeyPress="if(event.keyCode == 13) event.returnValue = false;"
                >
                    <div class="row">
                        <div class="col-lg-6 col-12">
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
                        <div class="col-lg-6 col-12">
                            <div class="form-group">
                                <label for>Costo</label>
                                <currency-input
                                    v-validate="{
                                        required: true,
                                    }"
                                    class="form-control form-control-sm"
                                    v-currency="{
                                        currency: 'USD',
                                        precision: 0,
                                        locale: 'en',
                                    }"
                                    :class="{
                                        'is-invalid':
                                            submitted && errors.has('costo'),
                                    }"
                                    v-model.number="form.cost"
                                    name="costo"
                                />
                                <div
                                    v-if="submitted && errors.has('costo')"
                                    class="invalid-feedback"
                                >
                                    {{ errors.first("costo") }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 col-12">
                            <div class="form-group">
                                <label for>Precio mayorista</label>
                                <currency-input
                                    @change="typePrice()"
                                    v-validate="'|required|min_value:0'"
                                    class="form-control form-control-sm"
                                    v-currency="{
                                        currency: 'USD',
                                        precision: 0,
                                        locale: 'en',
                                    }"
                                    :class="{
                                        'is-invalid':
                                            submitted &&
                                            errors.has('precio mayorista1'),
                                    }"
                                    v-model.number="form.priceSv"
                                    name="precio mayorista"
                                />
                                <div
                                    v-if="
                                        submitted &&
                                        errors.has('precio mayorista')
                                    "
                                    class="invalid-feedback"
                                >
                                    {{ errors.first("precio mayorista") }}
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6 col-12">
                            <div class="form-group">
                                <label for>Precio de venta</label>
                                <currency-input
                                    @change="typePrice()"
                                    v-validate="{
                                        min_value: 0,
                                    }"
                                    class="form-control form-control-sm"
                                    v-currency="{
                                        currency: 'USD',
                                        precision: 0,
                                        locale: 'en',
                                    }"
                                    :class="{
                                        'is-invalid':
                                            submitted &&
                                            errors.has('precio de venta'),
                                    }"
                                    v-model.number="form.price_twoSv"
                                    name="precio de venta"
                                />
                                <div
                                    v-if="
                                        submitted &&
                                        errors.has('precio de venta')
                                    "
                                    class="invalid-feedback"
                                >
                                    {{ errors.first("precio de venta") }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 col-12">
                            <div class="form-group">
                                <label>Categoria</label>
                                <v-select
                                    :options="categories"
                                    v-model="form.categorie_id"
                                    :reduce="(categories) => categories.id"
                                    label="name"
                                >
                                    <template #search="{ attributes, events }">
                                        <input
                                            class="vs__search"
                                            :required="!form.categorie_id"
                                            v-bind="attributes"
                                            v-on="events"
                                        />
                                    </template>
                                </v-select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-2 col-12">
                            <select
                                v-model="form.type_iva"
                                @change="typePrice()"
                            >
                                <option value="0">0</option>
                                <option value="19">19%</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-lg-6 col-6">
                            <div class="form-group">
                                <label for>Valor iva mayorista</label>
                                <input
                                    class="form-control form-control-sm"
                                    type="number"
                                    disabled
                                    v-model.number="form.vm"
                                />
                            </div>
                        </div>

                        <div class="col-lg-6 col-6">
                            <div class="form-group">
                                <label for>Valor iva detal</label>
                                <input
                                    class="form-control form-control-sm"
                                    type="number"
                                    disabled
                                    v-model.number="form.vd"
                                />
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-lg-6 col-6">
                            <div class="form-group">
                                <label for>PM F</label>
                                <input
                                    class="form-control form-control-sm"
                                    type="number"
                                    v-model.number="form.price"
                                    disabled
                                />
                            </div>
                        </div>

                        <div class="col-lg-6 col-6">
                            <div class="form-group">
                                <label for>PV F</label>
                                <input
                                    class="form-control form-control-sm"
                                    type="number"
                                    disabled
                                    v-model.number="form.price_two"
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
                <br />
            </section>
        </Modal-Resource>
    </div>
</template>
<script>
import { mapState } from "vuex";
import ModalResource from "../utilities/modal.vue";
import add from "../../mixins/add";
import VueBarcode from "vue-barcode";

export default {
    $_veeValidate: {
        validator: "new",
    },
    name: "add",
    components: {
        ModalResource,
        barcode: VueBarcode,
    },
    computed: {
        ...mapState(["urlproducts", "categories"]),
    },
    data() {
        return {
            submitted: true,
            send: 1,
            price_default: 1000,
            form: {
                id: null,
                name: "",
                price: 0,
                priceSv: 0, //porcentaje de iva venta al mayor
                price_two: 0,
                price_twoSv: 0, //porcentaje de iva venta detal
                type_iva: 0,
                vm: 0, //add
                vd: 0,
                //end
                //ver valor
                vd: 0,
                vm: 0,
                cost: 0,
                categorie_id: null,
            },
        };
    },

    created() {
        this.getCategorie();
    },
    methods: {
        add(id, url) {
            this.$validator.validate().then((valid) => {
                if (valid) {
                    this.route(id, url);
                }
            });
        },
        async route(id, url) {
            this.send = false;
            if (id) {
                let response = await axios.put(url + "/" + id, this.form);
                try {
                    this.$store.dispatch("Productactions", 1);
                    Swal.fire({
                        position: "center",
                        icon: "success",
                        title: `${response.data.message}`,
                        showConfirmButton: false,
                        timer: 1500,
                    });
                    $("#model").modal("hide");
                    this.clear();
                } catch (error) {
                    console.log(error.response);
                }
            } else {
                let response = await axios.post(url, this.form);

                try {
                    Swal.fire({
                        position: "center",
                        icon: "success",
                        title: `${response.data.message}`,
                        showConfirmButton: false,
                        timer: 1600,
                    });

                    $("#model").modal("hide");
                    this.$store.dispatch("Productactions", 1);
                } catch (error) {
                    console.log(error.response);
                }
            }
        },
        getCategorie() {
            this.$store.dispatch("Categorieactions");
        },
        typePrice() {
            if (this.form.type_iva == 0) {
                this.form.vm = 0;
                this.form.vd = 0;
                this.form.price = this.form.priceSv;
                this.form.price_two = this.form.price_twoSv;
            } else {
                //precio al mayor
                this.typeCalculate();
            }
        },
        typeCalculate() {
            let porcentajeSv1 = this.form.priceSv * (19 / 100);
            //precio de venta
            let porcentajeSv2 = this.form.price_twoSv * (19 / 100);
            //agregando el porcentaje al input
            this.form.vm = porcentajeSv1;
            this.form.vd = porcentajeSv2;
            //agregando la suma a inputs de precio final
            this.form.price = this.form.priceSv + porcentajeSv1;
            this.form.price_two = this.form.price_twoSv + porcentajeSv2;
        },
        show(row) {
            this.form.id = row.id;
            this.form.type_iva = "";
            this.form.name = row.name;
            this.form.price = parseFloat(row.price);
            this.form.price_two = parseFloat(row.price_two);
            //view show
            this.form.priceSv = parseFloat(row.priceSv);
            this.form.price_twoSv = parseFloat(row.price_twoSv);
            this.form.type_iva = row.type_iva;
            console.log(row.type_iva);

            this.form.cost = parseFloat(row.cost);
            this.form.categorie_id = row.idc;
            $("#model").modal("show");
            this.send = true;
        },
        clear() {
            this.form.id = null;
            this.form.name = null;
            this.form.price = 0;
            this.form.priceSv = 0;
            this.price_twoSv = 0;
            this.form.price_two = 0;
            this.form.cost = 0;
            this.form.categorie_id = null;
            this.$validator.reset();
            this.send = true;
        },
    },
};
</script>
<style>
.div-1 {
    background-color: #ebebeb;
}
</style>
