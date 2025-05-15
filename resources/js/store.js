import axios from 'axios';
import Vue from 'vue';
import Vuex from 'vuex';
import createPersistedState from 'vuex-persistedstate';

Vue.use(Vuex)
const persistedData = new createPersistedState({
    key:'carte_pcg',
    storage: localStorage,
    reducer : state => ({
        // isLoggedin : state.isLoggedin,
        // loggedInUser : state.loggedInUser,
        // MonitoringData : state.MonitoringData,
        userInfo : state.userInfo,
        method_items : state.method_items,
        pcgType_items : state.pcgType_items,
        carte_categories : state.carte_categories,
        team_items : state.team_items,
        // carte_users : state.carte_users
    })
})

export default new Vuex.Store({
    state:{
        isLoggedin : false,
        loggedInUser : null,
        allUsers : [],
        userInfo : null,
        MonitoringData : [],
        method_items : [],
        pcgType_items : [],
        carte_categories : [],
        team_items : [],
        carte_users : [],
        action_items: [],
        versions: [],
        cart_search: '',
    },

    actions:{
        resetTableScroll({ commit }, tableRef) {
            return new Promise((resolve) => {
                // commit('SET_LOADING', true, { root: true });
                
                // Wait for next tick to ensure DOM is updated
                tableRef.$nextTick(() => {
                const scrollContainer = tableRef.$el.querySelector('.v-table__overflow') || 
                                        tableRef.$el.firstElementChild;
                
                if (scrollContainer) {
                    scrollContainer.scrollTop = 0;
                }
                
                // commit('SET_LOADING', false, { root: true });
                resolve();
                });
            });
        },

        login(context){
            let user_data = {
                id : 1, 
                name : 'karl'
            }

            axios.post('/api/getUser', user_data)
            .then(response => {
                context.commit('login', response.data);
            })
        },

        logout(context){
            context.commit('logout');
        },

        storeUserInfo({commit},data){
            commit("storeUserInfo",data)
        },

        action_monitoring( {commit} ){
            axios({
                method : "post",
                url : 'api/indexMonitoring',
                data : {
                    method : "All",
                    pcg : "All",
                    team : "All",
                    status : "All",
                    category : "All",
                    dateFrom : null,
                    dateTo : null,
                }
            })
            .then( (res) => {
                console.log(res.data,'wwwwe')
                commit('mutation_monitoring', res.data)
            } )
        },

        async getVersions({commit},payload){
            console.log(payload,'from component')
            try {
                const response = await axios.get('api/get_versions',{
                    params: {
                        id: payload.id,
                    }
                });
                console.log(response,'get version response')
                commit('mutation_get_versions',response.data)
            } catch (error) {
                console.error(error);
                alert('getVersions() error, please reload page')
            }
        },

        async getActions({commit}){
            try {
                const response = await axios.get('api/get_actions');
                // console.log(response,'getActions');
                commit('mutation_get_actions',response.data)
            } catch (error) {
                console.error(error);
                alert('getActions() error, please reload page')
            }
        },

        getMethods({commit}){
            axios({
                method : 'get',
                url : 'api/get_methods'
            }).then(res => {
                // console.log(res.data,'getMethods')
                commit('mutation_get_methods', res.data)
            }).catch(err=>{
                console.error(err);
                alert('getMethods() error, please reload page')
            })
        },

        getPcgtypes({commit}){
            axios({
                method : 'get',
                url : 'api/get_pcgtypes'
            }).then(res => {
                // console.log(res.data,'getPcgtypes')
                commit('mutation_get_pcgtypes', res.data)
            }).catch(err=>{
                console.log(err,'error')
                alert('getPcgtypes() error, please reload page')
            })
        },

        getCategories({commit}){
            axios({
                method : 'get',
                url : 'api/get_categories'
            }).then(res => {
                // console.log(res.data,'getCategories');
                commit('mutation_get_categories', res.data)
            }).catch(err=>{
                console.log(err,'error')
                alert('getCategories() error, please reload page')
            })
        },

        
        getTeams({commit}){
            axios({
                method : 'get',
                url : 'api/get_teams'
            }).then(res => {
                // console.log(res.data,'getteams')
                commit('mutation_get_teams', res.data)
            }).catch(err=>{
                console.log(err,'error')
                alert('getTeams() error, please reload page')
            })
        },

        async getUsers({commit}, payload){
            await axios.get('api/getUsers',{
                params:{
                    user_id : payload.user_id
                }
            }).then(res => {
                // console.log(res.data,'getUSERS')
                commit('mutation_get_users', res.data)
            }).catch(err=>{
                console.log(err,'error')
                alert('getUsers() error, please reload page')
            })
        },

        deleteKeyItems({commit},data){
            commit("deleteKeyItems",data)
        },

        updateCartTerm({commit},data){
            commit("setCartTerm",data)
        }

    },

    mutations:{
        setCartTerm(state,payload){
            state.cart_search = payload
        },

        deleteKeyItems(state, payload){
            state.carte_users = payload
            state.method_items = payload
            state.pcgType_items = payload
            state.carte_categories = payload
            state.versions = payload
        },

        mutation_get_versions(state,payload){
            state.versions = payload.query1
            // state.state.userInfo = null
            // this.$router.push("/login");
        },

        mutation_get_actions(state,payload){
            state.action_items = payload
        },

        mutation_get_users(state,payload){
            state.carte_users = payload
        },

        mutation_get_teams(state,payload){
            state.team_items = payload
        },

        mutation_get_pcgtypes(state,payload){
            state.pcgType_items = payload
        },

        mutation_get_methods(state,payload){
            state.method_items = payload
        },

        mutation_get_categories(state,payload){
            state.carte_categories = payload
        },

        mutation_monitoring(state,payload){
            state.MonitoringData = payload
        },

        login(state, data){
            state.loggedInUser = data
            state.isLoggedin = true
        },

        logout(state){
            state.loggedInUser = {}
            state.isLoggedin = false;
        },

        getUsers(state, data){
            state.allUsers = data
        },

        storeUserInfo(state,data){
            state.userInfo = data
        }
    },
    getters:{
        team_items(state){
            let data = state.team_items.filter(r => {
                return r.pcg_team_code != 1;
            })
            return data;
        },

        new_carte_users(state){
            let excludedCodes =  ['36396','20042','06995']
            let data = state.carte_users.filter(item => {
                return !excludedCodes.includes(item.EmployeeCode);
            })
            return data;
        },
    },
    plugins: [persistedData]
});