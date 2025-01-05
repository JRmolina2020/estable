module.exports = {
    methods: {
        thestatus(row, route, prefijo) {
            if (row.status == 1) {
                let url = route + "/locked/" + row.id;
                axios.put(url).then(response => {
                    Swal.fire({
                        title: `${prefijo} ${row.name} ${response.data.message}`,
                        icon: "success"
                    });
                    this.getList();
                });
            } else {
                let url = route + "/available/" + row.id;
                axios.put(url).then(response => {
                    Swal.fire({
                        title: `${prefijo} ${row.name} ${response.data.message}`,
                        icon: "success"
                    });
                    this.getList();
                });
            }
        }
    }
};