<template>
    <div>
        <div v-if="viewfac">
            <div v-if="status">
                <div class="row">
                    <div class="col-lg-4 col-xs-12 col-sm-12 col-md-12">
                        <div
                            class="alert alert-success alert-dismissible fade show"
                            role="alert"
                        >
                            <strong>Hola :)</strong> Estas son las ventas del
                            dia
                            <button
                                type="button"
                                class="close"
                                data-dismiss="alert"
                                aria-label="Close"
                            >
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-3">
                        <div class="input-group">
                            <input
                                class="form-control form-control-sm"
                                type="date"
                                v-model="date"
                                placeholder=".form-control-sm"
                            />
                            <div class="input-group-append">
                                <button
                                    class="btn btn-outline-secondary btn-sm"
                                    @click="getDate()"
                                    type="button"
                                >
                                    Buscar
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="input-group">
                            <select
                                v-model="search_sale"
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
                            <div class="input-group-append">
                                <button
                                    class="btn btn-success btn-sm"
                                    @click="getTypeSale()"
                                    type="button"
                                >
                                    Buscar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <VTable
                        :data="factures"
                        :page-size="5"
                        :currentPage.sync="currentPage"
                        @totalPagesChanged="totalPages = $event"
                        class="table table-striped table-borderless mt-3"
                    >
                        <template #head>
                            <tr style="color: #fff; background: black">
                                <th>#</th>
                                <th>Total</th>
                                <th>E</th>
                                <th>O</th>
                                <th>Banco</th>
                                <th>Vendedor</th>
                                <th>Estado</th>
                                <th>POS</th>
                                <th>Fac</th>
                                <th>D</th>
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
                                <th>{{ row.name }}</th>
                                <td>
                                    <span class="badge badge-success"
                                        >Pagado</span
                                    >
                                </td>

                                <td>
                                    <Modal-Ticket
                                        v-bind:cod="row.id"
                                    ></Modal-Ticket>
                                </td>
                                <td>
                                    <button
                                        class="btn bg-primary btn-sm"
                                        @click="viewTabledetail(row.id)"
                                    >
                                        <i class="fi fi-shopping-basket"></i>
                                    </button>
                                </td>

                                <td>
                                    <button
                                        v-can="'enviar factura'"
                                        type="button"
                                        @click="emailFac(row.id)"
                                        class="btn bg-primary btn-sm"
                                    >
                                        <i class="fi fi-skype"></i>
                                    </button>
                                </td>
                                <td v-can="'eliminar factura'">
                                    <button
                                        type="button"
                                        @click="destroy(row.id)"
                                        class="btn bg-danger btn-sm"
                                    >
                                        <i class="fi fi-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        </template>
                    </VTable>
                    <div class="text-xs-center">
                        <VTPagination
                            :currentPage.sync="currentPage"
                            :total-pages="totalPages"
                            :boundary-links="true"
                            :maxPageLinks="4"
                        />
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-4">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <td>Monto</td>
                                    <td>Efectivo</td>
                                    <td>Otros</td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr
                                    v-for="(item, index) in typeSale"
                                    :key="index"
                                >
                                    <th>{{ item.tot | currency }}</th>
                                    <th>{{ item.efecty | currency }}</th>
                                    <th>{{ item.other | currency }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <div class="col-lg-2">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <td>Gasto total</td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr
                                    v-for="(item, index) in billstot"
                                    :key="index"
                                >
                                    <th>{{ item.price | currency }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <div class="col-lg-2">
                        <table class="table table-bordered table-dark">
                            <tbody>
                                <tr>
                                    <td>Banco total</td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr
                                    v-for="(item, index) in typeSale_one"
                                    :key="index"
                                >
                                    <th>{{ item.other | currency }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <div class="col-lg-2">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <td>Gasto total</td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>
                                        <button @click="SendW()" type="button">
                                            generar cierre
                                        </button>
                                    </th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                <div v-if="factures.length == 0">
                    <div class="alert alert-danger" role="alert">
                        No existe información de venta para este dia
                    </div>
                </div>
            </div>
        </div>
        <div v-else>
            <ModalFac :cod="cod"></ModalFac>
        </div>
    </div>
</template>
<script>
import { mapState } from "vuex";

import ModalTicket from "../utilities/modalticket";

import ModalFac from "../utilities/modalfac";
import VueHtmlToPaper from "vue-html-to-paper";

const options = {
    name: "_blank",
    specs: ["fullscreen=yes", "titlebar=yes", "scrollbars=yes"],
    styles: ["https://unpkg.com/bootstrap/dist/css/bootstrap.min.css"],

    timeout: 1000,
    autoClose: true,
    windowTitle: "Vue Html To Paper - Vue mixin for html elements printing.",
};

Vue.use(VueHtmlToPaper, options);
import MgetList from "../../mixins/dateFacture";

export default {
    data() {
        return {
            cod: 0,
            viewfac: 1,
            date: "",
            totalPages: 1,
            currentPage: 1,
            search_sale: "",
        };
    },
    mixins: [MgetList],
    components: {
        ModalTicket,
        ModalFac,
    },
    computed: {
        ...mapState([
            "viewfx",
            "factures",
            "typeSale",
            "typeSale_one",
            "status",
            "urlfactures",
            "status",
            "billstot",
            "moneySingle",
            "products",
            "company",
        ]),
    },

    created() {
        this.getList();
    },
    methods: {
        getDate() {
            if (this.date == "") {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Te falta digitar la fecha!",
                });
            } else {
                let date = this.date;
                this.$store.dispatch("Factureactions", date);
                this.$store.dispatch("TypeSaleactions", date);
                this.$store.dispatch("Billtotactions", date);
                this.$store.dispatch("MoneySigleactions");
            }
        },
        getTypeSale() {
            if (this.date == "") {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Te falta digitar la fecha!",
                });
            } else {
                let obj = {
                    prop1: this.date,
                    prop2: this.search_sale,
                };
                this.$store.dispatch("TypeSale_one_actions", obj);
            }
        },

        async destroy(id) {
            let url = this.urlfactures + "/" + id;
            let response = await axios.delete(url);
            try {
                this.getList();
                Swal.fire({
                    title: `${response.data.message}`,
                    icon: "success",
                });
                this.$store.dispatch("Productactions");
            } catch (error) {
                console.log(error);
            }
        },

        async emailFac(id) {
            Swal.fire("Enviando...");
            let url = "api/emailfac/" + id;
            let response = await axios.get(url);
            try {
                Swal.fire("Realizado!", "success");
            } catch (error) {
                console.log(error);
            }
        },
        viewTabledetail(id) {
            this.viewfac = 0;
            this.cod = id;
            this.$store.dispatch("viewactions");
        },
        SendW() {
            //format
            const formatoPesos = new Intl.NumberFormat("es-CO", {
                style: "currency",
                currency: "COP",
                minimumFractionDigits: 0, // En Colombia, los pesos suelen mostrarse sin decimales
            });
            //end

            let tot = formatoPesos.format(this.typeSale[0].tot);
            let other = formatoPesos.format(this.typeSale[0].other);
            let efecty = formatoPesos.format(this.typeSale[0].efecty);
            const numero = "57" + this.company[0].phone; // Número en formato internacional sin '+'

            const mensaje = encodeURIComponent(
                "*Hola!* \n\nEspero que estés teniendo un gran día. \n *Quería mostrarte el cierre del dia:* \n *TOTAL VENTA:*" +
                    tot +
                    "\n*EFECTIVO:*" +
                    efecty +
                    "\n *OTROS:*" +
                    other +
                    ""
            );

            const url = `https://wa.me/${numero}?text=${mensaje}`;
            window.open(url, "_blank");
        },
    },
};
</script>
