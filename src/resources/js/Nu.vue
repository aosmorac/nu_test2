<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <form @submit.prevent="submitForm" class="gmail-form">
                    <h2 class="mb-4">Contact Form</h2>
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" class="form-control" id="name" v-model="form.name" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" v-model="form.email" required>
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone Number</label>
                        <input type="tel" class="form-control" id="phone" v-model="form.phone" required>
                    </div>
                    <div class="form-group">
                        <label for="comment">Questions or comments</label>
                        <textarea class="form-control" id="comment" rows="5" v-model="form.comment"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Send</button>
                </form>
                <div v-if="message" class="mt-3 alert alert-success">
                    {{ message }}
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            form: {
                name: '',
                email: '',
                phone: '',
                comment: '',
            },
            message: '',
        };
    },
    methods: {
        submitForm() {
            axios
                .post('http://localhost:8070/api/v1/contact', this.form)
                .then(response => {
                    this.message = response.data.message;
                    this.resetForm();
                })
                .catch(error => {
                    console.log(error);
                });
        },
        resetForm() {
            this.form = {
                name: '',
                email: '',
                phone: '',
                comment: '',
            };
        },
    },
};
</script>

<style scoped>
.container {
    margin-top: 50px;
}

.gmail-form {
    background-color: #f1f3f4;
    padding: 40px;
    border-radius: 8px;
}

.form-group {
    margin-bottom: 20px;
}

label {
    font-weight: 600;
}

.btn-primary {
    background-color: #4a8cf7;
    border-color: #4a8cf7;
}

.btn-primary:hover {
    background-color: #386fe7;
    border-color: #386fe7;
}
</style>
