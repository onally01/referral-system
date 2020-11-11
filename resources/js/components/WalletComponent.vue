<template>
    <div class="col-md-4">
    <div>
        <div class="card">
            <div class="card-header"> Wallet</div>

            <div class="card-body">
                <h1>{{ wallet | toCurrency }}</h1>
                <button v-if="wallet > 0" type="button" class="btn btn-primary" data-toggle="modal" data-target="#withdrawalModal">
                    Withdraw
                </button>
            </div>
        </div>
    </div>

        <!-- Modal -->
        <div class="modal fade" id="withdrawalModal" tabindex="-1" role="dialog" aria-labelledby="withdrawalModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="withdrawalModalLabel">Withdrawal</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form method="POST">

                            <div class="form-group row">
                                <label for="amount" class="col-md-4 col-form-label text-md-right">Amount</label>

                                <div class="col-md-6">
                                    <input id="amount" type="number" class="form-control" v-model="amount">

                                    <span v-if="error.isShown" class="text-danger">
                                        <strong>{{ error.message.amount ? error.message.amount[0] : '' }}</strong>
                                    </span>
                                    <span v-if="success.isShown" class="text-success">
                                        <strong>{{ success.message }}</strong>
                                    </span>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" :disabled="loading" @click="withdraw" class="btn btn-primary">Withdraw</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['wallet'],
        data() {
            return {
                loading: false,
                amount: 0,
                error: {
                    isShown: false,
                    message: ''
                },
                success: {
                    isShown: false,
                    message: ''
                },
            };
        },
        methods: {
            withdraw() {
                this.loading = true
                axios.post('/withdraw', {
                    amount: this.amount
                }).then((response) => {
                    this.success.isShown = true;
                    this.success.message = 'Withdrawal successful';
                    this.error.isShown = false;
                    this.error.message = ''
                    this.amount = 0;
                    window.location.reload()
                }).catch((err) => {
                    this.loading = false
                    this.error.isShown = true;
                    this.error.message = err.response.data.errors;
                });
            }
        }
    }
</script>
