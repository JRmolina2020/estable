<template>
    <div>
        <!-- detailsx -->
        <div class="row">
            <div class="">
                <button
                    type="button"
                    class="btn btn-primary prod"
                    data-toggle="modal"
                    data-target="#exampleDetail"
                >
                    Productos
                </button>
                <span class="badge badge-warning">PF {{ sumProducts }}</span>
            </div>

            <div class="table-responsive mt-3">
                <table class="table table-striped table-dark">
                    <thead>
                        <tr>
                            <th scope="col">Item</th>
                            <th scope="col">Precio</th>
                            <th scope="col">Cant</th>
                            <th scope="col">Sub</th>
                            <th scope="col">
                                <i class="fi fi-dollar"></i>
                            </th>
                            <th scope="col">Total</th>
                            <th>Op</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="(item, index) in formFacture.dataDetails"
                            :key="index"
                        >
                            <td>
                                {{ item.name }}
                            </td>
                            <td>{{ item.price | currency }}</td>
                            <td>
                                <button
                                    type="button"
                                    @click="decrementDetail(index)"
                                    class="btn bg-danger btn-xs"
                                >
                                    <i class="fi fi-angle-down"></i>
                                </button>
                                {{ item.quantity }}

                                <button
                                    v-if="item.quantity < item.stock"
                                    type="button"
                                    @click="incrementDetail(index)"
                                    class="btn bg-success btn-xs"
                                >
                                    <i class="fi fi-angle-up"></i>
                                </button>
                            </td>
                            <td>
                                {{
                                    (formFacture.dataDetails[index].sub =
                                        item.price * item.quantity) | currency
                                }}
                            </td>
                            <td>
                                <button
                                    v-can="'precio update'"
                                    type="button"
                                    class="btn btn-primary"
                                    @click="pricestate(index, item.name)"
                                >
                                    <i class="fi fi-dollar"></i>
                                </button>
                            </td>
                            <td v-if="formFacture.dataDetails[index].disc == 0">
                                {{
                                    (formFacture.dataDetails[index].tot =
                                        onviewTotDetail[index]) | currency
                                }}
                            </td>
                            <td class="bg-info" v-else>
                                {{
                                    (formFacture.dataDetails[index].tot =
                                        onviewTotDetail[index]) | currency
                                }}
                            </td>
                            <td>
                                <button
                                    type="button"
                                    @click="removeDetail(index)"
                                    class="btn bg-danger"
                                >
                                    <i class="fi fi-close-a"></i>
                                </button>
                            </td>
                            <!-- edit price and add discount details products -->

                            <!-- end  -->
                        </tr>
                    </tbody>
                </table>
                <div v-if="tabledark">
                    usted esta editando temporalmente el precio de
                    {{ pname }}<br />
                    <!-- edit price -->
                    <label style="color: black">Otro precio</label>
                    <currency-input
                        name="precio"
                        id="precio"
                        v-validate="{
                            required: true,
                            min_value: 0,
                        }"
                        :class="{
                            'is-invalid': submitted && errors.has('precio'),
                        }"
                        v-currency="{
                            currency: 'USD',
                            precision: 0,
                            locale: 'en',
                        }"
                        v-model="price[pindex]"
                        @change="updatePrice(pindex)"
                        class="form-control form-control-sm col-lg-4 co-lg-12"
                    />
                    <!-- add discount -->
                    <div class="form-group">
                        <label style="color: black">Descuento</label>
                        <currency-input
                            name="descuento"
                            id="descuento"
                            v-validate="{
                                required: true,
                                min_value: 0,
                                max_value: formFacture.dataDetails[pindex].sub,
                            }"
                            :class="{
                                'is-invalid':
                                    submitted && errors.has('descuento'),
                            }"
                            v-model="discDetail[pindex]"
                            v-on:keyup="calculateDisc()"
                            class="form-control form-control-sm col-lg-4 co-lg-12"
                            v-currency="{
                                currency: 'USD',
                                precision: 0,
                                locale: 'en',
                            }"
                        />
                    </div>
                    <button
                        type="button"
                        class="btn btn-danger"
                        data-dismiss="modal"
                        @click="calculateEfecty()"
                    >
                        <i class="fi fi-caret-left"></i>
                    </button>
                    <br />

                    <!-- end input discount -->
                </div>
            </div>
        </div>
        <!-- facture main -->
        <form
            method="POST"
            @submit.enter.prevent="addFacture()"
            autocomplete="off"
            onKeyPress="if(event.keyCode == 13) event.returnValue = false;"
        >
            <div class="row mt-3">
                <div class="col-6 col-lg-2">
                    <div class="form-group">
                        <label for>Venta</label>
                        <select
                            class="form-control form-control-sm"
                            v-model="type_sale"
                            required
                            @change="typeSale()"
                        >
                            <option value="1">Mayorista</option>
                            <option value="0">Detal</option>
                        </select>
                    </div>
                </div>
                <div class="col-lg-4 col-6">
                    <div class="form-group">
                        <label>Cliente</label>
                        <v-select
                            :options="clients"
                            v-model="formFacture.client_id"
                            :reduce="(clients) => clients.id"
                            label="fullname"
                        >
                            <template #search="{ attributes, events }">
                                <input
                                    class="vs__search"
                                    :required="!formFacture.client_id"
                                    v-bind="attributes"
                                    v-on="events"
                                />
                            </template>
                        </v-select>
                    </div>
                </div>
                <div class="col-6 col-lg-2">
                    <div class="form-group">
                        <label for>Subtotal</label>
                        <currency-input
                            disabled
                            :value="onViewSub"
                            class="form-control form-control-sm"
                            v-currency="{
                                currency: 'USD',
                                precision: 0,
                                locale: 'en',
                            }"
                        />
                    </div>
                </div>

                <div class="col-6 col-lg-2">
                    <div class="form-group">
                        <label for>Descuento</label>
                        <currency-input
                            v-model.number="formFacture.disc"
                            class="form-control form-control-sm"
                            v-currency="{
                                currency: 'USD',
                                precision: 0,
                                locale: 'en',
                            }"
                            @keyup="equalsTot()"
                        />
                    </div>
                </div>
                <div class="col-6 col-lg-2">
                    <div class="form-group">
                        <label for>Total</label>
                        <currency-input
                            disabled
                            class="form-control form-control-sm"
                            :value="onViewTot"
                            v-currency="{
                                currency: 'USD',
                                precision: 0,
                                locale: 'en',
                            }"
                        />
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-6 col-lg-2">
                    <div class="form-group">
                        <label for>Efectivo</label>
                        <currency-input
                            name="efectivo"
                            id="efectivo"
                            required
                            v-validate="{
                                required: true,
                                min_value: 0,
                                max_value: onViewTot,
                            }"
                            :class="{
                                'is-invalid':
                                    submitted && errors.has('efectivo'),
                            }"
                            v-model="formFacture.efecty"
                            @keyup="equalsTot()"
                            class="form-control form-control-sm"
                            v-currency="{
                                currency: 'USD',
                                precision: 0,
                                locale: 'en',
                            }"
                        />
                    </div>
                </div>
                <div class="col-6 col-lg-2">
                    <label for>Otros</label>
                    <input
                        class="form-control form-control-sm"
                        type="number"
                        v-model.number="formFacture.other"
                        disabled
                    />
                </div>
                <div class="col-6 col-lg-2">
                    <div class="form-group">
                        <label>Vendedor</label>
                        <select
                            v-model="formFacture.user"
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

                <div v-if="formFacture.other > 0" class="col-6 col-lg-2">
                    <div class="form-group">
                        <label for="my-select">Banco</label>
                        <select
                            v-model="formFacture.type_sale"
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
                <div class="col-6 col-lg-2">
                    <div class="form-group">
                        <label for="status">Estado</label>
                        <select
                            class="form-control form-control-sm"
                            v-model="formFacture.status"
                        >
                            <option value="1">Pagado</option>
                            <option value="0">Pendiente</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <label for>Observación</label>
                    <textarea
                        class="form-control"
                        v-model="formFacture.note"
                        id="observación"
                        name="observación"
                        rows="2"
                        placeholder="Añade una observación."
                    ></textarea>
                </div>
            </div>
            <div class="row">
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
                <div
                    v-if="
                        (send,
                        formFacture.dataDetails.length != 0 &&
                            onViewSub >= formFacture.disc)
                    "
                    class="col-lg-2"
                >
                    <button
                        type="submit"
                        :disabled="errors.any()"
                        class="btn btn btn-primary btn-sm mt-3"
                    >
                        Guardar venta
                    </button>
                </div>
                <div
                    class="col-lg-2"
                    v-if="formFacture.dataDetails.length != 0"
                ></div>
            </div>
        </form>
        <!-- modal details products-->
        <div
            class="modal fade"
            data-backdrop="static"
            data-keyboard="false"
            id="exampleDetail"
            tabindex="-1"
            aria-labelledby="exampleModalLabel"
            aria-hidden="true"
        >
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">
                            Detalle de factura
                        </h5>
                        <div>
                            <button
                                type="button"
                                @click="
                                    (divproduct = true), (sendproduct = true)
                                "
                                class="btn bg-black btn-sm"
                            >
                                <i class="fi fi-flash"></i>
                            </button>

                            <button
                                @click="calculateEfecty()"
                                type="button"
                                class="btn btn-danger btn-sm"
                                data-dismiss="modal"
                            >
                                <i class="fi fi-close-a"></i>
                            </button>
                        </div>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <input
                                type="text"
                                class="form-control"
                                v-model="filters"
                                placeholder="Buscar productos"
                            />
                        </div>
                        <div v-if="divproduct">
                            <form
                                method="POST"
                                @submit.enter.prevent="addProduct()"
                                autocomplete="off"
                                onKeyPress="if(event.keyCode == 13) event.returnValue = false;"
                            >
                                <div class="row">
                                    <div class="col-lg-4 col-md-6">
                                        <div class="form-group">
                                            <label for>Nombre</label>
                                            <input
                                                required
                                                type="text"
                                                class="form-control form-control-sm"
                                                v-model="product.name"
                                            />
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6">
                                        <div class="form-group">
                                            <label for>Precio mayorista</label>
                                            <currency-input
                                                required
                                                class="form-control form-control-sm"
                                                v-currency="{
                                                    currency: 'USD',
                                                    precision: 0,
                                                    locale: 'en',
                                                }"
                                                v-model.number="product.price"
                                                name="precio de compra"
                                            />
                                        </div>
                                    </div>
                                </div>
                                <button
                                    v-if="!sendproduct"
                                    class="btn btn-dark btn-sm"
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
                                    v-if="sendproduct"
                                    :disabled="errors.any()"
                                    type="submit"
                                    class="btn btn-dark btn-sm"
                                >
                                    <i class="fi fi-check"></i>
                                </button>
                                <button
                                    type="button"
                                    @click="divproduct = false"
                                    class="btn btn-danger btn-sm"
                                >
                                    <i class="fi fi-close-a"></i>
                                </button>
                            </form>
                        </div>
                        <div class="row mt-3">
                            <div
                                v-for="(item, index) in filteredList"
                                :key="'t' + index"
                                class="col-4"
                            >
                                <div
                                    v-if="item.stock <= 0"
                                    class="card text-white bg-dark mb-3"
                                    style="max-width"
                                >
                                    <div class="card-body">
                                        {{ item.name }}<br />
                                        <span class="badge bg-danger"
                                            >Agotado</span
                                        >
                                    </div>
                                </div>
                                <div
                                    v-else
                                    class="card text-white bg-dark mb-3"
                                    style="max-width"
                                    @click="addRow(item, index)"
                                >
                                    <div class="card-body">
                                        <p>
                                            {{ item.name }}
                                            {{ item.categorie_id }}
                                        </p>
                                        <p
                                            v-if="type_sale === 1"
                                            class="card-text"
                                        >
                                            {{ item.price | currency }}
                                        </p>
                                        <p v-else class="card-text">
                                            {{ item.price_two | currency }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button
                            @click="calculateEfecty()"
                            type="button"
                            class="btn btn-danger"
                            data-dismiss="modal"
                        >
                            <i class="fi fi-close-a"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import { mapState } from "vuex";
import date_facture from "../../mixins/dateFacture";
export default {
    $_veeValidate: {
        validator: "new",
    },
    name: "add",
    components: {},

    data() {
        return {
            divproduct: false,
            pindex: 0,
            pname: "",
            tabledark: false,
            actions: "Factureactions",
            submitted: true,
            send: true,
            sendproduct: true,
            totalPages: 1,
            currentPage: 1,
            filters: "",
            type_sale: 1,
            discDetail: [],
            //fictyUpdate
            price: [],
            //
            //

            product: {
                name: "",
                price: 0,
                price_two: 0,
                cost: 0,
            },
            formFacture: {
                id: 0,
                client_id: 1,
                sub: 0,
                disc: 0,
                tot: 0,
                efecty: null,
                other: 0,
                note: "",
                status: 1,
                user: 0,
                type_sale: 1,
                dataDetails: [],
            },
        };
    },
    mixins: [date_facture],
    created() {
        this.getData();
        this.formFacture.type_sale = "Bancolombia";
        this.$store.dispatch("Useractions");
        this.$store.dispatch("MoneySigleactions");
    },
    computed: {
        ...mapState([
            "urlfactures",
            "urlproducts",
            "users",
            "clients",
            "products",
            "moneySingle",
        ]),

        filteredList() {
            return this.products.filter((products) => {
                return products.name
                    .toLowerCase()
                    .includes(this.filters.toLowerCase());
            });
        },
        onViewSub() {
            let subtot = 0;
            this.formFacture.dataDetails.map((data) => {
                subtot = subtot + data.quantity * data.price;
            });
            return subtot;
        },
        sumProducts() {
            let tot = 0;
            this.formFacture.dataDetails.map((data) => {
                tot = parseInt(tot) + parseInt(data.quantity);
            });
            return tot;
        },
        onViewTot() {
            let sub = this.onViewSub;
            let disc = this.formFacture.disc;
            if (sub >= disc) {
                let tot = parseInt(sub) - parseInt(disc);
                return tot;
            }
        },
        onviewTotDetail() {
            let tot = [];
            for (let i = 0; i < this.formFacture.dataDetails.length; i++) {
                tot[i] =
                    this.formFacture.dataDetails[i].sub - this.discDetail[i];
            }
            return tot;
        },
    },
    methods: {
        async addFacture() {
            //agregando valores calculados fuction en el form
            this.formFacture.sub = this.onViewSub;
            this.formFacture.tot = this.onViewTot;
            this.send = false;
            if (this.formFacture.other == 0) {
                this.formFacture.type_sale = 1;
            }

            let response = await axios.post(this.urlfactures, this.formFacture);
            try {
                Swal.fire({
                    position: "center-start",
                    icon: "success",
                    title: `${response.data.message}`,
                    showConfirmButton: false,
                    timer: 1500,
                });

                this.getList();
                this.clear();
                this.$store.dispatch("Productactions", 1);
                this.send = true;
            } catch (error) {
                console.log(error.response);
            }
        },
        getData() {
            this.$store.dispatch("Clientactions");
            this.$store.dispatch("Productactions", 1);
        },
        async addProduct() {
            this.sendproduct = false;
            this.product.price_two = this.product.price + 10000;
            let response = await axios.post(this.urlproducts, this.product);
            try {
                Swal.fire({
                    position: "center-start",
                    title: `${response.data.message}`,
                    showConfirmButton: false,
                    timer: 1000,
                });
                this.$store.dispatch("Productactions", 1);
                this.product.name = "";
                this.product.price = 0;
                this.divproduct = false;
                this.sendproduct = true;
            } catch (error) {
                console.log(error.response);
            }
        },
        addRow(row, index) {
            console.log(this.type_sale);
            Swal.fire({
                position: "center-start",
                icon: "success",
                title: `el producto ${row.name} se agregó`,
                showConfirmButton: false,
                timer: 400,
            });
            let price = 0;
            if (this.type_sale == 1) {
                price = row.price;
            } else {
                price = row.price_two;
            }
            price = parseFloat(price);
            this.formFacture.dataDetails.push({
                product_id: row.id,
                name: row.name,
                price: price,
                quantity: 1,
                stock: row.stock,
                sub: 0,
                disc: 0,
                tot: 0,
            });

            //para cuandro se agregue a la lista del detalle el descuento tome valor 0
            for (let i = 0; i < this.formFacture.dataDetails.length; i++) {
                this.discDetail[i] = 0;
            }
            //evitar valores duplicados en el carrito
            this.ProductRowUnique();
        },
        ProductRowUnique() {
            const cartProduct = this.formFacture.dataDetails.reduce(
                (acum, valueActuality) => {
                    const elementTrue = acum.find(
                        (element) =>
                            element.product_id === valueActuality.product_id
                    );
                    if (elementTrue) {
                        return acum.map((element) => {
                            if (
                                element.product_id === valueActuality.product_id
                            ) {
                                return {
                                    ...element,
                                    quantity:
                                        element.quantity +
                                        valueActuality.quantity,
                                };
                            }

                            return element;
                        });
                    }

                    return [...acum, valueActuality];
                },
                []
            );
            this.formFacture.dataDetails = cartProduct;
        },

        calculateDisc() {
            let totDisc = 0;
            for (let i = 0; i < this.formFacture.dataDetails.length; i++) {
                totDisc = parseInt(totDisc + this.discDetail[i]);
                this.formFacture.dataDetails[i].disc = this.discDetail[i];
            }

            this.formFacture.disc = totDisc;
        },

        clear() {
            this.formFacture.id = 0;
            this.formFacture.client_id = 1;
            this.formFacture.sub = 0;
            this.formFacture.disc = 0;
            this.formFacture.tot = 0;
            this.formFacture.efecty = 0;
            this.formFacture.other = 0;
            this.formFacture.note = "";
            this.formFacture.status = 1;
            this.formFacture.user = "";
            this.formFacture.type_sale = "Bancolombia";
            this.formFacture.dataDetails = [];
            this.price = [];
        },
        typeSale() {
            if (this.type_sale == 1) {
                this.type_sale = 1;
                Swal.fire({
                    position: "center",
                    icon: "success",
                    title: "Usted ha escogido venta mayorista",
                    showConfirmButton: true,
                });
            } else {
                this.type_sale = 0;
                Swal.fire({
                    position: "center",
                    icon: "success",
                    title: "Usted ha escogido venta al detal",
                    showConfirmButton: true,
                });
            }
        },
        incrementDetail(index) {
            this.formFacture.dataDetails[index].quantity++;
            this.calculateEfecty();
        },
        decrementDetail(index) {
            let num = this.formFacture.dataDetails[index].quantity;
            if (num != 1) {
                this.formFacture.dataDetails[index].quantity--;
            }
            this.calculateEfecty();
        },
        updatePrice(index) {
            this.formFacture.dataDetails[index].price = this.price[index];
        },

        removeDetail(index) {
            this.formFacture.dataDetails.splice(index, 1);
            this.calculateEfecty();
        },
        equalsTot() {
            this.formFacture.other = this.onViewTot - this.formFacture.efecty;
        },
        calculateEfecty() {
            this.formFacture.efecty = this.onViewTot;
            this.formFacture.other = 0;
            this.filters = "";
            this.tabledark = false;
        },
        pricestate(index, name) {
            this.tabledark = true;
            this.pindex = index;
            this.pname = name;
        },
    },
};
</script>
