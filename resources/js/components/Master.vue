<template>
  <div style="padding:4px; ">
    <v-card class="mx-auto" tile elevation="0">
      <!-- <v-card-title>
        <h1 class="display-1 font-weight-bold primary--text">Master</h1>
        <v-spacer/>
        <v-btn v-show="tab == 4" @click="updateEmployees()" >Update Users</v-btn>
        <v-btn 
          small
          v-if="tab == 4"
          color="primary"
          @click="handleButtonClick()"
        >Add
          <v-icon dark small>mdi-plus</v-icon>
        </v-btn>
      </v-card-title>
      <v-card-subtitle>
        <i style="font-size:12px;">Changes in master, users are required to (CTRL+SHIFT+DELETE, then check "Cookies and other site data" and "Cached images and files" then click "clear data") and reload this page.</i>
      </v-card-subtitle> -->
      <v-sheet >
        <v-tabs
          v-model="tab"
          background-color="transparent"
          centered
          >
          <v-tabs-slider color="teal darken-2"></v-tabs-slider>
          <v-tab
            v-for="(item, index) in items"
            :key="index"
          >
            <div class="tab-item">
              {{ item }}
            </div>
          </v-tab>
        </v-tabs>
      </v-sheet>
      <Method v-if="tab == 0"></Method>
      <PcgType v-else-if="tab == 1"></PcgType>
      <Category v-else-if="tab == 2"></Category>
      <Team v-else-if="tab == 3"></Team>
      <User v-else-if="tab == 4"></User>
      <Action v-else-if="tab == 5"></Action>
    </v-card>
  </div>
</template>
<script>

import Swal from 'sweetalert2';
import { mapActions, mapState } from 'vuex'
import Action from './master/Action.vue'
import Method from './master/Method.vue'
import PcgType from './master/PcgType.vue'
import Category from './master/Category.vue'
import Team from './master/Team.vue'
import User from './master/User.vue'

   export default {
      components: {
        Action,
        Method,
        PcgType,
        Category,
        Team,
        User,
      },
      data: () => ({
        tab: null,
        items: [
          'Methods', 
          'PCG Types', 
          'Categories', 
          'Teams', 
          'Users',
          'Actions'
        ],
        payload : {
          user_id: ''
        }
        
      }),

      methods: {
        ...mapActions([
          'getMethods',
          'getPcgtypes',
          "getCategories",
          'getTeams',
          'getUsers',
          "getActions"
        ]),

        updateEmployees(){
          axios({
            method : 'post',
            url : 'updateEmployees'
          }).then(res => {
            console.log(res.data,'updateEmployees');
            this.$store.dispatch('getUsers',[])
          })
        },

      },
      
      mounted() {
        this.getMethods();
        this.getPcgtypes();
        this.getCategories();
        this.getTeams();
        this.getUsers(this.payload);
        this.getActions();
        // console.log(this.userInfo,'user')
    
      }
   }
</script>

<style>

.tab-item {
  font-weight: bold;
  font-size: 15px;
  font-family: "Courier New", monospace;
  color: #238f6ded;
}


</style>