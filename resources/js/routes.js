export default [
    { path: '/dashboard', component: require('./components/Dashboard.vue').default },
    { path: '/profile', component: require('./components/Profile.vue').default },
    { path: '/developer', component: require('./components/Developer.vue').default },
    { path: '/users', component: require('./components/Users.vue').default },
    { path: '/items', component: require('./components/product/Items.vue').default },
    { path: '/displayItems', component: require('./components/product/DisplayItem.vue').default },
    { path: '/stockItems', component: require('./components/product/StockItem.vue').default },
    { path: '/transactions', component: require('./components/Transaction.vue').default },
    { path: '/orders', component: require('./components/Order.vue').default },
    { path: '*', component: require('./components/NotFound.vue').default }
];
