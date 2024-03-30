
import { createApp } from 'vue';

import App from './components/Pages/Index.vue';
import router from './router';
import store from './store';

import PrimeVue from 'primevue/config';

import InputNumber from 'primevue/inputnumber';
import Button from 'primevue/button';
import SelectButton from 'primevue/selectbutton';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Calendar from 'primevue/calendar';
import FloatLabel from 'primevue/floatlabel';

import 'bootstrap';
import 'primevue/resources/themes/md-light-indigo/theme.css';
import 'primevue/resources/primevue.css';
import 'primeflex/primeflex.css';

const main = createApp(App);

main.component('InputNumber', InputNumber);
main.component('Button', Button);
main.component('SelectButton', SelectButton);
main.component('DataTable', DataTable);
main.component('Column', Column);
main.component('Calendar', Calendar);
main.component('FloatLabel', FloatLabel);

main.use(store);
main.use(router);

main.use(PrimeVue);

main.mount("#app");
