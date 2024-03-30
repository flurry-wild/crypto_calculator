import { createWebHistory, createRouter } from "vue-router";
import Index from './components/Pages/Index';

const routes = [
];

const router = createRouter({
    history: createWebHistory(),
    routes: routes,
});

/*router.beforeEach((to, from, next) => {
    const accessToken = localStorage.getItem('access_token');

    if (!accessToken) {
        if (to.name === 'user.login' || to.name === 'user.registration') {
            return next()
        } else {
            return next({
                name: 'pages.index'
            })
        }
    }

    if (to.name === 'user.login' && accessToken) {
        return next({name: 'pages.index'})
    }

    next();
})*/

export default router;
