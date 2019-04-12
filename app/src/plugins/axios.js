import axios from 'axios'

export default ({ Vue }) => {
  Vue.prototype.$axios = axios.create({
    baseURL: process.env.API,
    headers: {
      'Accept': 'application/json'
    }
  })
  Vue.prototype.$axios.interceptors.request.use(config => {
    let action = config.url.split('/').pop()
    const token = localStorage.getItem('myteste@token')
    if (token && action !== 'entrar') {
      config.headers.Authorization = `Bearer ${token}`
    }
    return config
  }, function (err) {
    return Promise.reject(err)
  })
}
