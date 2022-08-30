// import * as nuxt from '#app';

export default async (req, res) => {
    const app = useApp();
    await app.load();

    // // Admin guard
    // if (req.path.startsWith('/admin') && !app.user) {
    //     return navigateTo('/auth');
    // }
};