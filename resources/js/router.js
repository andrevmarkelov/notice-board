import { createRouter, createWebHistory } from 'vue-router';

const routes = [
    {
        path: '/',
        component: () => import('./Pages/index.vue')
    },
    {
        path: '/ad/:id',
        component: () => import('./Pages/ad/[ad].vue')
    },
    {
        path: '/ad/create',
        component: () => import('./Pages/ad/create.vue')
    },
];

export default createRouter({
    history: createWebHistory(),
    routes,
});
