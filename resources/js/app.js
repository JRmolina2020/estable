require("./bootstrap");

window.Vue = require("vue").default;
window.Axios = require("axios");
import store from "./store";
import "core-js/stable";
import "regenerator-runtime/runtime";
;
//utils
//table
import SmartTable from "vuejs-smart-table";
Vue.use(SmartTable);
//end
//alert
import Swal from "sweetalert2";
window.Swal = Swal;
//end
// star currency
import VueCurrencyInput from "vue-currency-input";
import VueCurrencyFilter from "vue-currency-filter";
Vue.use(VueCurrencyInput);
Vue.use(VueCurrencyFilter);
//end
//start validate
import es from "vee-validate/dist/locale/es";
import VeeValidate, { Validator } from "vee-validate";
Vue.use(VeeValidate);
Validator.localize("es", es);
//end
//start select
import vSelect from "vue-select";
Vue.component("v-select", vSelect);
import "vue-select/dist/vue-select.css";
//end
Vue.directive("can", function (el, binding, vnode) {
    if (Permissions.indexOf(binding.value) !== -1) {
        return (vnode.elm.hidden = false);
    } else {
        return (vnode.elm.hidden = true);
    }
});
//

//end utils
//components
Vue.component(
    "user_example",
    require("./components/users/UserExample.vue").default
);
Vue.component(
    "profile_example",
    require("./components/users/ProfileUser.vue").default
);
Vue.component("login", require("./components/login.vue").default);
Vue.component(
    "product_example",
    require("./components/products/ProductExample.vue").default
);
Vue.component(
    "categorie_example",
    require("./components/categories/CategorieExample.vue").default
);
Vue.component(
    "client_example",
    require("./components/clients/ClientExample.vue").default
);
Vue.component(
    "provider_example",
    require("./components/provider/ProviderExample.vue").default
);

Vue.component(
    "facture_example",
    require("./components/factures/FactureExample.vue").default
);
Vue.component(
    "fstate_example",
    require("./components/factures/FstateExample.vue").default
);
Vue.component("Role", require("./components/roles/RoleExample.vue").default);
Vue.component(
    "permission_example",
    require("./components/permissions/PermissionExample.vue").default
);

Vue.component(
    "bill_example",
    require("./components/bills/BillExample.vue").default
);

Vue.component("gain_example", require("./components/gain.vue").default);

Vue.component(
    "company_example",
    require("./components/companies/CompanyExample.vue").default
);
Vue.component(
    "money_example",
    require("./components/money/MoneyExample.vue").default
);
Vue.component(
    "income_example",
    require("./components/incomes/Example.vue").default
);
Vue.component(
    "inventory_example",
    require("./components/inventory.vue").default
);


Vue.component(
    "updatefacture_example",
    require("./components/factures/UpdateFacture.vue").default
);

import auth from "./mixins/Auth.js";
Vue.mixin(auth);
const app = new Vue({
    el: "#app",
    store,
});
