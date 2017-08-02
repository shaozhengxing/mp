var router = [{
    /* list */
    path: '/',
    name: 'index',
    component: require('./components/Listing.vue')
}, {
    /* vad */
    path: '/vad/:id',
    name: 'vad',
    component: require('./components/Vad.vue')
}]

export default router
