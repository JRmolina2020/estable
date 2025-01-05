wwww
<template>
    <div>
        <!-- Button trigger modal -->
        <button
            type="button"
            class="btn bg-success btn-sm"
            data-toggle="modal"
            :data-target="'#model' + cod"
            @click="getlistProducts"
        >
            <i class="fi fi-file-1"></i>
        </button>

        <div
            class="modal fade"
            :id="'model' + cod"
            tabindex="-1"
            role="dialog"
            aria-labelledby="modelTitleId"
            aria-hidden="true"
        >
            <div class="modal-dialog" style="width: 300px" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <div id="facture">
                            <div>
                                <center>
                                    <br /><br />
                                    <h5
                                        v-for="(item, index) in company"
                                        :key="index"
                                    >
                                        {{ item.name }}<br />Nit.{{ item.nit
                                        }}<br />
                                        {{ item.representative }}<br />
                                        {{ item.direction }}
                                        <br />
                                        {{ item.phone }}<br />
                                        {{ item.city }}<br />
                                    </h5>
                                </center>

                                <p
                                    id="estilo"
                                    v-for="(item, index) in facUnique"
                                    :key="'a' + index"
                                >
                                    fecha: {{ item.created_at }}<br />
                                    fac: POS #00{{ item.id }}<br />
                                    ------------------------------<br />
                                    {{ item.fullname }}<br />
                                    {{ item.phone }}<br />
                                    ------------------------------<br />
                                    {{ item.note }}
                                </p>
                            </div>
                            <strong>Productos:</strong>

                            <p v-for="(item, index) in details" :key="index">
                                {{ item.quantity }}X {{ item.name }} *{{
                                    item.price | currency
                                }}<br />
                                ${{
                                    item.sub | currency
                                }}....................<br />
                            </p>
                            ********************
                            <div
                                v-for="(item, index) in facUnique"
                                :key="'f' + index"
                            >
                                <p class="bodyText">
                                    <strong>Sub</strong> ${{
                                        item.sub | currency
                                    }}<br />
                                    <strong>Iva%</strong> $ 0.00<br />
                                    <strong>Desc</strong> ${{
                                        item.disc | currency
                                    }}<br />
                                    <strong>Tot</strong> ${{
                                        item.tot | currency
                                    }}<br />
                                    <strong>Cant:</strong>{{ sumProducts
                                    }}<br />
                                    <strong v-if="item.type_sale == 1"
                                        >Pago: Efectivo</strong
                                    >
                                    <strong v-else
                                        >Pago: {{ item.type_sale }}</strong
                                    >
                                </p>
                            </div>
                            ********************
                            <center>
                                <h5
                                    v-for="(item, index) in company"
                                    :key="index"
                                >
                                    {{ item.note }}
                                </h5>
                            </center>
                        </div>
                        <button @click="print('facture')">Inprimir</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import { mapState } from "vuex";
export default {
    props: {
        cod: {
            type: Number,
            required: true,
        },
    },
    components: {},
    data() {
        return {
            currentPage: 1,
            totalPages: 0,
        };
    },
    computed: {
        ...mapState(["details", "facUnique", "company"]),
        sumProducts() {
            let tot = 0;
            this.details.map((data) => {
                tot = parseInt(tot) + parseInt(data.quantity);
            });
            return tot;
        },
    },
    methods: {
        print(areaID) {
            setTimeout(function () {
                var printContent = document.getElementById(areaID);
                var WinPrint = window.open("", "", "width=500,height=750");
                WinPrint.document.write(printContent.innerHTML);
                WinPrint.document.close();
                WinPrint.focus();
                WinPrint.print();
            }, 300);
        },
        getlistProducts() {
            this.$store.dispatch("FactureDetailactions", this.cod);
            this.$store.dispatch("FactureUniquections", this.cod);
            this.$store.dispatch("Companyactions");
        },
    },
};
</script>
<style type="text/css">
@media print {
    p.bodyText {
        font-family: georgia, serif;
        font-size: 14px;
        color: blue;
    }
    @page {
        margin: 2cm;
    }
}
@media screen {
    p.bodyText {
        font-family: georgia, serif;
        font-size: 14px;
        color: blue;
    }
}
</style>
