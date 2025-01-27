//My vite.config.js will becomes like this

import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import vue from "@vitejs/plugin-vue";

export default defineConfig({
server: {
    hmr: {
        host: "localhost",
        protocol: "ws",
        port: 3000,
    },
    port: 3000,
    host: true,
    watch: {
        usePolling: true,
        interval: 10, // Adjust the polling interval as needed
    },
},
plugins: [
    vue(),
    laravel({
        input: ["resources/css/app.css", "resources/js/app.js"],
        refresh: true,
    }),
],
});