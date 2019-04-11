import axios from 'axios'

export default ({ Vue }) => {
  Vue.prototype.$axios = axios.create({
    baseURL: process.env.API,
    headers: {
      'Accept': 'application/json'
    }
  })
  axios.interceptors.request.use(config => {
    const token = localStorage.getItem('myteste@token')
    if (token) {
      config.headers.Authorization = `Bearer ${token}`
    }
    return config
  }, function (err) {
    return Promise.reject(err)
  })
}
