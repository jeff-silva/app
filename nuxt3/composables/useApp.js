// import { useState } from '#app';
import { useAppStore } from '@/stores/app';

export default function() {
    const app = useAppStore();
    app.load();
    return app;
};