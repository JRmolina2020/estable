<template>
    <div>
        <div class="text-center">
            <img
                class="profile-user-img img-fluid img-circle"
                src="https://www.kindpng.com/picc/m/78-786207_user-avatar-png-user-avatar-icon-png-transparent.png"
                alt="User profile picture"
                height="60"
                width="60"
            />
            <h3 class="profile-username text-center">{{ currentUser.name }}</h3>
            <p class="text-muted text-center">{{ currentUser.email }}</p>
        </div>
        <form
            method="POST"
            @submit.prevent="updatePassword(currentUser.id)"
            autocomplete="off"
            enctype="multipart/form-data"
        >
            <div class="row">
                <div class="col-lg-12">
                    <div class="form-group">
                        <label for>password new</label>
                        <input
                            type="password"
                            v-validate="'required|max:15|min:3'"
                            class="form-control form-control-sm"
                            :class="{
                                'is-invalid':
                                    submitted && errors.has('password'),
                            }"
                            placeholder="nuevo password"
                            v-model="form.password"
                            name="password"
                            ref="password"
                        />
                        <div
                            v-if="submitted && errors.has('password')"
                            class="invalid-feedback"
                        >
                            {{ errors.first("password") }}
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="form-group">
                        <label for>password confirmation</label>
                        <input
                            type="password"
                            v-model="form.password_confirmed"
                            v-validate="'required|confirmed:password'"
                            data-vv-as="password"
                            class="form-control form-control-sm"
                            :class="{
                                'is-invalid':
                                    submitted &&
                                    errors.has('password_confirmation'),
                            }"
                            placeholder="confirmar password"
                            name="password_confirmation"
                        />
                        <div
                            v-if="
                                submitted && errors.has('password_confirmation')
                            "
                            class="invalid-feedback"
                        >
                            {{ errors.first("password_confirmation") }}
                        </div>
                    </div>
                </div>
            </div>
            <button
                :hidden="errors.any()"
                type="submit"
                class="btn btn-outline-primary"
            >
                <li class="fi fi-wink"></li>
            </button>
        </form>
    </div>
</template>
<script>
export default {
    $_veeValidate: {
        validator: "new",
    },
    data() {
        return {
            submitted: true,
            url: "/api/user/password/",
            form: {
                password: null,
                password_confirmed: null,
            },
        };
    },
    methods: {
        async updatePassword(id) {
            let url = `${this.url}${id}`;
            let response = await axios.put(url, this.form);
            try {
                Swal.fire({
                    position: "center",
                    icon: "success",
                    title: `${response.data.message}`,
                    showConfirmButton: false,
                    timer: 1500,
                });
                this.form.password = null;
                this.form.password_confirmed = null;
                this.$validator.reset();
            } catch (error) {
                console.log(error);
            }
        },
    },
};
</script>
