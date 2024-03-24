const vite = require("laravel-mix-vite");

mix.vue()
    .js("resources/js/app.js", "public/js")
    .postCss("resources/css/app.css", "public/css", [
        vite(),
        require("postcss-import"),
        require("tailwindcss"),
        require("autoprefixer"),
    ])
    .alias({
        "@": "resources/js",
    });
