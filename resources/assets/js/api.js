import axios from 'axios'

const http = axios.create({
    baseURL: 'http://127.0.0.1:8000/api/'
});

const api =  {
    loadNames: function () {
        return http.get('user.all')
    }
}

export default api
