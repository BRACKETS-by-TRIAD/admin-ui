const Admin = {
    el: '#app',
    data() {
        return {
            loading: false
        }
    },
    mounted() {
        // Add a loader request interceptor
        axios.interceptors.request.use((config) => {
            this.setLoading(true);
            return config;
        }, (error) => {
            this.setLoading(false);
            return Promise.reject(error);
        });

        // Add a loader response interceptor
        axios.interceptors.response.use((response) => {
            this.setLoading(false);
            return response;
        }, (error) => {
            this.setLoading(false);
            return Promise.reject(error);
        });
    },
    methods: {
        setLoading(value) {
            this.loading = !!value;
        }
    }
};

export default Admin;
