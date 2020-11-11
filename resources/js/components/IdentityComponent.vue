<template>
    <div class="col-md-4">
    <div>
        <div class="card">
            <div class="card-header"> Identity</div>

            <div class="card-body">
                <img v-if="user.identity" width="100%" height="95" :src="user.identity_url">
                <button v-if="! user.identity" type="button" class="btn btn-primary" data-toggle="modal" data-target="#uploadModal">
                    Upload
                </button>
            </div>
        </div>
    </div>

        <!-- Modal -->
        <div class="modal fade" id="uploadModal" tabindex="-1" role="dialog" aria-labelledby="uploadModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="uploadModalLabel">Upload Identity</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form ref="uploadForm">
                            <div class="form-group row">
                                <div class="col-md-6">
                                    <div v-if="!identity">
                                        <input id="identity" type="file" @change="onFileChange">
                                    </div>
                                    <div v-else>
                                        <img width="100%" :src="identity" />
                                        <button @click="removeImage">Remove image</button>
                                    </div>

                                    <span v-if="error.isShown" class="text-danger">
                                        <strong>{{ error.message.identity ? error.message.identity[0] : '' }}</strong>
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
                        <button type="button" :disabled="loading" @click="upload" class="btn btn-primary">Upload</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['user'],
        data() {
            return {
                loading: false,
                identity: '',
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
            upload() {
                this.loading = true
                axios.post('/identity', {
                    identity: this.identity
                }).then((response) => {
                    this.success.isShown = true;
                    this.success.message = 'ID upload successful';
                    this.error.isShown = false;
                    this.error.message = ''
                    this.$refs.uploadForm.reset();
                    window.location.reload()
                }).catch((err) => {
                    this.loading = false
                    this.error.isShown = true;
                    this.error.message = err.response.data.errors;
                });
            },
            onFileChange(e) {
                this.error.message = ''
                let files = e.target.files || e.dataTransfer.files;
                if (!files.length)
                    return;
                this.createImage(files[0]);
            },
            createImage(file) {
                let reader = new FileReader();
                let vm = this;

                reader.onload = (e) => {
                    vm.identity = e.target.result;
                };
                reader.readAsDataURL(file);
            },
            removeImage() {
                this.identity = '';
            }
        }
    }
</script>
