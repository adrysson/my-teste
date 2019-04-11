export default {
  namespaced: true,
  state: {
    userId: 0
  },
  getters: {
    getUserId: state => state.userId
  },
  mutations: {
    setUserId (state, userId) {
      state.userId = userId
    }
  }
}
