const { add } = require("lodash");

module.exports = {
    methods: {
        add(id, actions, url) {
            this.$validator.validate().then((valid) => {
                if (valid) {
                    this.route(id, actions, url);
                }
            });
        },
        async route(id, actions, url) {
            this.send = false;
            if (id) {
                let response = await axios.put(url + "/" + id, this.form);
                try {
                    this.$store.dispatch(actions);
                    Swal.fire({
                        position: "center",
                        icon: "success",
                        title: `${response.data.message}`,
                        showConfirmButton: false,
                        timer: 1500,
                    });
                    $("#model").modal("hide");
                    this.clear();
                } catch (error) {
                    console.log(error.response);
                }
            } else {
                let response = await axios.post(url, this.form);

                try {
                    Swal.fire({
                        position: "center",
                        icon: "success",
                        title: `${response.data.message}`,
                        showConfirmButton: false,
                        timer: 1600,
                    });
                    console.log(response)
                    $("#model").modal("hide");
                    this.$store.dispatch(actions);
                    this.clear();
                } catch (error) {
                    console.log(error.response);
                }
            }
        },
    },
};
