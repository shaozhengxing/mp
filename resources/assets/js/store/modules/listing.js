import * as type from './../mutationType'
import api from './../../api'

const state = {
    names: []
}

const getters = {
    names: state => state.names
}

const mutations = {
    [type.LOAD_NAMES](state, {data}) {
        state.names = data
    },
}

const actions = {
    loadNames ( { commit } ) {
        return api.loadNames()
            .then(data => {
                return commit(type.LOAD_NAMES, data)
            })
    }
}

export default {
    state,
    getters,
    mutations,
    actions
}