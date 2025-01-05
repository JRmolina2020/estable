const { date_facture } = require("lodash");

module.exports = {
    methods: {
        getList() {
            let date = new Date();
            let day = date.getDate();
            let month = date.getMonth() + 1;
            let year = date.getFullYear();

            if (month < 10) {
                let date_now = `${year}-0${month}-${day}`;
                this.$store.dispatch("Factureactions", date_now);
                this.$store.dispatch("TypeSaleactions", date_now);
                this.$store.dispatch("Billtotactions", date_now);
            } else {
                let date_now = `${year}-${month}-${day}`;
                this.$store.dispatch("Factureactions", date_now);
                this.$store.dispatch("TypeSaleactions", date_now);
                this.$store.dispatch("Billtotactions", date_now);
            }
        },
    },
};
