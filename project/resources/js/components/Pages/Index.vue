<template>
    <h1>Калькулятор криптоинвестиций</h1>

    <div>Зафиксировать перевод
        <InputNumber v-model="sumRub" mode="currency" currency="RUB" locale="ru-RU"/>
        по курсу <b>USDT$ </b>
          <InputNumber v-model="course" mode="currency" currency="RUB" locale="ru-RU"/>
        <Button class="m-2" @click.prevent="buyForFiat">Зафиксировать</Button>
    </div>
    <br>

    <div>Выводы</div>
    <DataTable :value="fiatPayments" tableStyle="min-width: 50rem">
        <Column field="sum" header="Cумма в рублях"></Column>
        <Column field="sum_in_currency" header="Сумма в USDT"></Column>
        <Column field="course" header="Курс"></Column>
        <Column>
            <template #body="{ data }">
                <Button label="Удалить сделку" @click="deleteFiat(data.id)"/>
            </template>
        </Column>
    </DataTable>

    <div>Инвестировано в рублях: <b>{{ allSum }}</b></div>
    <div>Инвестировано в USDT$: <b>{{ allSumUsdt }}</b></div>
    <br>
    <br>
    <br>

    <div>Зафиксировать покупку <br><br>
        <SelectButton v-model="selectedCoin" :options="cryptoCoins" aria-labelledby="basic" @click="selectCrypto"/>
        <br>
        на сумму <InputNumber v-model="usdtAmount" prefix="USDT$  "/>
         по курсу
        <InputNumber v-model="selectedCoinCourse" prefix="USDT$  "  :maxFractionDigits="4"/>
        <br>
        <br>
        <FloatLabel>
            <Calendar v-model="purchaseDate" inputId="purchase_date" dateFormat="yy-mm-dd" hourFormat="24"/>
            <label for="purchase_date"> Дата покупки</label>
        </FloatLabel>
        <Button class="m-2" @click.prevent="buyCrypto">Зафиксировать</Button>
    </div>

    <br>
    <br>

    <div>Покупки криптовалюты</div>
    <DataTable :value="purchases" tableStyle="min-width: 50rem">
        <Column field="currency" header="Актив"></Column>
        <Column field="course" header="Курс"></Column>
        <Column field="sum_in_currency" header="Сумма"></Column>
        <Column field="purchase_date" header="Дата покупки"></Column>
        <Column field="sell_date" header="Дата продажи"></Column>
        <Column field="sell_course" header="Курс продажи"></Column>
        <Column>
            <template #body="{ data }">
                <Button label="Зафиксировать продажу" @click="dialogVisible = true" :class="saleImpossible(data)"/>
            </template>
        </Column>
        <Column>
            <template #body="{ data }">
                <Button label="Удалить сделку" @click="deleteCrypto(data.id)"/>
            </template>
        </Column>
    </DataTable>

    <canvas></canvas>

    <h2>Результат</h2>
    <br>
    <div>
        <div>Текущая сумма в USDT: <b>{{ currentSumUsdt }}</b></div>
        <div>Текущая сумма в рублях: <b>{{ currentSumRub }}</b></div>
        <br>
        <div>Общая доходность: <b>{{ profitability }}%</b></div>

    </div>

    <Dialog v-model:visible="dialogVisible" modal header="Зафиксировать продажу актива" :style="{ width: '25rem' }">
        <InputNumber v-model="this.coinCourses[this.dialogDeal.currency]" prefix="USDT$  " :maxFractionDigits="4"/>
        <br>
        <br>
        <FloatLabel>
            <Calendar v-model="sellDate" inputId="sell_date" dateFormat="yy-mm-dd" hourFormat="24"/>
            <label for="sell_date">Дата продажи</label>
        </FloatLabel>
        <Button class="m-2" @click.prevent="recordTheSale">Зафиксировать</Button>
    </Dialog>
</template>

<script>
import axios from 'axios';
import Chart from 'chart.js/auto';

export default {
    name: "Index",

    data() {
        return {
            fiatPayments: [],
            sumRub: 10000,
            course: 0,
            allSum: 0,
            allSumUsdt: 0,
            chart: null,
            selectedCoin: 'WBTC',
            cryptoCoins: [],
            usdtAmount: 100,
            selectedCoinCourse: null,
            coinCourses: null,
            purchases: [],
            purchaseDate: null,
            currentSumUsdt: 0,
            currentSumRub: 0,
            profitability: 0,
            dialogVisible: false,
            sellDate: null,
            disabled: '',
            dialogDeal: null
        }
    },
    mounted() {
        this.getFiatPayments();
        this.getSum();
        this.getCoins();
        this.getCoinPurchases();
        this.createLineChart(this.selectedCoin);
    },
    computed: {

    },
    methods: {
        async getFiatPayments() {
            axios.get('/fiat_payments').then(res => {
                this.fiatPayments = res.data.data;
            });
        },
        deleteFiat(id) {
            axios.delete('/fiat_payments/'+id);

            location.reload();
        },
        buyForFiat() {
            axios.post('/fiat_payments', {sum: this.sumRub, course: this.course});
            this.getSum();

            location.reload();
        },
        getSum() {
            axios.get('/fiat_payments/get_sum').then(res => {
                this.allSum = res.data.sum;
                this.allSumUsdt = res.data.sumUsdt;
                this.currentSumUsdt = res.data.currentSumUsdt;
                this.currentSumRub = res.data.currentSumRub;
                this.profitability = res.data.profitability;
            });
        },
        getCoinPurchases() {
            axios.get('/crypto_payments/purchases/'+this.selectedCoin).then(res => {
                this.purchases = res.data.purchases;
            });
        },
        getCoins() {
            axios.get('/fiat_payments/courses').then(res => {

                this.cryptoCoins = Object.keys(res.data.data.cryptoCoins);

                this.coinCourses = res.data.data.cryptoCoins;
                this.selectedCoinCourse = this.coinCourses[this.selectedCoin];
                console.log(this.coinCourses);

                this.course = res.data.data.USDT;
            });
        },
        selectCrypto(event) {
            this.selectedCoinCourse = this.coinCourses[this.selectedCoin];
            this.getCoinPurchases(this.selectedCoin);
            this.createLineChart(this.selectedCoin);
        },
        twoDigits(num) {
            return ('0' + num).slice(-2);
        },
        convertDate(date) {
            let d = new Date(date);
            //is primevue calendar bag (month +1)
            return d.getFullYear() + '-' + this.twoDigits(d.getMonth()+1) + '-' + this.twoDigits(d.getDate());
        },
        buyCrypto() {
            axios.post('crypto_payments/buy_crypto', {
                sum: this.usdtAmount,
                course: this.selectedCoinCourse,
                currency: this.selectedCoin,
                purchase_date: this.convertDate(this.purchaseDate)
            });

            location.reload();
        },
        deleteCrypto(id) {
            axios.delete('crypto_payments/'+id);

            location.reload();
        },
        async createLineChart(coin) {
            if (this.chart) {
                this.chart.destroy();
            }

            let response = await axios.get('crypto_payments/chart/'+coin);
            let responseData = response.data;
            let xData = responseData.times;
            let yData = responseData.values;
            let types = responseData.types;

            let canvas = window.document.querySelector('canvas');
            let context = canvas.getContext('2d');

            console.log(xData.length);

            let pointColors = [];
            types.forEach((item) => {
                if (item == 'default') {
                    pointColors.push('#ffff00');
                } else if (item == 'buy') {
                    pointColors.push('#000000');
                } else {
                    pointColors.push('#ff0000');
                }
            });

            let data = {
                labels: xData,
                datasets: [{
                    data: yData,
                    borderColor: pointColors,
                },]
            }

            let config = {
                type: 'line',
                data: data,
                options: {
                    plugins: {
                        title: {
                            display: true,
                            text: this.selectedCoin
                        }
                    },
                },
            }

            Chart.defaults.plugins.legend.display = false;
            this.chart = new Chart(context, config);
        },
        async recordTheSale() {
            await axios.patch('crypto_payments/sell_crypto/'+this.dialogDeal.id, {
                sell_course: this.coinCourses[this.dialogDeal.currency],
                sell_date: this.convertDate(this.sellDate)
            });

            location.reload();
        },
        saleImpossible(data) {
            this.dialogDeal = data;

            if (data.sell_date == null) {
                return '';
            }

            return 'p-disabled'
        }
    }
}
</script>
