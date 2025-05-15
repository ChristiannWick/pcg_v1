import Vue from 'vue'
import VueRouter from 'vue-router'
import store from './store'

Vue.use(VueRouter)
import Home from './components/Home'
import Hello from './components/Hello'
import Master from './components/Master'
import PCGM from './components/PCGMonitoring.vue'
import PCGR from './components/PCGReport.vue'
import PCGC from './components/PCGChart.vue'
import Login from './components/Login.vue'
import PCG_test from './components/PCG_test.vue'
import PCG_Report from './components/PCGTroper.vue'
import ImportExcel from './components/ImportExcel.vue'
import Checked from './components/Checked.vue'
import CommonTerms from './components/CommonTerms.vue'

const router = new VueRouter({
    mode: 'history',
    // base: 'carte-pcg-v1',
    routes: [
        {
            path: '/home',
            name: 'home',
            component: Home
        },
        {
          path: '/Monitoring',
          name: 'Monitoring',
          component: PCG_test
      },
        {
            path: '/hello',
            name: 'hello',
            component: Hello
        },

        {
            path: '/master',
            name: 'master',
            component : Master
        },
        {
            path: '/pcgMonitoring',
            name: 'pcgMonitoring',
            component : PCGM
        },
        {
            path: '/pcgReport',
            name: 'pcgReport',
            component : PCGR
        },
        {
            path: '/login',
            name: 'login',
            component : Login
        },
        {
            path: '/pcgChart',
            name: 'pcgChart',
            component : PCGC
        },
        {
            path: '/Report',
            name: 'Report',
            component : PCG_Report
        },
        {
            path: '/import_excel',
            name: 'import_excel',
            component : ImportExcel
        },
        {
            path: '/checked',
            name: 'checked',
            component : Checked
        },
        {
            path: '/common_terms',
            name: 'common_terms',
            component : CommonTerms
        },
    ],
});

router.beforeEach((to, from, next) => {
  if(to.path.toLowerCase() !== '/login' ){
    console.log('if path',to.path)
    if(store.state.userInfo!=null ){
      if(to.path.toLowerCase() ==='/master'){
        if(store.state.userInfo.admin === 2 || store.state.userInfo.admin === 1){
          next()
        }else{
          next('/Monitoring')
        }
      }else if(to.path ==='/checked'){
        if(store.state.userInfo.admin === 1){
          next()
        }else{
          next('/Monitoring')
        }
      }else{
        next()
      }
      
    }
    else{
      next('/login')
    }
  }else{
    console.log('else path',to.path)
    if(store.state.userInfo != null) {
      next('/Monitoring')
    }else {
      next()
    }
  }
})

router.afterEach((to, from) => {
  if (to.matched.length === 0) {
    router.push('/Monitoring');
  }
});

export default router

// if(to.path !== '/login'){
//   if(store.state.userInfo != null ){
//     next()
//   }else{
//     next('/login')
//   }
// }else{
//   if(store.state.userInfo != null) {
//     next('/Monitoring')
//   }else {
//     next()
//   }
// }


